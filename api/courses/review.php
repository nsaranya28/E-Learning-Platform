<?php
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
    }

    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please log in to submit a review.'], 401);
    }

    $user_id     = $_SESSION['user_id'];
    $course_id   = intval($_POST['course_id'] ?? 0);
    $rating      = intval($_POST['rating'] ?? 0);
    $review_text = sanitize($_POST['review_text'] ?? '');

    if (empty($course_id) || $rating < 1 || $rating > 5) {
        jsonResponse(['success' => false, 'message' => 'Valid course ID and rating (1-5) are required.'], 422);
    }

    $db = getDB();

    $stmt = $db->prepare("SELECT id FROM courses WHERE id = ? AND status = 'published'");
    $stmt->execute([$course_id]);
    if (!$stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'Course not found.'], 404);
    }

    $stmt = $db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    if (!$stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'You must be enrolled to review this course.'], 403);
    }

    $stmt = $db->prepare("SELECT id FROM reviews WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt = $db->prepare("UPDATE reviews SET rating = ?, review_text = ?, created_at = NOW() WHERE id = ?");
        $stmt->execute([$rating, $review_text, $existing['id']]);
        $message = 'Review updated successfully.';
    } else {
        $stmt = $db->prepare("INSERT INTO reviews (user_id, course_id, rating, review_text, status, created_at) VALUES (?, ?, ?, ?, 'approved', NOW())");
        $stmt->execute([$user_id, $course_id, $rating, $review_text]);
        $message = 'Review submitted successfully.';
    }

    $stmt = $db->query("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE course_id = $course_id AND status = 'approved'");
    $stats = $stmt->fetch();
    $avgRating = round($stats['avg_rating'], 1);
    $totalReviews = $stats['total_reviews'];

    $stmt = $db->prepare("UPDATE courses SET rating = ?, review_count = ? WHERE id = ?");
    $stmt->execute([$avgRating, $totalReviews, $course_id]);

    jsonResponse(['success' => true, 'message' => $message, 'rating' => $avgRating, 'review_count' => $totalReviews]);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}
