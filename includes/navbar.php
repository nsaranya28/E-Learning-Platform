<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Smart<span>Learn</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>" href="courses.php">Courses</a></li>
                <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'faq.php' ? 'active' : ''; ?>" href="faq.php">FAQ</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../student/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="../student/my-courses.php"><i class="fas fa-book me-2"></i>My Courses</a></li>
                            <li><a class="dropdown-item" href="../student/progress.php"><i class="fas fa-chart-line me-2"></i>Progress</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../api/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php elseif (isset($_SESSION['instructor_id'])): ?>
                    <a href="../instructor/dashboard.php" class="btn btn-outline-primary"><i class="fas fa-chalkboard-teacher me-1"></i>Instructor</a>
                <?php elseif (isset($_SESSION['admin_id'])): ?>
                    <a href="../admin/dashboard.php" class="btn btn-outline-primary"><i class="fas fa-shield-alt me-1"></i>Admin</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-primary"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                    <a href="register.php" class="btn btn-primary"><i class="fas fa-user-plus me-1"></i>Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
