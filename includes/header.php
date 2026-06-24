<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - SmartLearn' : 'SmartLearn - AI-Powered E-Learning Platform'; ?></title>
    <meta name="description" content="SmartLearn - AI-Powered E-Learning Platform. Learn from expert instructors with personalized AI learning assistance.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <?php if (isset($extraStyles)) echo $extraStyles; ?>
</head>
<body>
<?php if (!isset($hideNavbar) || !$hideNavbar) include 'navbar.php'; ?>
<div id="toastContainer" class="toast-container"></div>
