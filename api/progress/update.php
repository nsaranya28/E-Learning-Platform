<?php
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
    }

    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please log in to track progress.'], 401);
    }

    $user_id       = $_SESSION['user_id'];
    $course_id     = intval($_POST['course_id'] ?? 0);
    $lesson_id     = intval($_POST['lesson_id'] ?? 0);
    $watched       = !empty($_POST['watched']);
    $watch_duration = intval($_POST['watch_duration'] ?? 0);

    if (empty($course_id) || empty($lesson_id)) {
        jsonResponse(['success' => false, 'message' => 'Course ID and Lesson ID are required.'], 422);
    }

    $db = getDB();

    $stmt = $db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ? AND status = 'active'");
    $stmt->execute([$user_id, $course_id]);
    if (!$stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'You are not enrolled in this course.'], 403);
    }

    $stmt = $db->prepare("SELECT id FROM lessons WHERE id = ? AND course_id = ? AND status = 'active'");
    $stmt->execute([$lesson_id, $course_id]);
    if (!$stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'Lesson not found in this course.'], 404);
    }

    $completed_at = $watched ? date('Y-m-d H:i:s') : null;

    $stmt = $db->prepare("INSERT INTO lesson_progress (user_id, lesson_id, course_id, watched, watch_duration, completed_at) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE watched = VALUES(watched), watch_duration = VALUES(watch_duration), completed_at = VALUES(completed_at)");
    $stmt->execute([$user_id, $lesson_id, $course_id, $watched ? 1 : 0, $watch_duration, $completed_at]);

    $stmt = $db->prepare("SELECT COUNT(*) as total FROM lessons WHERE course_id = ? AND status = 'active'");
    $stmt->execute([$course_id]);
    $totalLessons = (int)$stmt->fetch()['total'];

    $stmt = $db->prepare("SELECT COUNT(*) as completed FROM lesson_progress WHERE user_id = ? AND course_id = ? AND watched = 1");
    $stmt->execute([$user_id, $course_id]);
    $completedLessons = (int)$stmt->fetch()['completed'];

    $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;

    $status = $progress >= 100 ? 'completed' : 'active';
    $completedAt = $progress >= 100 ? date('Y-m-d H:i:s') : null;

    $stmt = $db->prepare("UPDATE enrollments SET progress = ?, completed_lessons = ?, total_lessons = ?, status = ?, completed_at = ? WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$progress, $completedLessons, $totalLessons, $status, $completedAt, $user_id, $course_id]);

    jsonResponse(['success' => true, 'message' => 'Progress updated.', 'progress' => $progress, 'completed_lessons' => $completedLessons, 'total_lessons' => $totalLessons]);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}
