<?php
$pageTitle = 'Contact Us';
require_once 'includes/functions.php';

$submitted = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $type = sanitize($_POST['type'] ?? 'general');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!validateEmail($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, subject, type, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $type, $message]);
            $submitted = true;
        } catch (Exception $e) {
            $error = 'Failed to send your message. Please try again later.';
        }
    }
}

include 'includes/header.php';
?>

<section class="hero-section" style="padding: 3rem 0 2rem;">
    <div class="container">
        <div class="section-header mb-0">
            <h1 class="section-title">Get In Touch</h1>
            <p class="section-subtitle">Have a question, feedback, or need help? We'd love to hear from you. Our team typically responds within 24 hours.</p>
        </div>
    </div>
</section>

<section class="section-padding pt-0">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card h-100">
                    <div class="icon-box"><i class="fas fa-map-marker-alt"></i></div>
                    <h5>Our Address</h5>
                    <p class="mb-0">123 Learning Street<br>Education City, EC 10001</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card h-100">
                    <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                    <h5>Phone</h5>
                    <p class="mb-0">+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card h-100">
                    <div class="icon-box"><i class="fas fa-envelope"></i></div>
                    <h5>Email</h5>
                    <p class="mb-0">support@smartlearn.com<br>info@smartlearn.com</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card h-100">
                    <div class="icon-box"><i class="fas fa-clock"></i></div>
                    <h5>Working Hours</h5>
                    <p class="mb-0">Mon - Fri: 9:00 AM - 6:00 PM<br>Sat: 10:00 AM - 2:00 PM</p>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-7">
                <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm">
                    <h3 class="fw-bold mb-1">Send Us a Message</h3>
                    <p class="text-muted mb-4">Fill out the form below and we'll get back to you as soon as possible.</p>

                    <?php if ($submitted): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>Thank you for contacting us! We'll respond to your inquiry within 24 hours.
                    </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="How can we help?">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select">
                                    <option value="general">General Inquiry</option>
                                    <option value="bug">Bug Report</option>
                                    <option value="feature">Feature Request</option>
                                    <option value="complaint">Complaint</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control" rows="6" placeholder="Write your message here..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-paper-plane me-2"></i>Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="bg-white rounded-3 shadow-sm overflow-hidden" style="height: 100%; min-height: 400px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.966309591941!2d-73.9854283!3d40.7488175!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1" width="100%" height="100%" style="border:0; min-height: 400px;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
