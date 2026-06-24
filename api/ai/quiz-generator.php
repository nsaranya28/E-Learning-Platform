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
    $courseId = intval($rawInput['course_id'] ?? $_POST['course_id'] ?? 0);
    $topic = sanitize($rawInput['topic'] ?? $_POST['topic'] ?? '');
    $difficulty = sanitize($rawInput['difficulty'] ?? $_POST['difficulty'] ?? 'intermediate');
    $numQuestions = intval($rawInput['num_questions'] ?? $_POST['num_questions'] ?? 10);

    $allowedDifficulties = ['beginner', 'intermediate', 'advanced'];
    if (!in_array($difficulty, $allowedDifficulties)) {
        $difficulty = 'intermediate';
    }

    if ($numQuestions < 1) $numQuestions = 5;
    if ($numQuestions > 50) $numQuestions = 50;

    if (empty($topic) && $courseId <= 0) {
        jsonResponse(['success' => false, 'message' => 'Either course_id or topic is required.'], 422);
    }

    if ($aiProvider !== 'simulated') {
        /*
        $prompt = "Generate " . $numQuestions . " " . $difficulty . "-level quiz questions about " . ($topic ?: "course id " . $courseId) . ". Return JSON with title and questions array. Each question must have: question_text, question_type (multiple_choice or true_false), options (array), correct_answer, explanation, points (int).";

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
                    ['role' => 'system', 'content' => 'You are a quiz generator for SmartLearn. Generate educational quiz questions in JSON format.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 3000
            ]),
            CURLOPT_RETURNTRANSFER => true
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
        $quizData = json_decode($data['choices'][0]['message']['content'] ?? '{}', true);
        if (isset($quizData['questions'])) {
            jsonResponse(['success' => true, 'quiz' => $quizData]);
        }
        */
    }

    $db = getDB();
    $courseTitle = '';
    $lessonTitles = [];

    if ($courseId > 0) {
        $stmt = $db->prepare("SELECT id, title, description FROM courses WHERE id = ? AND status = 'published'");
        $stmt->execute([$courseId]);
        $course = $stmt->fetch();
        if ($course) {
            $courseTitle = $course['title'];
        }

        $stmt = $db->prepare("SELECT id, title FROM lessons WHERE course_id = ? AND status = 'active' ORDER BY lesson_order LIMIT 20");
        $stmt->execute([$courseId]);
        $lessonTitles = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    $title = $courseTitle ? "Quiz: " . $courseTitle : ($topic ? "Quiz: " . $topic : "Knowledge Check");
    if (empty($courseTitle) && empty($topic)) {
        $topic = 'General Knowledge';
        $title = 'Knowledge Check';
    }

    $questions = generateSampleQuestions($topic ?: $courseTitle, $difficulty, $numQuestions, $lessonTitles);

    $quiz = [
        'title' => $title,
        'difficulty' => $difficulty,
        'total_questions' => count($questions),
        'total_points' => array_sum(array_column($questions, 'points')),
        'questions' => $questions
    ];

    jsonResponse(['success' => true, 'quiz' => $quiz]);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}

function generateSampleQuestions($topic, $difficulty, $count, $lessonTitles = []) {
    $samplePool = [
        // Multiple choice questions
        [
            'question_text' => 'What is the primary purpose of version control systems like Git?',
            'question_type' => 'multiple_choice',
            'options' => ['To track changes in code', 'To compile code faster', 'To design user interfaces', 'To manage databases'],
            'correct_answer' => 'To track changes in code',
            'explanation' => 'Version control systems track changes to files over time, enabling collaboration and rollback to previous versions.',
            'points' => 10
        ],
        [
            'question_text' => 'Which programming paradigm uses functions as first-class citizens?',
            'question_type' => 'multiple_choice',
            'options' => ['Object-Oriented', 'Functional', 'Procedural', 'Event-Driven'],
            'correct_answer' => 'Functional',
            'explanation' => 'Functional programming treats functions as first-class citizens, allowing them to be passed as arguments, returned, and assigned to variables.',
            'points' => 10
        ],
        [
            'question_text' => 'What does SQL stand for?',
            'question_type' => 'multiple_choice',
            'options' => ['Structured Query Language', 'Simple Query Language', 'Standard Query Logic', 'Sequential Query Language'],
            'correct_answer' => 'Structured Query Language',
            'explanation' => 'SQL stands for Structured Query Language, used for managing and querying relational databases.',
            'points' => 5
        ],
        [
            'question_text' => 'In object-oriented programming, what is encapsulation?',
            'question_type' => 'multiple_choice',
            'options' => ['Bundling data and methods together', 'Creating multiple classes', 'Inheriting properties', 'Overriding methods'],
            'correct_answer' => 'Bundling data and methods together',
            'explanation' => 'Encapsulation bundles data and methods that operate on that data within a single unit (class), restricting direct access to some components.',
            'points' => 10
        ],
        [
            'question_text' => 'Which HTTP method is typically used to update an existing resource?',
            'question_type' => 'multiple_choice',
            'options' => ['GET', 'POST', 'PUT', 'DELETE'],
            'correct_answer' => 'PUT',
            'explanation' => 'PUT is used to update or replace an existing resource, while POST creates new resources and GET retrieves them.',
            'points' => 5
        ],
        [
            'question_text' => 'What is the time complexity of binary search?',
            'question_type' => 'multiple_choice',
            'options' => ['O(n)', 'O(log n)', 'O(n²)', 'O(1)'],
            'correct_answer' => 'O(log n)',
            'explanation' => 'Binary search has O(log n) time complexity because it halves the search space with each comparison.',
            'points' => 10
        ],
        [
            'question_text' => 'What is the main purpose of a REST API?',
            'question_type' => 'multiple_choice',
            'options' => ['To provide a standardized way to communicate between systems', 'To render HTML pages', 'To manage file storage', 'To compile code'],
            'correct_answer' => 'To provide a standardized way to communicate between systems',
            'explanation' => 'REST APIs provide a standardized, stateless architecture for building web services that different systems can use to communicate.',
            'points' => 10
        ],
        [
            'question_text' => 'What is a primary key in a database?',
            'question_type' => 'multiple_choice',
            'options' => ['A unique identifier for each row', 'A column used for sorting', 'A foreign reference', 'An index for searching'],
            'correct_answer' => 'A unique identifier for each row',
            'explanation' => 'A primary key uniquely identifies each row in a database table and cannot contain NULL values.',
            'points' => 5
        ],
        [
            'question_text' => 'Which protocol ensures secure data transmission over the web?',
            'question_type' => 'multiple_choice',
            'options' => ['HTTP', 'FTP', 'HTTPS', 'SMTP'],
            'correct_answer' => 'HTTPS',
            'explanation' => 'HTTPS (HTTP Secure) encrypts data using SSL/TLS, ensuring secure communication between client and server.',
            'points' => 5
        ],
        [
            'question_text' => 'What is the main benefit of using Docker containers?',
            'question_type' => 'multiple_choice',
            'options' => ['Consistent environment across different systems', 'Faster code execution', 'Better user interface', 'Automatic code generation'],
            'correct_answer' => 'Consistent environment across different systems',
            'explanation' => 'Docker containers package an application with all its dependencies, ensuring it runs consistently across any environment.',
            'points' => 10
        ],
        [
            'question_text' => 'What does CSS stand for?',
            'question_type' => 'multiple_choice',
            'options' => ['Cascading Style Sheets', 'Computer Style System', 'Creative Style Sheets', 'Colorful Style Sheets'],
            'correct_answer' => 'Cascading Style Sheets',
            'explanation' => 'CSS (Cascading Style Sheets) is used to style and layout web pages, controlling colors, fonts, and positioning.',
            'points' => 5
        ],
        [
            'question_text' => 'Which data structure operates on a Last-In-First-Out (LIFO) principle?',
            'question_type' => 'multiple_choice',
            'options' => ['Queue', 'Stack', 'Array', 'Linked List'],
            'correct_answer' => 'Stack',
            'explanation' => 'A stack follows LIFO principle, where the last element added is the first one removed, like a stack of plates.',
            'points' => 10
        ],
        [
            'question_text' => 'What is the purpose of a JOIN operation in SQL?',
            'question_type' => 'multiple_choice',
            'options' => ['To combine rows from multiple tables', 'To delete records', 'To create new tables', 'To update records'],
            'correct_answer' => 'To combine rows from multiple tables',
            'explanation' => 'JOIN combines rows from two or more tables based on a related column between them.',
            'points' => 10
        ],
        [
            'question_text' => 'What does CI/CD stand for in DevOps?',
            'question_type' => 'multiple_choice',
            'options' => ['Continuous Integration/Continuous Deployment', 'Code Integration/Code Deployment', 'Continuous Improvement/Continuous Development', 'Central Integration/Central Deployment'],
            'correct_answer' => 'Continuous Integration/Continuous Deployment',
            'explanation' => 'CI/CD automates the building, testing, and deployment of code changes, enabling faster and more reliable software delivery.',
            'points' => 10
        ],
        // True/False questions
        [
            'question_text' => 'JavaScript is a statically typed programming language.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'False',
            'explanation' => 'JavaScript is dynamically typed, meaning variable types are determined at runtime, not at compile time.',
            'points' => 5
        ],
        [
            'question_text' => 'HTTPS encrypts data between the client and server using SSL/TLS.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'True',
            'explanation' => 'HTTPS uses SSL/TLS encryption to secure data transmission, protecting against eavesdropping and tampering.',
            'points' => 5
        ],
        [
            'question_text' => 'In SQL, the DELETE command removes the table structure from the database.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'False',
            'explanation' => 'DELETE removes rows from a table. To remove the table structure, you use the DROP TABLE command.',
            'points' => 5
        ],
        [
            'question_text' => 'Agile methodology follows a strictly sequential development process.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'False',
            'explanation' => 'Agile is iterative and incremental, not strictly sequential. The Waterfall model is sequential.',
            'points' => 5
        ],
        [
            'question_text' => 'A primary key column can contain NULL values.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'False',
            'explanation' => 'Primary keys cannot contain NULL values because they must uniquely identify each row.',
            'points' => 5
        ],
        [
            'question_text' => 'Docker containers share the host operating system kernel.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'True',
            'explanation' => 'Unlike virtual machines, Docker containers share the host OS kernel, making them more lightweight and efficient.',
            'points' => 5
        ],
        [
            'question_text' => 'React uses a virtual DOM to improve performance.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'True',
            'explanation' => 'React maintains a virtual DOM in memory and efficiently updates the real DOM by comparing changes, minimizing direct manipulations.',
            'points' => 5
        ],
        [
            'question_text' => 'Machine learning models always produce 100% accurate results.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'False',
            'explanation' => 'No machine learning model is 100% accurate. All models have some degree of error and uncertainty.',
            'points' => 5
        ],
        [
            'question_text' => 'TCP guarantees delivery of data packets in the correct order.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'True',
            'explanation' => 'TCP provides reliable, ordered delivery by using sequence numbers, acknowledgments, and retransmission of lost packets.',
            'points' => 5
        ],
        [
            'question_text' => 'CSS Grid and Flexbox serve the same purpose and are interchangeable.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'False',
            'explanation' => 'CSS Grid is best for 2D layouts (rows and columns), while Flexbox excels at 1D layouts (single row or column). They complement each other.',
            'points' => 5
        ],
        [
            'question_text' => 'An API gateway can handle rate limiting and authentication.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'True',
            'explanation' => 'API gateways commonly provide cross-cutting concerns like rate limiting, authentication, logging, and request routing.',
            'points' => 5
        ],
        [
            'question_text' => 'NoSQL databases are always faster than SQL databases.',
            'question_type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'False',
            'explanation' => 'Performance depends on the use case. NoSQL excels at specific scenarios like large-scale unstructured data, while SQL is optimized for complex queries and ACID transactions.',
            'points' => 5
        ]
    ];

    // Filter by difficulty (adjust point values and question complexity)
    if ($difficulty === 'beginner') {
        $filtered = array_filter($samplePool, function($q) {
            return $q['points'] <= 5;
        });
    } elseif ($difficulty === 'advanced') {
        $filtered = array_filter($samplePool, function($q) {
            return $q['points'] >= 10;
        });
    } else {
        $filtered = $samplePool;
    }

    $filtered = array_values($filtered);

    // Shuffle and select
    shuffle($filtered);

    $selected = [];
    $usedTexts = [];

    foreach ($filtered as $q) {
        if (count($selected) >= $count) break;
        $key = md5($q['question_text']);
        if (!isset($usedTexts[$key])) {
            $usedTexts[$key] = true;
            $q['question_text'] = str_replace(['course', 'topic'], [$topic ?: 'this subject', $topic ?: 'this subject'], $q['question_text']);
            $selected[] = $q;
        }
    }

    // If we have fewer questions than requested, supplement
    $remaining = $count - count($selected);
    if ($remaining > 0) {
        $backup = $samplePool;
        shuffle($backup);
        foreach ($backup as $q) {
            if ($remaining <= 0) break;
            $key = md5($q['question_text']);
            if (!isset($usedTexts[$key])) {
                $usedTexts[$key] = true;
                $q['question_text'] = str_replace(['course', 'topic'], [$topic ?: 'this subject', $topic ?: 'this subject'], $q['question_text']);
                $selected[] = $q;
                $remaining--;
            }
        }
    }

    return $selected;
}
