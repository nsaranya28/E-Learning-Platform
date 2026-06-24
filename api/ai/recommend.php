<?php
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

// Configuration
$aiProvider = getenv('AI_PROVIDER') ?: 'simulated';
$apiKey = getenv('OPENAI_API_KEY') ?: 'your-key-here';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
    }

    $rawInput = json_decode(file_get_contents('php://input'), true);
    $userId = intval($rawInput['user_id'] ?? $_POST['user_id'] ?? 0);
    $interests = $rawInput['interests'] ?? $_POST['interests'] ?? [];
    $completedCourses = $rawInput['completed_courses'] ?? $_POST['completed_courses'] ?? [];
    $skillLevel = sanitize($rawInput['skill_level'] ?? $_POST['skill_level'] ?? 'beginner');

    if (is_string($interests)) {
        $interests = json_decode($interests, true) ?? [];
    }
    if (is_string($completedCourses)) {
        $completedCourses = json_decode($completedCourses, true) ?? [];
    }

    if ($aiProvider !== 'simulated') {
        /*
        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a course recommendation engine for SmartLearn. Suggest relevant courses based on user interests, skill level, and completed courses.'],
                    ['role' => 'user', 'content' => json_encode(['interests' => $interests, 'skill_level' => $skillLevel, 'completed_courses' => $completedCourses])]
                ],
                'temperature' => 0.3,
                'max_tokens' => 1000
            ]),
            CURLOPT_RETURNTRANSFER => true
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        // Parse and return recommendations from AI response
        */
    }

    $db = getDB();
    $recommendations = [];
    $excludeIds = array_map('intval', $completedCourses);

    // Build category interest mapping
    $interestMap = [];
    $categoryKeywords = [
        'web' => 1, 'html' => 1, 'css' => 1, 'javascript' => 1, 'php' => 1, 'react' => 1, 'vue' => 1, 'node' => 1,
        'frontend' => 1, 'backend' => 1, 'full-stack' => 1, 'fullstack' => 1,
        'data' => 2, 'python' => 2, 'machine learning' => 2, 'ai' => 2, 'analytics' => 2, 'statistics' => 2,
        'mobile' => 3, 'android' => 3, 'ios' => 3, 'flutter' => 3, 'react native' => 3, 'swift' => 3, 'kotlin' => 3,
        'devops' => 4, 'cloud' => 4, 'aws' => 4, 'docker' => 4, 'kubernetes' => 4, 'ci/cd' => 4, 'azure' => 4, 'gcp' => 4,
        'design' => 5, 'ui' => 5, 'ux' => 5, 'figma' => 5, 'photoshop' => 5, 'graphic' => 5,
        'business' => 6, 'marketing' => 6, 'seo' => 6, 'entrepreneur' => 6, 'digital marketing' => 6,
        'it' => 7, 'networking' => 7, 'security' => 7, 'cyber' => 7, 'linux' => 7,
        'personal' => 8, 'leadership' => 8, 'communication' => 8, 'productivity' => 8
    ];

    $targetCategories = [];
    if (!empty($interests)) {
        foreach ($interests as $interest) {
            $interest = strtolower(trim($interest));
            if (isset($categoryKeywords[$interest])) {
                $targetCategories[] = $categoryKeywords[$interest];
            } else {
                foreach ($categoryKeywords as $keyword => $catId) {
                    if (strpos($interest, $keyword) !== false) {
                        $targetCategories[] = $catId;
                    }
                }
            }
        }
    }

    if (!empty($interests)) {
        // Query by matching categories
        $targetCategories = array_unique($targetCategories);
        $placeholders = implode(',', array_fill(0, count($targetCategories), '?'));
        $params = $targetCategories;

        $sql = "SELECT c.id, c.title, c.short_description, c.level, c.price, c.rating, cat.name as category_name
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE c.status = 'published' AND c.category_id IN ($placeholders)";
        $orderClauses = [];

        // Match by level preference
        if (in_array($skillLevel, ['beginner', 'intermediate', 'advanced'])) {
            $sql .= " AND c.level = ?";
            $params[] = $skillLevel;
        }

        $sql .= " ORDER BY c.featured DESC, c.rating DESC, c.total_students DESC";
        $sql .= " LIMIT 20";

        if (!empty($excludeIds)) {
            $excludePlaceholders = implode(',', array_fill(0, count($excludeIds), '?'));
            $sql .= " AND c.id NOT IN ($excludePlaceholders)";
            $params = array_merge($params, $excludeIds);
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $courses = $stmt->fetchAll();

        foreach ($courses as $course) {
            $recommendations[] = [
                'id' => (int)$course['id'],
                'title' => $course['title'],
                'category' => $course['category_name'],
                'level' => $course['level'],
                'price' => (float)$course['price'],
                'rating' => (float)$course['rating'],
                'reason' => generateRecommendationReason($course, $interests)
            ];
        }
    }

    // Fall back to featured/popular courses if no interests or no matches
    if (empty($recommendations)) {
        $sql = "SELECT c.id, c.title, c.short_description, c.level, c.price, c.rating, cat.name as category_name
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE c.status = 'published'";
        $params = [];

        if (!empty($excludeIds)) {
            $excludePlaceholders = implode(',', array_fill(0, count($excludeIds), '?'));
            $sql .= " AND c.id NOT IN ($excludePlaceholders)";
            $params = $excludeIds;
        }

        $sql .= " ORDER BY c.featured DESC, c.total_students DESC, c.rating DESC LIMIT 10";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $courses = $stmt->fetchAll();

        foreach ($courses as $course) {
            $recommendations[] = [
                'id' => (int)$course['id'],
                'title' => $course['title'],
                'category' => $course['category_name'],
                'level' => $course['level'],
                'price' => (float)$course['price'],
                'rating' => (float)$course['rating'],
                'reason' => 'Popular course with high student enrollment and excellent ratings.'
            ];
        }
    }

    // Limit to top 10
    $recommendations = array_slice($recommendations, 0, 10);

    jsonResponse(['success' => true, 'recommendations' => $recommendations]);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}

function generateRecommendationReason($course, $interests) {
    $reasons = [
        "Matches your interest in " . ($interests[0] ?? 'technology') . " and is designed for " . $course['level'] . " learners.",
        "Highly rated by students in the " . $course['category_name'] . " category. Perfect for building " . $course['level'] . "-level skills.",
        "Recommended based on your skill profile. This " . $course['category_name'] . " course covers essential " . $course['level'] . " topics.",
        "Popular choice among learners with similar interests. Great for advancing your " . $course['category_name'] . " knowledge.",
        "This course aligns with your learning goals and has excellent student reviews in " . $course['category_name'] . "."
    ];
    return $reasons[array_rand($reasons)];
}
