<?php
$pageTitle = 'Login';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-header">
                <h3><i class="fas fa-graduation-cap me-2" style="color: var(--primary);"></i>Welcome Back</h3>
                <p>Sign in to continue your learning journey</p>
            </div>

            <?php echo flashMessage(); ?>

            <form action="../api/auth/login.php" method="POST" novalidate>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <a href="forgot-password.php" class="text-decoration-none" style="font-size: 0.9rem;">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
            </form>

            <div class="divider"><span>or continue with</span></div>

            <div class="d-flex gap-2 mb-4">
                <button class="btn btn-outline-secondary w-100" onclick="alert('Google login coming soon')">
                    <i class="fab fa-google me-2"></i>Google
                </button>
                <button class="btn btn-outline-secondary w-100" onclick="alert('Facebook login coming soon')">
                    <i class="fab fa-facebook-f me-2"></i>Facebook
                </button>
                <button class="btn btn-outline-secondary w-100" onclick="alert('GitHub login coming soon')">
                    <i class="fab fa-github me-2"></i>GitHub
                </button>
            </div>

            <p class="text-center mb-2" style="font-size: 0.9rem;">
                Don't have an account? <a href="register.php">Register Here</a>
            </p>
            <p class="text-center mb-0" style="font-size: 0.9rem;">
                Are you an instructor? <a href="instructor-login.php">Instructor Login</a>
            </p>
        </div>
    </div>
</section>

<script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
