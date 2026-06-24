<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$sidebarLinks = [
    'dashboard.php' => ['label' => 'Dashboard', 'icon' => 'fa-tachometer-alt'],
    'manage-courses.php' => ['label' => 'My Courses', 'icon' => 'fa-book-open'],
    'create-course.php' => ['label' => 'Create Course', 'icon' => 'fa-plus-circle'],
    'quizzes.php' => ['label' => 'Quizzes', 'icon' => 'fa-question-circle'],
    'students.php' => ['label' => 'Students', 'icon' => 'fa-users'],
    'analytics.php' => ['label' => 'Analytics', 'icon' => 'fa-chart-bar'],
    'settings.php' => ['label' => 'Settings', 'icon' => 'fa-cog'],
];
?>
<aside class="dashboard-sidebar">
    <div class="user-info">
        <div class="d-flex align-items-center gap-3">
            <img src="https://placehold.co/100x100/2563eb/ffffff?text=<?php echo urlencode(substr($_SESSION['instructor_name'] ?? 'I', 0, 1)); ?>" alt="Avatar">
            <div>
                <h6><?php echo htmlspecialchars($_SESSION['instructor_name'] ?? 'Instructor'); ?></h6>
                <small><i class="fas fa-chalkboard-teacher me-1"></i>Instructor</small>
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
