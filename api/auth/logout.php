<?php
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

session_destroy();
jsonResponse(['success' => true, 'message' => 'Logged out successfully.', 'redirect' => 'index.php']);
