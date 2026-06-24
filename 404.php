<?php $pageTitle = 'Page Not Found'; require_once 'includes/functions.php'; include 'includes/header.php'; ?>

<section class="section-padding" style="min-height: 70vh; display: flex; align-items: center;">
    <div class="container text-center">
        <div style="font-size: 8rem; font-weight: 900; color: var(--gray-200); line-height: 1;">404</div>
        <h2 class="fw-bold mb-3" style="color: var(--secondary);">Page Not Found</h2>
        <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto 2rem;">The page you're looking for doesn't exist or has been moved. Let's get you back on track!</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="index.php" class="btn btn-primary btn-lg"><i class="fas fa-home me-2"></i>Go Home</a>
            <a href="courses.php" class="btn btn-outline-primary btn-lg"><i class="fas fa-book me-2"></i>Browse Courses</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
