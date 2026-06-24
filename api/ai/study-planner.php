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
    $courseId = intval($rawInput['course_id'] ?? $_POST['course_id'] ?? 0);
    $hoursPerDay = floatval($rawInput['hours_per_day'] ?? $_POST['hours_per_day'] ?? 1);
    $startDate = sanitize($rawInput['start_date'] ?? $_POST['start_date'] ?? date('Y-m-d'));
    $endDate = sanitize($rawInput['end_date'] ?? $_POST['end_date'] ?? '');
    $preferredTime = sanitize($rawInput['preferred_time'] ?? $_POST['preferred_time'] ?? 'morning');

    if ($courseId <= 0) {
        jsonResponse(['success' => false, 'message' => 'Course ID is required.'], 422);
    }

    if ($hoursPerDay < 0.5) $hoursPerDay = 0.5;
    if ($hoursPerDay > 12) $hoursPerDay = 12;

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
                    ['role' => 'system', 'content' => 'You are a study planner for SmartLearn. Generate a personalized study schedule in JSON format.'],
                    ['role' => 'user', 'content' => json_encode([
                        'course_id' => $courseId, 'hours_per_day' => $hoursPerDay,
                        'start_date' => $startDate, 'end_date' => $endDate, 'preferred_time' => $preferredTime
                    ])]
                ],
                'temperature' => 0.5,
                'max_tokens' => 2000
            ]),
            CURLOPT_RETURNTRANSFER => true
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
        $plan = json_decode($data['choices'][0]['message']['content'] ?? '{}', true);
        if (!empty($plan)) {
            jsonResponse(['success' => true, 'plan' => $plan]);
        }
        */
    }

    $db = getDB();

    // Get course and lesson info
    $stmt = $db->prepare("SELECT id, title, description, total_lessons, total_duration, duration FROM courses WHERE id = ? AND status = 'published'");
    $stmt->execute([$courseId]);
    $course = $stmt->fetch();

    if (!$course) {
        jsonResponse(['success' => false, 'message' => 'Course not found or not published.'], 404);
    }

    $stmt = $db->prepare("SELECT id, title, duration, lesson_order FROM lessons WHERE course_id = ? AND status = 'active' ORDER BY lesson_order");
    $stmt->execute([$courseId]);
    $lessons = $stmt->fetchAll();

    if (empty($lessons)) {
        // Generate synthetic lessons based on course title
        $topics = explodeTopics($course['title'], $course['description']);
        $lessons = [];
        $order = 1;
        foreach ($topics as $topic) {
            $lessons[] = [
                'id' => 0,
                'title' => $topic,
                'duration' => '30:00',
                'lesson_order' => $order++
            ];
        }
    }

    // Calculate time parameters
    $start = new DateTime($startDate);
    $end = $endDate ? new DateTime($endDate) : (new DateTime())->add(new DateInterval('P30D'));
    if ($end <= $start) {
        $end = (clone $start)->add(new DateInterval('P30D'));
    }

    $totalDays = $start->diff($end)->days + 1;
    $totalAvailableHours = $totalDays * $hoursPerDay;

    // Calculate total lesson minutes
    $totalLessonMinutes = 0;
    foreach ($lessons as $lesson) {
        $durationStr = $lesson['duration'] ?? '30:00';
        $parts = explode(':', $durationStr);
        $minutes = (int)($parts[0] ?? 0) * 60 + (int)($parts[1] ?? 0);
        $totalLessonMinutes += $minutes;
    }
    if ($totalLessonMinutes <= 0) $totalLessonMinutes = count($lessons) * 30;

    $totalHours = ceil($totalLessonMinutes / 60);

    // Distribute lessons across days
    $timeSlots = [
        'morning' => '09:00',
        'afternoon' => '14:00',
        'evening' => '19:00',
        'night' => '21:00'
    ];
    $startTime = $timeSlots[$preferredTime] ?? '09:00';

    $weeklySchedule = [];
    $lessonIndex = 0;
    $totalLessons = count($lessons);
    $lessonsPerDay = max(1, round($totalLessons / $totalDays));

    for ($day = 0; $day < $totalDays; $day++) {
        $currentDate = (clone $start)->add(new DateInterval("P{$day}D"));
        $weekNumber = floor($day / 7) + 1;

        if (!isset($weeklySchedule[$weekNumber])) {
            $weeklySchedule[$weekNumber] = [
                'week' => $weekNumber,
                'days' => []
            ];
        }

        $dailyLessons = [];
        $dailyMinutes = 0;
        $maxDailyMinutes = $hoursPerDay * 60;

        for ($i = 0; $i < $lessonsPerDay && $lessonIndex < $totalLessons; $i++) {
            $lesson = $lessons[$lessonIndex];
            $durationStr = $lesson['duration'] ?? '30:00';
            $parts = explode(':', $durationStr);
            $minutes = (int)($parts[0] ?? 0) * 60 + (int)($parts[1] ?? 0);

            if ($dailyMinutes + $minutes > $maxDailyMinutes && $dailyMinutes > 0) {
                break;
            }

            $dailyLessons[] = [
                'lesson_id' => $lesson['id'],
                'title' => $lesson['title'],
                'duration' => $durationStr,
                'order' => $lesson['lesson_order']
            ];
            $dailyMinutes += $minutes;
            $lessonIndex++;
        }

        if (!empty($dailyLessons)) {
            $weeklySchedule[$weekNumber]['days'][] = [
                'date' => $currentDate->format('Y-m-d'),
                'day_name' => $currentDate->format('l'),
                'topic' => 'Course Progress - Week ' . $weekNumber,
                'duration_minutes' => $dailyMinutes,
                'start_time' => $startTime,
                'lessons' => $dailyLessons
            ];
        }
    }

    $plan = [
        'course_title' => $course['title'],
        'total_days' => $totalDays,
        'total_hours' => $totalHours,
        'daily_hours' => $hoursPerDay,
        'preferred_time' => $preferredTime,
        'start_date' => $startDate,
        'end_date' => $end->format('Y-m-d'),
        'total_lessons' => $totalLessons,
        'weekly_schedule' => array_values($weeklySchedule)
    ];

    jsonResponse(['success' => true, 'plan' => $plan]);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}

function explodeTopics($title, $description) {
    $topics = [
        'Web Development' => ['Introduction to HTML', 'CSS Fundamentals', 'JavaScript Basics', 'Responsive Design', 'Frontend Frameworks', 'Backend Development', 'Databases & SQL', 'Deployment & DevOps'],
        'Data Science' => ['Python for Data Science', 'Statistics Fundamentals', 'Data Wrangling', 'Data Visualization', 'Machine Learning Basics', 'Deep Learning', 'Natural Language Processing', 'Big Data Analytics'],
        'Mobile Development' => ['Mobile UI Design', 'App Architecture', 'Platform Fundamentals', 'State Management', 'API Integration', 'Testing & Debugging', 'App Store Deployment', 'Performance Optimization'],
        'DevOps' => ['Linux Fundamentals', 'Networking Basics', 'Containerization with Docker', 'Orchestration with Kubernetes', 'CI/CD Pipelines', 'Infrastructure as Code', 'Monitoring & Logging', 'Cloud Services'],
        'Design' => ['Design Principles', 'Color Theory', 'Typography', 'User Research', 'Wireframing', 'Prototyping', 'Visual Design', 'Design Systems'],
        'Business' => ['Market Research', 'Digital Strategy', 'Content Marketing', 'SEO Fundamentals', 'Social Media Marketing', 'Email Marketing', 'Analytics & KPIs', 'Growth Hacking'],
        'IT' => ['Computer Networks', 'Operating Systems', 'Cybersecurity Basics', 'Network Security', 'System Administration', 'Cloud Computing', 'ITIL Fundamentals', 'Disaster Recovery'],
        'Personal Development' => ['Goal Setting', 'Time Management', 'Effective Communication', 'Leadership Skills', 'Critical Thinking', 'Emotional Intelligence', 'Public Speaking', 'Conflict Resolution']
    ];

    $lower = strtolower($title . ' ' . $description);
    foreach ($topics as $key => $subtopics) {
        if (strpos($lower, strtolower($key)) !== false) {
            return $subtopics;
        }
    }

    // Default topics if nothing matches
    return [
        'Introduction & Overview',
        'Core Concepts',
        'Fundamentals Part 1',
        'Fundamentals Part 2',
        'Intermediate Topics',
        'Advanced Concepts',
        'Practical Applications',
        'Review & Assessment'
    ];
}
