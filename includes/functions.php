<?php
session_start();

// Database connection
function getDB() {
    static $conn = null;
    if ($conn === null) {
        $config = require_once __DIR__ . '/../api/config/database.php';
        try {
            $conn = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $conn;
}

// Sanitize input
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Generate slug
function createSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if instructor is logged in
function isInstructor() {
    return isset($_SESSION['instructor_id']);
}

// Check if admin is logged in
function isAdmin() {
    return isset($_SESSION['admin_id']);
}

// Redirect with message
function redirect($url, $message = '', $type = 'info') {
    if ($message) {
        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
    }
    header("Location: $url");
    exit();
}

// Display flash message
function flashMessage() {
    if (isset($_SESSION['flash'])) {
        $msg = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return "<div class='alert alert-{$msg['type']} alert-dismissible fade show'>{$msg['message']}<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
    return '';
}

// Get user by ID
function getUser($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get course by ID
function getCourse($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT c.*, cat.name as category_name, i.full_name as instructor_name, i.avatar as instructor_avatar
                          FROM courses c
                          LEFT JOIN categories cat ON c.category_id = cat.id
                          LEFT JOIN instructors i ON c.instructor_id = i.id
                          WHERE c.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get courses with filters
function getCourses($filters = []) {
    $db = getDB();
    $sql = "SELECT c.*, cat.name as category_name, i.full_name as instructor_name
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN instructors i ON c.instructor_id = i.id
            WHERE c.status = 'published'";
    $params = [];

    if (!empty($filters['category'])) {
        $sql .= " AND cat.slug = ?";
        $params[] = $filters['category'];
    }
    if (!empty($filters['level'])) {
        $sql .= " AND c.level = ?";
        $params[] = $filters['level'];
    }
    if (!empty($filters['search'])) {
        $sql .= " AND (c.title LIKE ? OR c.short_description LIKE ?)";
        $params[] = "%{$filters['search']}%";
        $params[] = "%{$filters['search']}%";
    }
    if (!empty($filters['price'])) {
        if ($filters['price'] === 'free') $sql .= " AND c.price = 0";
        elseif ($filters['price'] === 'paid') $sql .= " AND c.price > 0";
    }

    $sql .= " ORDER BY c.featured DESC, c.created_at DESC";
    if (!empty($filters['limit'])) {
        $sql .= " LIMIT " . intval($filters['limit']);
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Get all categories
function getCategories() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name");
    return $stmt->fetchAll();
}

// Check if user is enrolled in course
function isEnrolled($userId, $courseId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$userId, $courseId]);
    return $stmt->fetch();
}

// Get enrollment progress
function getProgress($userId, $courseId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT progress, completed_lessons, total_lessons FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$userId, $courseId]);
    return $stmt->fetch();
}

// Format price
function formatPrice($price) {
    if ($price == 0) return 'Free';
    return '$' . number_format($price, 2);
}

// Time ago function
function timeAgo($timestamp) {
    $time = strtotime($timestamp);
    $diff = time() - $time;

    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    return date('M d, Y', $time);
}

// Generate certificate number
function generateCertNumber($userId, $courseId) {
    return 'CRT-' . strtoupper(substr(md5(uniqid()), 0, 10)) . '-' . $userId . '-' . $courseId;
}

// Log activity
function logActivity($type, $title, $message, $userId = null, $instructorId = null, $adminId = null, $link = null) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO notifications (user_id, instructor_id, admin_id, type, title, message, link) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $instructorId, $adminId, $type, $title, $message, $link]);
}

// Get rating stars HTML
function getRatingStars($rating) {
    $html = '<div class="rating-stars">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating)) {
            $html .= '<i class="fas fa-star"></i>';
        } elseif ($i - $rating <= 0.5) {
            $html .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $html .= '<i class="far fa-star"></i>';
        }
    }
    $html .= '</div>';
    return $html;
}

// Send JSON response
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate random password
function generatePassword($length = 12) {
    return bin2hex(random_bytes($length / 2));
}
