<?php
$pageTitle = 'Course Details';
require_once 'includes/functions.php';

$courseId = intval($_GET['id'] ?? 0);
$course = getCourse($courseId);

if (!$course) {
    include 'includes/header.php';
    echo '<div class="container section-padding"><div class="empty-state"><i class="fas fa-exclamation-circle"></i><h5>Course Not Found</h5><p>The course you are looking for does not exist or has been removed.</p><a href="courses.php" class="btn btn-primary">Browse Courses</a></div></div>';
    include 'includes/footer.php';
    exit;
}

include 'includes/header.php';
?>

<section class="course-detail-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <span class="badge badge-primary"><?php echo htmlspecialchars($course['category_name'] ?? 'General'); ?></span>
                    <span class="badge bg-warning text-dark"><?php echo ucfirst($course['level']); ?></span>
                </div>
                <h1><?php echo htmlspecialchars($course['title']); ?></h1>
                <p style="color: rgba(255,255,255,0.8); font-size: 1.05rem;"><?php echo htmlspecialchars($course['short_description'] ?? $course['description']); ?></p>
                <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($course['instructor_name'] ?? 'Instructor'); ?>&background=3b82f6&color=fff&size=35" style="width: 35px; height: 35px; border-radius: 50%;" alt="">
                        <span><?php echo htmlspecialchars($course['instructor_name'] ?? 'Expert Instructor'); ?></span>
                    </div>
                    <?php echo getRatingStars($course['rating']); ?>
                    <span style="color: rgba(255,255,255,0.7);"><i class="fas fa-users me-1"></i><?php echo $course['total_students']; ?> students</span>
                </div>
                <div class="meta">
                    <span><i class="fas fa-clock"></i>Last updated <?php echo timeAgo($course['updated_at'] ?? $course['created_at']); ?></span>
                    <span><i class="fas fa-globe"></i><?php echo htmlspecialchars($course['language'] ?? 'English'); ?></span>
                    <span><i class="fas fa-book"></i><?php echo $course['total_lessons']; ?> lessons</span>
                    <span><i class="fas fa-hourglass-half"></i><?php echo htmlspecialchars($course['duration'] ?? 'Self-paced'); ?></span>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="course-sidebar-card">
                    <div class="position-relative">
                        <img src="https://picsum.photos/seed/<?php echo $course['id']; ?>-detail/600/340" class="w-100" style="display: block;" alt="<?php echo htmlspecialchars($course['title']); ?>">
                        <?php if (!empty($course['video_url'])): ?>
                        <a href="<?php echo htmlspecialchars($course['video_url']); ?>" class="btn btn-light position-absolute top-50 start-50 translate-middle rounded-circle" style="width: 60px; height: 60px; font-size: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.3);"><i class="fas fa-play ms-1" style="color: var(--primary);"></i></a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="price mb-3">
                            <?php if (!empty($course['discount_price'])): ?>
                                <span style="font-size: 1rem; color: var(--gray-400); text-decoration: line-through; font-weight: 500;"><?php echo formatPrice($course['price']); ?></span>
                                <?php echo formatPrice($course['discount_price']); ?>
                            <?php else: ?>
                                <?php echo formatPrice($course['price']); ?>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo isLoggedIn() ? 'student/enroll.php?id=' . $course['id'] : 'login.php'; ?>" class="btn btn-primary btn-lg w-100 mb-3"><i class="fas fa-shopping-cart me-2"></i>Enroll Now</a>
                        <div class="mb-3">
                            <h6 style="font-weight: 700; font-size: 0.9rem; color: var(--gray-600); margin-bottom: 0.8rem;">This course includes:</h6>
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: var(--gray-500);">
                                <li class="mb-2"><i class="fas fa-video me-2 text-primary"></i><?php echo $course['total_lessons']; ?> on-demand video lessons</li>
                                <li class="mb-2"><i class="fas fa-file-alt me-2 text-primary"></i>Downloadable resources & exercises</li>
                                <li class="mb-2"><i class="fas fa-code me-2 text-primary"></i>Hands-on coding projects</li>
                                <li class="mb-2"><i class="fas fa-certificate me-2 text-primary"></i>Certificate of completion</li>
                                <li class="mb-2"><i class="fas fa-robot me-2 text-primary"></i>AI learning assistant access</li>
                                <li><i class="fas fa-infinity me-2 text-primary"></i>Full lifetime access</li>
                            </ul>
                        </div>
                        <hr>
                        <div>
                            <h6 style="font-weight: 700; font-size: 0.9rem; color: var(--gray-600); margin-bottom: 0.8rem;">Share this course:</h6>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . '/course-details.php?id=' . $course['id']); ?>" target="_blank" class="btn btn-outline-primary btn-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out this course: ' . $course['title']); ?>&url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . '/course-details.php?id=' . $course['id']); ?>" target="_blank" class="btn btn-outline-primary btn-icon"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . '/course-details.php?id=' . $course['id']); ?>" target="_blank" class="btn btn-outline-primary btn-icon"><i class="fab fa-linkedin-in"></i></a>
                                <a href="mailto:?subject=<?php echo urlencode('Learn ' . $course['title']); ?>&body=<?php echo urlencode('I found this great course: ' . $course['title'] . ' - http://' . $_SERVER['HTTP_HOST'] . '/course-details.php?id=' . $course['id']); ?>" class="btn btn-outline-primary btn-icon"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <?php if (!empty($course['learning_objectives'])): ?>
                <div class="mb-5">
                    <h2 class="section-title" style="font-size: 1.5rem;">What You'll Learn</h2>
                    <div class="row g-3">
                        <?php
                        $objectives = is_string($course['learning_objectives']) ? json_decode($course['learning_objectives'], true) : $course['learning_objectives'];
                        if (!empty($objectives)):
                            foreach ($objectives as $obj):
                        ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fas fa-check-circle text-success mt-1"></i>
                                <span><?php echo htmlspecialchars($obj); ?></span>
                            </div>
                        </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mb-5">
                    <h2 class="section-title" style="font-size: 1.5rem;">Course Curriculum</h2>
                    <div class="accordion" id="curriculumAccordion">
                        <?php
                        $sections = is_string($course['curriculum']) ? json_decode($course['curriculum'], true) : ($course['curriculum'] ?? []);
                        if (!empty($sections)):
                            $lessonIdx = 0;
                            foreach ($sections as $secIdx => $section):
                        ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?php echo $secIdx > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#section<?php echo $secIdx; ?>">
                                    <?php echo htmlspecialchars($section['title'] ?? 'Section ' . ($secIdx + 1)); ?>
                                    <small class="text-muted ms-2">(<?php echo count($section['lessons'] ?? []); ?> lessons)</small>
                                </button>
                            </h2>
                            <div id="section<?php echo $secIdx; ?>" class="accordion-collapse collapse <?php echo $secIdx === 0 ? 'show' : ''; ?>" data-bs-parent="#curriculumAccordion">
                                <div class="accordion-body p-3">
                                    <?php foreach ($section['lessons'] ?? [] as $lesson): ?>
                                    <div class="curriculum-item">
                                        <div class="left">
                                            <i class="fas fa-play-circle"></i>
                                            <span><?php echo htmlspecialchars($lesson['title'] ?? 'Lesson'); ?></span>
                                        </div>
                                        <span class="duration"><i class="far fa-clock me-1"></i><?php echo htmlspecialchars($lesson['duration'] ?? '10:00'); ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-list" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                            <p class="mb-0">Curriculum details are being updated. Check back soon.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="section-title" style="font-size: 1.5rem;">Instructor</h2>
                    <div class="d-flex align-items-start gap-3 p-4 bg-white rounded-3 shadow-sm">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($course['instructor_name'] ?? 'Instructor'); ?>&background=2563eb&color=fff&size=80" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; flex-shrink: 0;" alt="">
                        <div>
                            <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($course['instructor_name'] ?? 'Expert Instructor'); ?></h5>
                            <p class="text-muted mb-2"><?php echo htmlspecialchars($course['instructor_title'] ?? 'Course Instructor at SmartLearn'); ?></p>
                            <p class="mb-0"><?php echo htmlspecialchars($course['instructor_bio'] ?? 'Experienced professional passionate about teaching and helping students achieve their learning goals.'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="section-title" style="font-size: 1.5rem;">Student Reviews</h2>
                    <?php
                    $reviews = [];
                    if (!empty($course['id'])) {
                        try {
                            $db = getDB();
                            $stmt = $db->prepare("SELECT r.*, u.full_name, u.avatar FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.course_id = ? AND r.status = 'approved' ORDER BY r.created_at DESC LIMIT 10");
                            $stmt->execute([$course['id']]);
                            $reviews = $stmt->fetchAll();
                        } catch (Exception $e) {}
                    }
                    if (!empty($reviews)):
                        foreach ($reviews as $review):
                    ?>
                    <div class="testimonial-card mb-3">
                        <div class="mb-2"><?php echo getRatingStars($review['rating']); ?></div>
                        <p>"<?php echo htmlspecialchars($review['comment']); ?>"</p>
                        <div class="user">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($review['full_name']); ?>&background=2563eb&color=fff&size=45" alt="">
                            <div>
                                <h6><?php echo htmlspecialchars($review['full_name']); ?></h6>
                                <small><?php echo timeAgo($review['created_at']); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; else: ?>
                    <div class="text-center py-4 bg-white rounded-3 shadow-sm">
                        <i class="far fa-comment-dots" style="font-size: 2.5rem; color: var(--gray-300); display: block; margin-bottom: 0.8rem;"></i>
                        <p class="text-muted mb-0">No reviews yet. Be the first to review this course!</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="mb-4">
                    <h2 class="section-title" style="font-size: 1.5rem;">Related Courses</h2>
                    <?php
                    $related = [];
                    if (!empty($course['category_id'])) {
                        try {
                            $db = getDB();
                            $stmt = $db->prepare("SELECT c.*, cat.name as category_name, i.full_name as instructor_name FROM courses c LEFT JOIN categories cat ON c.category_id = cat.id LEFT JOIN instructors i ON c.instructor_id = i.id WHERE c.category_id = ? AND c.id != ? AND c.status = 'published' ORDER BY c.featured DESC, c.total_students DESC LIMIT 3");
                            $stmt->execute([$course['category_id'], $course['id']]);
                            $related = $stmt->fetchAll();
                        } catch (Exception $e) {}
                    }
                    if (!empty($related)):
                        foreach ($related as $rc):
                    ?>
                    <a href="course-details.php?id=<?php echo $rc['id']; ?>" class="text-decoration-none">
                        <div class="d-flex gap-3 p-3 bg-white rounded-3 shadow-sm mb-3 transition" style="border: 1px solid var(--gray-100);">
                            <img src="https://picsum.photos/seed/<?php echo $rc['id']; ?>-related/120/80" style="width: 120px; height: 80px; object-fit: cover; border-radius: var(--radius-sm); flex-shrink: 0;" alt="">
                            <div>
                                <h6 class="fw-bold mb-1" style="color: var(--secondary); font-size: 0.95rem;"><?php echo htmlspecialchars($rc['title']); ?></h6>
                                <small class="text-muted d-block mb-1"><?php echo htmlspecialchars($rc['instructor_name'] ?? 'Instructor'); ?></small>
                                <div class="d-flex align-items-center gap-2">
                                    <small style="color: var(--accent);"><?php echo getRatingStars($rc['rating']); ?></small>
                                    <small class="fw-bold" style="color: var(--primary);"><?php echo formatPrice($rc['price']); ?></small>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; else: ?>
                    <div class="text-center py-4 bg-white rounded-3 shadow-sm">
                        <i class="fas fa-book-open" style="font-size: 2rem; color: var(--gray-300); display: block; margin-bottom: 0.5rem;"></i>
                        <p class="text-muted mb-0">No related courses found.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
