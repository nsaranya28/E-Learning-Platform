<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$sidebarLinks = [
    'dashboard.php' => ['label' => 'Dashboard', 'icon' => 'fa-tachometer-alt'],
    'students.php' => ['label' => 'Students', 'icon' => 'fa-users'],
    'instructors.php' => ['label' => 'Instructors', 'icon' => 'fa-chalkboard-teacher'],
    'courses.php' => ['label' => 'Courses', 'icon' => 'fa-book-open'],
    'categories.php' => ['label' => 'Categories', 'icon' => 'fa-tags'],
    'certificates.php' => ['label' => 'Certificates', 'icon' => 'fa-award'],
    'reports.php' => ['label' => 'Reports', 'icon' => 'fa-chart-bar'],
    'feedback.php' => ['label' => 'Feedback', 'icon' => 'fa-comment-dots'],
    'settings.php' => ['label' => 'Settings', 'icon' => 'fa-cog'],
];
?>
<aside class="dashboard-sidebar">
    <div class="user-info">
        <div class="d-flex align-items-center gap-3">
            <img src="https://placehold.co/100x100/2563eb/ffffff?text=<?php echo urlencode(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?>" alt="Avatar">
            <div>
                <h6><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></h6>
                <small><i class="fas fa-shield-alt me-1"></i>Administrator</small>
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
    <div class="p-3" style="border-top: 1px solid var(--gray-100); margin-top: auto;">
        <a href="../api/auth/logout.php" class="nav-link" style="color: var(--danger) !important;">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
