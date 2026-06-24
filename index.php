<?php
$pageTitle = 'Home';
require_once 'includes/functions.php';
$featuredCourses = getCourses(['limit' => 6]);
$categories = getCategories();
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Unlock Your Potential with <span>AI-Powered</span> Learning</h1>
                <p class="hero-text">SmartLearn combines expert-led courses with artificial intelligence to create a personalized learning experience that adapts to your goals and pace.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="courses.php" class="btn btn-primary btn-lg"><i class="fas fa-graduation-cap me-2"></i>Explore Courses</a>
                    <a href="register.php" class="btn btn-outline-primary btn-lg"><i class="fas fa-user-plus me-2"></i>Get Started Free</a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat"><h3>10K+</h3><p>Students</p></div>
                    <div class="hero-stat"><h3>500+</h3><p>Courses</p></div>
                    <div class="hero-stat"><h3>200+</h3><p>Instructors</p></div>
                    <div class="hero-stat"><h3>95%</h3><p>Satisfaction</p></div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/5968/5968885.png" alt="Online Learning" class="hero-image" style="max-width: 80%;">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Why Choose SmartLearn?</h2>
            <p class="section-subtitle">Experience a revolutionary way of learning with AI-powered features designed to help you succeed.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-robot"></i></div>
                    <h5>AI Learning Assistant</h5>
                    <p>24/7 AI chatbot support for instant answers, explanations, and personalized guidance throughout your learning journey.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-lightbulb"></i></div>
                    <h5>Smart Recommendations</h5>
                    <p>AI analyzes your interests and goals to recommend the perfect courses and learning paths tailored just for you.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-tasks"></i></div>
                    <h5>AI Study Planner</h5>
                    <p>Get a personalized study schedule optimized for your learning style, pace, and availability.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-certificate"></i></div>
                    <h5>Verified Certificates</h5>
                    <p>Earn industry-recognized certificates upon course completion to boost your career prospects.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Featured Courses</h2>
            <p class="section-subtitle">Start learning from our most popular courses handpicked for you.</p>
        </div>
        <div class="row g-4">
            <?php if (!empty($featuredCourses)): ?>
                <?php foreach ($featuredCourses as $course): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card course-card h-100">
                        <div style="position: relative;">
                            <img src="https://picsum.photos/seed/<?php echo $course['id']; ?>/400/250" class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>">
                            <span class="course-level"><?php echo ucfirst($course['level']); ?></span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge badge-primary"><?php echo htmlspecialchars($course['category_name'] ?? 'General'); ?></span>
                                <?php if ($course['featured']): ?><span class="badge bg-warning text-dark">Featured</span><?php endif; ?>
                            </div>
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($course['short_description'] ?? $course['description'], 0, 100)); ?>...</p>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($course['instructor_name'] ?? 'Instructor'); ?>&background=2563eb&color=fff&size=30" class="instructor-img" alt="">
                                <small class="text-muted"><?php echo htmlspecialchars($course['instructor_name'] ?? 'Expert Instructor'); ?></small>
                            </div>
                            <?php echo getRatingStars($course['rating']); ?>
                            <div class="course-meta">
                                <span><i class="fas fa-users me-1"></i><?php echo $course['total_students']; ?></span>
                                <span><i class="fas fa-book me-1"></i><?php echo $course['total_lessons']; ?> lessons</span>
                                <span class="ms-auto course-price">
                                    <?php if ($course['discount_price']): ?>
                                        <span class="original"><?php echo formatPrice($course['price']); ?></span>
                                        <?php echo formatPrice($course['discount_price']); ?>
                                    <?php else: ?>
                                        <?php echo formatPrice($course['price']); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        <a href="course-details.php?id=<?php echo $course['id']; ?>" class="stretched-link"></a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card course-card h-100">
                        <div style="position: relative;">
                            <img src="https://picsum.photos/seed/<?php echo $i; ?>/400/250" class="card-img-top" alt="Course">
                            <span class="course-level">Beginner</span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge badge-primary">Web Development</span>
                            </div>
                            <h5 class="card-title">Complete Web Development Bootcamp 2024</h5>
                            <p class="card-text">Learn HTML, CSS, JavaScript, React, Node.js and more. Build real-world projects...</p>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=2563eb&color=fff&size=30" class="instructor-img" alt="">
                                <small class="text-muted">John Doe</small>
                            </div>
                            <?php echo getRatingStars(4.5); ?>
                            <div class="course-meta">
                                <span><i class="fas fa-users me-1"></i>1,234</span>
                                <span><i class="fas fa-book me-1"></i>24 lessons</span>
                                <span class="ms-auto course-price">$49.99</span>
                            </div>
                        </div>
                        <a href="course-details.php?id=<?php echo $i; ?>" class="stretched-link"></a>
                    </div>
                </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="courses.php" class="btn btn-primary btn-lg">View All Courses <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Browse by Category</h2>
            <p class="section-subtitle">Explore courses across various categories taught by industry experts.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $cat): ?>
            <div class="col-lg-3 col-md-6">
                <a href="courses.php?category=<?php echo $cat['slug']; ?>" class="text-decoration-none">
                    <div class="feature-card">
                        <div class="icon-box"><i class="<?php echo htmlspecialchars($cat['icon'] ?? 'fas fa-folder'); ?>"></i></div>
                        <h5><?php echo htmlspecialchars($cat['name']); ?></h5>
                        <p><?php echo htmlspecialchars($cat['description'] ?? ''); ?></p>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">What Our Students Say</h2>
            <p class="section-subtitle">Hear from thousands of learners who transformed their careers with SmartLearn.</p>
        </div>
        <div class="row g-4">
            <?php
            $testimonials = [
                ['name' => 'Sarah Johnson', 'role' => 'Full-Stack Developer', 'text' => 'SmartLearn completely changed my career. The AI study planner helped me stay on track, and within 6 months I landed my dream job as a developer.', 'rating' => 5],
                ['name' => 'Mike Chen', 'role' => 'Data Analyst', 'text' => 'The AI chatbot is incredible! Whenever I got stuck, it provided instant explanations. The personalized course recommendations were spot on.', 'rating' => 5],
                ['name' => 'Emily Rodriguez', 'role' => 'UX Designer', 'text' => 'Best investment in my education. The courses are well-structured, instructors are supportive, and the certificates are recognized by employers.', 'rating' => 5],
            ];
            foreach ($testimonials as $t): ?>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="mb-2"><?php echo getRatingStars($t['rating']); ?></div>
                    <p>"<?php echo $t['text']; ?>"</p>
                    <div class="user">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($t['name']); ?>&background=2563eb&color=fff&size=45" alt="">
                        <div>
                            <h6><?php echo $t['name']; ?></h6>
                            <small><?php echo $t['role']; ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
    <div class="container text-center">
        <h2 style="font-size: 2.2rem; font-weight: 800; color: white; margin-bottom: 1rem;">Ready to Start Learning?</h2>
        <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem; max-width: 600px; margin: 0 auto 2rem;">Join thousands of learners and start your journey towards a brighter future with SmartLearn.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="register.php" class="btn btn-light btn-lg" style="color: var(--primary); font-weight: 700;"><i class="fas fa-user-plus me-2"></i>Create Free Account</a>
            <a href="courses.php" class="btn btn-outline-light btn-lg"><i class="fas fa-play me-2"></i>Browse Courses</a>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>
