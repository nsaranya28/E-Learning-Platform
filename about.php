<?php $pageTitle = 'About Us'; require_once 'includes/functions.php'; include 'includes/header.php'; ?>

<section class="section-padding" style="padding-top: 3rem;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h6 class="text-primary fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>About SmartLearn</h6>
                <h2 class="section-title">Empowering Learners Worldwide with AI-Powered Education</h2>
                <p class="text-muted mb-4">SmartLearn is an innovative e-learning platform that combines expert-crafted courses with cutting-edge artificial intelligence to deliver a personalized, effective, and engaging learning experience.</p>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span>10,000+ Active Students</span></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span>500+ Expert-Led Courses</span></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span>AI-Powered Learning</span></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span>Industry Certificates</span></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span>24/7 AI Support</span></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span>Personalized Study Plans</span></div>
                    </div>
                </div>
                <a href="courses.php" class="btn btn-primary btn-lg"><i class="fas fa-graduation-cap me-2"></i>Explore Courses</a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/5968/5968885.png" alt="About SmartLearn" style="max-width: 70%;">
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-white">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Mission</h2>
            <p class="section-subtitle">To make quality education accessible, personalized, and effective for everyone using the power of AI.</p>
        </div>
        <div class="row g-4 mt-3">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-eye"></i></div>
                    <h5>Our Vision</h5>
                    <p>A world where anyone can learn anything, anytime, with personalized AI guidance that adapts to their unique learning style.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-bullseye"></i></div>
                    <h5>Our Mission</h5>
                    <p>To democratize education by providing affordable, high-quality courses enhanced with AI technology for optimal learning outcomes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-heart"></i></div>
                    <h5>Our Values</h5>
                    <p>Innovation, accessibility, quality, and community. We believe in continuous improvement and putting learners first.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Meet Our Team</h2>
            <p class="section-subtitle">Passionate educators, technologists, and innovators dedicated to transforming education.</p>
        </div>
        <div class="row g-4">
            <?php
            $team = [
                ['name' => 'Dr. Alex Morgan', 'role' => 'CEO & Founder', 'bio' => 'Education technology visionary with 15+ years of experience'],
                ['name' => 'Prof. Lisa Wang', 'role' => 'Head of AI Research', 'bio' => 'AI researcher specializing in adaptive learning systems'],
                ['name' => 'James Wilson', 'role' => 'CTO', 'bio' => 'Full-stack architect and cloud infrastructure expert'],
                ['name' => 'Dr. Sarah Patel', 'role' => 'Head of Curriculum', 'bio' => 'Curriculum designer with PhD in Educational Technology'],
            ];
            foreach ($team as $member): ?>
            <div class="col-lg-3 col-md-6">
                <div class="card instructor-card">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($member['name']); ?>&background=2563eb&color=fff&size=100" alt="">
                    <h5><?php echo $member['name']; ?></h5>
                    <p class="text-primary fw-semibold"><?php echo $member['role']; ?></p>
                    <small class="text-muted"><?php echo $member['bio']; ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
