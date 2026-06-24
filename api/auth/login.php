<?php
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['success' => false, 'message' => 'Invalid request method.'], 405);
    }

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = trim($_POST['role'] ?? '');

    if (empty($email) || empty($password) || !in_array($role, ['student', 'instructor'])) {
        jsonResponse(['success' => false, 'message' => 'Please provide email, password, and valid role.'], 422);
    }

    $db = getDB();
    $table = $role === 'instructor' ? 'instructors' : 'users';
    $idCol = $role === 'instructor' ? 'instructor_id' : 'user_id';

    $stmt = $db->prepare("SELECT * FROM $table WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        jsonResponse(['success' => false, 'message' => 'Invalid email or password.'], 401);
    }

    if ($role === 'instructor' && $user['status'] !== 'approved') {
        jsonResponse(['success' => false, 'message' => 'Your instructor account is not yet approved. Please wait for admin approval.'], 403);
    }

    if ($role === 'student' && $user['status'] !== 'active') {
        jsonResponse(['success' => false, 'message' => 'Your account is inactive. Please contact support.'], 403);
    }

    $_SESSION[$idCol]  = $user['id'];
    $_SESSION['user_name']  = $user['full_name'];
    $_SESSION['user_email'] = $user['email'];
    if ($role === 'instructor') {
        $_SESSION['instructor_id'] = $user['id'];
    } else {
        $_SESSION['user_id'] = $user['id'];
    }

    $redirect = $role === 'instructor' ? 'instructor/dashboard.php' : 'index.php';

    jsonResponse(['success' => true, 'message' => 'Login successful.', 'redirect' => $redirect]);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error. Please try again later.'], 500);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
}
