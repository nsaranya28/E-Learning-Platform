<?php
$pageTitle = 'Create Account';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<section class="auth-section">
    <div class="container">
        <div class="auth-card" style="max-width: 520px;">
            <div class="auth-header">
                <h3><i class="fas fa-user-graduate me-2" style="color: var(--primary);"></i>Create Account</h3>
                <p>Start your learning journey with SmartLearn</p>
            </div>

            <?php echo flashMessage(); ?>

            <form action="../api/auth/register.php" method="POST" novalidate>
                <input type="hidden" name="role" value="student">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="John Doe" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="text" class="form-control" id="username" name="username" placeholder="johndoe" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Min. 8 characters" required minlength="8">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="+1 (555) 123-4567">
                    </div>
                </div>
                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms" style="font-size: 0.9rem;">
                            I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </button>
            </form>

            <p class="text-center mb-2" style="font-size: 0.9rem;">
                Already have an account? <a href="login.php">Sign In</a>
            </p>
            <p class="text-center mb-0" style="font-size: 0.9rem;">
                Want to teach? <a href="instructor-register.php">Register as Instructor</a>
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
