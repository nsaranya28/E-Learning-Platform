<?php
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

// Configuration
$aiProvider = getenv('AI_PROVIDER') ?: 'simulated'; // simulated, openai, gemini
$apiKey = getenv('OPENAI_API_KEY') ?: 'your-key-here';
$geminiKey = getenv('GEMINI_API_KEY') ?: 'your-gemini-key-here';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
    }

    $rawInput = json_decode(file_get_contents('php://input'), true);
    $message = trim($rawInput['message'] ?? $_POST['message'] ?? '');
    $conversationHistory = $rawInput['conversation_history'] ?? $_POST['conversation_history'] ?? [];

    if (empty($message)) {
        jsonResponse(['success' => false, 'message' => 'Message is required.'], 422);
    }

    if ($aiProvider !== 'simulated') {
        // OpenAI integration (uncomment and set $apiKey for production)
        /*
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful SmartLearn platform assistant. Help users with courses, career guidance, study planning, pricing, and certificates.'],
            ['role' => 'user', 'content' => $message]
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => 'gpt-4',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 500
            ]),
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            $reply = $data['choices'][0]['message']['content'] ?? '';
            jsonResponse(['success' => true, 'message' => $reply]);
        }

        jsonResponse(['success' => false, 'message' => 'AI service error. Please try again later.'], 502);
        */

        // Gemini integration
        /*
        $payload = json_encode([
            'contents' => [['parts' => [['text' => $message]]]]
        ]);

        $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $geminiKey);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            jsonResponse(['success' => true, 'message' => $reply]);
        }

        jsonResponse(['success' => false, 'message' => 'AI service error. Please try again later.'], 502);
        */
    }

    // Simulated AI response based on keyword matching
    $msgLower = strtolower($message);

    $responses = [
        'course' => [
            'keywords' => ['course', 'class', 'learn', 'study', 'lesson', 'curriculum', 'subject', 'topic', 'module'],
            'response' => "SmartLearn offers a wide range of courses across Web Development, Data Science, Mobile Development, DevOps & Cloud, and more. Each course includes video lessons, quizzes, assignments, and a certificate upon completion. You can browse all courses at our Courses page. Which subject area interests you most?"
        ],
        'career' => [
            'keywords' => ['career', 'job', 'work', 'profession', 'employment', 'salary', 'hire', 'recruit', 'interview', 'resume', 'cv'],
            'response' => "SmartLearn's career guidance tools can help you find the right learning path for your goals. We recommend starting with our Career Guidance assessment, which analyzes your skills and interests to suggest optimal career paths. Popular tracks include Full-Stack Development, Data Science, Cloud Architecture, and AI/ML Engineering."
        ],
        'study' => [
            'keywords' => ['plan', 'schedule', 'time', 'organize', 'routine', 'calendar', 'deadline', 'goal', 'milestone'],
            'response' => "Our AI Study Planner creates personalized learning schedules based on your available time and goals. You can set daily study hours, preferred times, and target completion dates. Use the Study Planner in your dashboard to generate a custom plan that breaks down your course into manageable daily lessons."
        ],
        'pricing' => [
            'keywords' => ['price', 'cost', 'fee', 'payment', 'subscription', 'premium', 'free', 'trial', 'discount', 'coupon', 'purchase', 'buy', 'enroll'],
            'response' => "SmartLearn offers both free and paid courses. Many courses are available at no cost, while premium courses provide in-depth content, assignments, and certificates. We frequently run promotions and discounts. Check the course details page for specific pricing information and any available coupons."
        ],
        'certificate' => [
            'keywords' => ['certificate', 'certification', 'credential', 'diploma', 'badge', 'complete', 'graduate', 'accredit', 'verify'],
            'response' => "Upon completing a course on SmartLearn, you'll receive a verifiable certificate of completion. Each certificate includes a unique serial number and can be shared on LinkedIn or your resume. To earn a certificate, you must complete all lessons and achieve a passing score on the final quiz."
        ],
        'help' => [
            'keywords' => ['help', 'support', 'assist', 'guide', 'tutorial', 'how', 'what', 'where', 'when', 'why', 'which', 'who', 'problem', 'issue', 'error', 'bug', 'question', 'hello', 'hi'],
            'response' => "Welcome to SmartLearn! I'm your AI learning assistant. I can help you with:\n\n• Finding the right courses for your goals\n• Creating personalized study plans\n• Career guidance and skill recommendations\n• Information about pricing and certificates\n• Technical support and general inquiries\n\nWhat would you like to know more about?"
        ]
    ];

    $reply = null;
    // Check for more specific matches first (compound topics)
    if (preg_match('/(course|class|learn).*(career|job)/i', $message)) {
        $reply = "Our courses are designed with your career goals in mind. Each course teaches practical skills that employers value. For example, our Web Development bootcamp prepares you for front-end and full-stack roles, while Data Science courses build skills for analytics and ML positions. Check the course description for specific career outcomes.";
    } elseif (preg_match('/(certif|complete).*(course|class)/i', $message)) {
        $reply = "To earn a certificate, you need to complete all lessons and pass the final assessment with a score of 70% or higher. Your certificate will be available for download immediately upon completion. It includes your name, course title, completion date, and a unique verification code.";
    } elseif (preg_match('/study.*plan|plan.*study|schedule/i', $message)) {
        $reply = "Creating a study plan is easy! Use our AI Study Planner tool to generate a personalized schedule. You can specify how many hours per day you can study, your preferred time of day, and your target completion date. The planner will distribute your course content across available days and track your progress.";
    }

    // Fall back to keyword-based matching
    if (!$reply) {
        foreach ($responses as $topic => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (strpos($msgLower, $keyword) !== false) {
                    $reply = $data['response'];
                    break 2;
                }
            }
        }
    }

    // Default response if no keywords matched
    if (!$reply) {
        $reply = $responses['help']['response'];
    }

    jsonResponse(['success' => true, 'message' => $reply]);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}
