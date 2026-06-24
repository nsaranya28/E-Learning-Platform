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
    $currentSkills = $rawInput['current_skills'] ?? $_POST['current_skills'] ?? [];
    $interests = $rawInput['interests'] ?? $_POST['interests'] ?? [];
    $educationLevel = sanitize($rawInput['education_level'] ?? $_POST['education_level'] ?? 'bachelor');
    $experienceYears = intval($rawInput['experience_years'] ?? $_POST['experience_years'] ?? 0);
    $goals = sanitize($rawInput['goals'] ?? $_POST['goals'] ?? '');

    if (is_string($currentSkills)) {
        $currentSkills = json_decode($currentSkills, true) ?? [];
    }
    if (is_string($interests)) {
        $interests = json_decode($interests, true) ?? [];
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
                    ['role' => 'system', 'content' => 'You are a career guidance advisor for SmartLearn. Recommend career paths with detailed information.'],
                    ['role' => 'user', 'content' => json_encode([
                        'current_skills' => $currentSkills, 'interests' => $interests,
                        'education_level' => $educationLevel, 'experience_years' => $experienceYears, 'goals' => $goals
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
        $paths = json_decode($data['choices'][0]['message']['content'] ?? '{}', true);
        if (!empty($paths['career_paths'])) {
            jsonResponse(['success' => true, 'career_paths' => $paths['career_paths']]);
        }
        */
    }

    $db = getDB();

    $careerPaths = [
        [
            'title' => 'Full-Stack Developer',
            'description' => 'Build and maintain both front-end and back-end components of web applications. Full-stack developers are versatile engineers who can handle the entire development lifecycle, from database design to user interface implementation.',
            'demand_level' => 'Very High',
            'avg_salary' => '$95,000 - $145,000',
            'required_skills' => ['HTML/CSS', 'JavaScript', 'React or Vue.js', 'Node.js or PHP', 'SQL Databases', 'Git', 'REST APIs'],
            'growth_potential' => 'Excellent — 25% projected growth over next 5 years as digital transformation accelerates across industries.',
            'education_path' => 'Bachelor\'s degree in CS or equivalent bootcamp; strong portfolio essential',
            'recommended_courses' => []
        ],
        [
            'title' => 'Data Scientist',
            'description' => 'Analyze complex datasets to drive business decisions. Data scientists use statistical analysis, machine learning, and data visualization to uncover insights and solve real-world problems.',
            'demand_level' => 'Very High',
            'avg_salary' => '$110,000 - $170,000',
            'required_skills' => ['Python', 'SQL', 'Statistics', 'Machine Learning', 'Data Visualization', 'Deep Learning', 'Big Data Tools'],
            'growth_potential' => 'Outstanding — 35% projected growth. Data-driven decision making continues to expand across all sectors.',
            'education_path' => 'Master\'s or PhD preferred in STEM field; certifications in ML/AI highly valued',
            'recommended_courses' => []
        ],
        [
            'title' => 'Cloud Architect',
            'description' => 'Design and oversee cloud computing strategies for organizations. Cloud architects plan infrastructure, ensure security compliance, optimize costs, and lead cloud migration projects.',
            'demand_level' => 'High',
            'avg_salary' => '$130,000 - $190,000',
            'required_skills' => ['AWS/Azure/GCP', 'Networking', 'Security', 'DevOps Tools', 'Infrastructure as Code', 'Containerization', 'System Design'],
            'growth_potential' => 'Strong — 20% growth as enterprises continue migrating to cloud infrastructure and multi-cloud strategies.',
            'education_path' => 'Bachelor\'s in CS/IT with cloud certifications (AWS Solutions Architect, Azure)',
            'recommended_courses' => []
        ],
        [
            'title' => 'DevOps Engineer',
            'description' => 'Bridge development and operations by automating workflows, managing CI/CD pipelines, and ensuring reliable software delivery. DevOps engineers optimize the entire software development lifecycle.',
            'demand_level' => 'High',
            'avg_salary' => '$100,000 - $160,000',
            'required_skills' => ['Linux', 'Docker', 'Kubernetes', 'CI/CD Tools', 'Scripting (Python/Bash)', 'Cloud Platforms', 'Monitoring Tools'],
            'growth_potential' => 'Very Strong — 30% growth as organizations adopt agile and DevOps practices for faster delivery.',
            'education_path' => 'Bachelor\'s in CS/IT with DevOps certifications (AWS DevOps, CKAD)',
            'recommended_courses' => []
        ],
        [
            'title' => 'AI/ML Engineer',
            'description' => 'Develop artificial intelligence and machine learning systems. AI/ML engineers build and deploy models for natural language processing, computer vision, recommendation systems, and predictive analytics.',
            'demand_level' => 'Very High',
            'avg_salary' => '$120,000 - $200,000',
            'required_skills' => ['Python', 'TensorFlow/PyTorch', 'Machine Learning Algorithms', 'Deep Learning', 'NLP', 'Computer Vision', 'MLOps'],
            'growth_potential' => 'Exceptional — 40% projected growth. AI adoption is accelerating across healthcare, finance, autonomous systems, and more.',
            'education_path' => 'Master\'s or PhD in CS/AI/ML; strong mathematics foundation required',
            'recommended_courses' => []
        ]
    ];

    // Map courses from database to career paths
    $allCategories = $db->query("SELECT id, name FROM categories WHERE status = 'active'")->fetchAll();
    $catMap = [];
    foreach ($allCategories as $cat) {
        $catMap[$cat['id']] = $cat['name'];
    }

    $stmt = $db->query("SELECT c.id, c.title, c.short_description, c.level, cat.name as category_name
                        FROM courses c
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        WHERE c.status = 'published'
                        ORDER BY c.featured DESC, c.rating DESC
                        LIMIT 30");
    $dbCourses = $stmt->fetchAll();

    $pathCourseKeywords = [
        'Full-Stack Developer' => [1, 'web', 'fullstack', 'javascript', 'react', 'php', 'node'],
        'Data Scientist' => [2, 'data', 'python', 'machine learning', 'analytics', 'statistics'],
        'Cloud Architect' => [4, 'cloud', 'aws', 'azure', 'devops', 'architecture'],
        'DevOps Engineer' => [4, 'devops', 'docker', 'kubernetes', 'ci/cd', 'cloud', 'linux'],
        'AI/ML Engineer' => [2, 'ai', 'machine learning', 'deep learning', 'data science', 'python']
    ];

    foreach ($careerPaths as &$path) {
        $keywords = $pathCourseKeywords[$path['title']];
        $targetCatId = $keywords[0];
        $searchTerms = array_slice($keywords, 1);

        $matched = [];
        foreach ($dbCourses as $course) {
            $titleLower = strtolower($course['title']);
            $descLower = strtolower($course['short_description'] ?? '');
            $catMatch = (int)$course['category_id'] === $targetCatId;

            foreach ($searchTerms as $term) {
                if (strpos($titleLower, $term) !== false || strpos($descLower, $term) !== false || $catMatch) {
                    $matched[] = [
                        'id' => (int)$course['id'],
                        'title' => $course['title'],
                        'level' => $course['level'],
                        'category' => $course['category_name']
                    ];
                    break;
                }
            }
        }

        $path['recommended_courses'] = array_slice($matched, 0, 5);
    }
    unset($path);

    // Score and rank career paths against user profile
    if (!empty($currentSkills) || !empty($interests)) {
        $skillLower = array_map('strtolower', $currentSkills);
        $interestLower = array_map('strtolower', $interests);
        $allUserTerms = array_merge($skillLower, $interestLower);

        $scores = [];
        foreach ($careerPaths as $i => $path) {
            $score = 0;
            $pathSkills = array_map('strtolower', $path['required_skills']);
            foreach ($allUserTerms as $term) {
                foreach ($pathSkills as $skill) {
                    if (strpos($skill, $term) !== false || strpos($term, $skill) !== false) {
                        $score += 10;
                    }
                }
            }
            // Experience bonus
            if ($experienceYears >= 3 && in_array($path['title'], ['Cloud Architect', 'DevOps Engineer'])) {
                $score += 15;
            }
            if ($experienceYears <= 1 && in_array($path['title'], ['Full-Stack Developer', 'Data Scientist'])) {
                $score += 10;
            }
            $scores[$i] = $score;
        }

        arsort($scores);
        $sorted = [];
        foreach ($scores as $i => $score) {
            $sorted[] = $careerPaths[$i];
        }
        $careerPaths = $sorted;
    }

    jsonResponse(['success' => true, 'career_paths' => $careerPaths]);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}
