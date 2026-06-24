<?php
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
    }

    $full_name       = sanitize($_POST['full_name'] ?? '');
    $username        = sanitize($_POST['username'] ?? '');
    $email           = sanitize($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone           = sanitize($_POST['phone'] ?? '');
    $role            = sanitize($_POST['role'] ?? '');
    $qualification   = sanitize($_POST['qualification'] ?? '');
    $expertise       = sanitize($_POST['expertise'] ?? '');

    if (empty($full_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        jsonResponse(['success' => false, 'message' => 'Please fill in all required fields.'], 422);
    }

    if (!in_array($role, ['student', 'instructor'])) {
        jsonResponse(['success' => false, 'message' => 'Invalid role selected.'], 422);
    }

    if (!validateEmail($email)) {
        jsonResponse(['success' => false, 'message' => 'Invalid email address.'], 422);
    }

    if ($password !== $confirm_password) {
        jsonResponse(['success' => false, 'message' => 'Passwords do not match.'], 422);
    }

    if (strlen($password) < 6) {
        jsonResponse(['success' => false, 'message' => 'Password must be at least 6 characters.'], 422);
    }

    if ($role === 'instructor' && (empty($qualification) || empty($expertise))) {
        jsonResponse(['success' => false, 'message' => 'Please provide your qualification and expertise.'], 422);
    }

    $db = getDB();

    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ? UNION SELECT id FROM instructors WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email, $username, $email]);
    if ($stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'Username or email already exists.'], 409);
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($role === 'instructor') {
        $stmt = $db->prepare("INSERT INTO instructors (username, email, password, full_name, phone, qualification, expertise, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
        $stmt->execute([$username, $email, $hashedPassword, $full_name, $phone, $qualification, $expertise]);
        jsonResponse(['success' => true, 'message' => 'Registration successful! Your account is pending admin approval.', 'redirect' => 'instructor-login.php']);
    } else {
        $stmt = $db->prepare("INSERT INTO users (username, email, password, full_name, phone, status, created_at) VALUES (?, ?, ?, ?, ?, 'active', NOW())");
        $stmt->execute([$username, $email, $hashedPassword, $full_name, $phone]);

        $userId = $db->lastInsertId();
        $_SESSION['user_id']    = $userId;
        $_SESSION['user_name']  = $full_name;
        $_SESSION['user_email'] = $email;

        jsonResponse(['success' => true, 'message' => 'Registration successful! Welcome to SmartLearn.', 'redirect' => 'index.php']);
    }

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}
