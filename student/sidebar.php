<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$user = isset($user) ? $user : getUser($_SESSION['user_id'] ?? 0);
$sidebarLinks = [
    'dashboard.php' => ['label' => 'Dashboard', 'icon' => 'fa-tachometer-alt'],
    'my-courses.php' => ['label' => 'My Courses', 'icon' => 'fa-book-open'],
    'progress.php' => ['label' => 'Progress', 'icon' => 'fa-chart-line'],
    'quiz.php' => ['label' => 'Quiz History', 'icon' => 'fa-question-circle'],
    'certificate.php' => ['label' => 'Certificates', 'icon' => 'fa-award'],
    'wishlist.php' => ['label' => 'Wishlist', 'icon' => 'fa-heart'],
    'forum.php' => ['label' => 'Discussion Forum', 'icon' => 'fa-comments'],
    'notes.php' => ['label' => 'Notes', 'icon' => 'fa-sticky-note'],
    'settings.php' => ['label' => 'Settings', 'icon' => 'fa-cog'],
];
?>
<aside class="dashboard-sidebar">
    <div class="user-info">
        <div class="d-flex align-items-center gap-3">
            <img src="https://placehold.co/100x100/2563eb/ffffff?text=<?php echo urlencode(substr($user['full_name'] ?? $_SESSION['user_name'] ?? 'S', 0, 1)); ?>" alt="Avatar">
            <div>
                <h6><?php echo htmlspecialchars($user['full_name'] ?? $_SESSION['user_name'] ?? 'Student'); ?></h6>
                <small><i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($user['email'] ?? $_SESSION['user_email'] ?? ''); ?></small>
            </div>
        </div>
    </div>
    <ul class="nav flex-column">
        <?php foreach ($sidebarLinks as $file => $link): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $currentPage === $file ? 'active' : ''; ?>" href="<?php echo $file; ?>">
                <i class="fas <?php echo $link['icon']; ?>"></i>
                <span><?php echo $link['label']; ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <div class="mt-auto p-3" style="border-top: 1px solid var(--gray-100); margin-top: auto;">
        <a href="../api/auth/logout.php" class="nav-link" style="color: var(--danger) !important;">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
