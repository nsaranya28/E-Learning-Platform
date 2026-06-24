<?php
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
    }

    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please log in to enroll in courses.'], 401);
    }

    $user_id   = $_SESSION['user_id'];
    $course_id = intval($_POST['course_id'] ?? 0);

    if (empty($course_id)) {
        jsonResponse(['success' => false, 'message' => 'Course ID is required.'], 422);
    }

    $db = getDB();

    $stmt = $db->prepare("SELECT id, title, total_lessons, instructor_id FROM courses WHERE id = ? AND status = 'published'");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch();

    if (!$course) {
        jsonResponse(['success' => false, 'message' => 'Course not found or not published.'], 404);
    }

    $stmt = $db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    if ($stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'You are already enrolled in this course.'], 409);
    }

    $stmt = $db->prepare("INSERT INTO enrollments (user_id, course_id, total_lessons, status, enrolled_at) VALUES (?, ?, ?, 'active', NOW())");
    $stmt->execute([$user_id, $course_id, $course['total_lessons']]);

    $stmt = $db->prepare("UPDATE courses SET total_students = total_students + 1 WHERE id = ?");
    $stmt->execute([$course_id]);

    $stmt = $db->prepare("UPDATE instructors SET total_students = total_students + 1 WHERE id = ?");
    $stmt->execute([$course['instructor_id']]);

    $stmt = $db->prepare("SELECT full_name FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    logActivity('enrollment', 'New Enrollment', $user['full_name'] . ' enrolled in ' . $course['title'], $user_id, $course['instructor_id'], null, 'course-details.php?id=' . $course_id);

    jsonResponse(['success' => true, 'message' => 'Successfully enrolled in the course!', 'redirect' => 'my-courses.php']);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}
