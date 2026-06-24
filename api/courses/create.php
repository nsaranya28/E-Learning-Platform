<?php
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
    }

    if (!isInstructor()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized. Instructor login required.'], 401);
    }

    $instructor_id   = $_SESSION['instructor_id'];
    $title           = sanitize($_POST['title'] ?? '');
    $category_id     = intval($_POST['category_id'] ?? 0);
    $description     = sanitize($_POST['description'] ?? '');
    $short_description = sanitize($_POST['short_description'] ?? '');
    $level           = sanitize($_POST['level'] ?? 'beginner');
    $language        = sanitize($_POST['language'] ?? 'English');
    $price           = floatval($_POST['price'] ?? 0);
    $discount_price  = isset($_POST['discount_price']) && $_POST['discount_price'] !== '' ? floatval($_POST['discount_price']) : null;
    $requirements    = sanitize($_POST['requirements'] ?? '');
    $learning_objectives = $_POST['learning_objectives'] ?? '[]';
    $thumbnail       = sanitize($_POST['thumbnail'] ?? '');
    $duration        = sanitize($_POST['duration'] ?? '');

    if (empty($title) || empty($category_id)) {
        jsonResponse(['success' => false, 'message' => 'Title and category are required.'], 422);
    }

    $allowedLevels = ['beginner', 'intermediate', 'advanced', 'all_levels'];
    if (!in_array($level, $allowedLevels)) {
        $level = 'beginner';
    }

    if (is_string($learning_objectives)) {
        $decoded = json_decode($learning_objectives, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $learning_objectives = json_encode([]);
        }
    }

    $slug = createSlug($title);
    $db = getDB();

    $stmt = $db->prepare("SELECT id FROM courses WHERE slug = ?");
    $stmt->execute([$slug]);
    if ($stmt->fetch()) {
        $slug .= '-' . uniqid();
    }

    $stmt = $db->prepare("INSERT INTO courses (instructor_id, category_id, title, slug, description, short_description, level, language, price, discount_price, requirements, learning_objectives, thumbnail, duration, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft', NOW())");
    $stmt->execute([$instructor_id, $category_id, $title, $slug, $description, $short_description, $level, $language, $price, $discount_price, $requirements, $learning_objectives, $thumbnail, $duration]);

    $course_id = $db->lastInsertId();

    jsonResponse(['success' => true, 'message' => 'Course created successfully.', 'course_id' => $course_id, 'redirect' => 'instructor/course-edit.php?id=' . $course_id], 201);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}
