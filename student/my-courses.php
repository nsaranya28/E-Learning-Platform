<?php
session_start();
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php', 'Please login to access your courses', 'warning');
}

$pageTitle = 'My Courses';
$hideNavbar = true;
require_once '../includes/header.php';

$userId = $_SESSION['user_id'];
$user = getUser($userId);

$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

$courses = [
    [
        'id' => 1, 'title' => 'Complete Web Development Bootcamp',
        'image' => 'https://placehold.co/600x400/2563eb/ffffff?text=Web+Dev',
        'instructor' => 'John Doe', 'instructor_img' => 'https://placehold.co/100x100/2563eb/ffffff?text=JD',
        'progress' => 75, 'status' => 'in_progress', 'lessons_completed' => 36, 'total_lessons' => 48,
        'category' => 'Web Development', 'level' => 'Beginner', 'last_accessed' => '2026-06-10',
    ],
    [
        'id' => 2, 'title' => 'Python for Data Science & Machine Learning',
        'image' => 'https://placehold.co/600x400/10b981/ffffff?text=Python+DS',
        'instructor' => 'Jane Smith', 'instructor_img' => 'https://placehold.co/100x100/10b981/ffffff?text=JS',
        'progress' => 45, 'status' => 'in_progress', 'lessons_completed' => 18, 'total_lessons' => 40,
        'category' => 'Data Science', 'level' => 'Intermediate', 'last_accessed' => '2026-06-09',
    ],
    [
        'id' => 3, 'title' => 'UI/UX Design Masterclass',
        'image' => 'https://placehold.co/600x400/f59e0b/ffffff?text=UI+UX',
        'instructor' => 'Sarah Johnson', 'instructor_img' => 'https://placehold.co/100x100/f59e0b/ffffff?text=SJ',
        'progress' => 100, 'status' => 'completed', 'lessons_completed' => 32, 'total_lessons' => 32,
        'category' => 'Design', 'level' => 'All Levels', 'last_accessed' => '2026-06-01',
    ],
    [
        'id' => 4, 'title' => 'React & Next.js Advanced Patterns',
        'image' => 'https://placehold.co/600x400/ef4444/ffffff?text=React',
        'instructor' => 'Mike Wilson', 'instructor_img' => 'https://placehold.co/100x100/ef4444/ffffff?text=MW',
        'progress' => 20, 'status' => 'in_progress', 'lessons_completed' => 8, 'total_lessons' => 40,
        'category' => 'Web Development', 'level' => 'Advanced', 'last_accessed' => '2026-06-08',
    ],
    [
        'id' => 5, 'title' => 'JavaScript Algorithms & Data Structures',
        'image' => 'https://placehold.co/600x400/7c3aed/ffffff?text=JS+Algo',
        'instructor' => 'Emily Chen', 'instructor_img' => 'https://placehold.co/100x100/7c3aed/ffffff?text=EC',
        'progress' => 0, 'status' => 'not_started', 'lessons_completed' => 0, 'total_lessons' => 50,
        'category' => 'Web Development', 'level' => 'Intermediate', 'last_accessed' => null,
    ],
    [
        'id' => 6, 'title' => 'AWS Cloud Practitioner Essentials',
        'image' => 'https://placehold.co/600x400/f97316/ffffff?text=AWS',
        'instructor' => 'David Brown', 'instructor_img' => 'https://placehold.co/100x100/f97316/ffffff?text=DB',
        'progress' => 100, 'status' => 'completed', 'lessons_completed' => 25, 'total_lessons' => 25,
        'category' => 'Cloud Computing', 'level' => 'Beginner', 'last_accessed' => '2026-05-20',
    ],
];

if ($statusFilter !== 'all') {
    $courses = array_filter($courses, function($c) use ($statusFilter) {
        if ($statusFilter === 'in_progress') return $c['status'] === 'in_progress';
        if ($statusFilter === 'completed') return $c['status'] === 'completed' || $c['progress'] == 100;
        if ($statusFilter === 'not_started') return $c['status'] === 'not_started' || $c['progress'] == 0;
        return true;
    });
}

function getStatusBadge($status) {
    $map = [
        'in_progress' => ['label' => 'In Progress', 'class' => 'badge-primary'],
        'completed' => ['label' => 'Completed', 'class' => 'badge-success'],
        'not_started' => ['label' => 'Not Started', 'class' => 'badge-warning'],
    ];
    $s = $map[$status] ?? ['label' => ucfirst($status), 'class' => 'badge-primary'];
    return "<span class=\"badge {$s['class']}\">{$s['label']}</span>";
}
?>

<style>
:root { --sidebar-width: 260px; }

.course-progress-ring {
    --size: 80px;
    --thickness: 5px;
    width: var(--size);
    height: var(--size);
    border-radius: 50%;
    position: relative;
    flex-shrink: 0;
}
.course-progress-ring svg {
    transform: rotate(-90deg);
    width: var(--size);
    height: var(--size);
}
.course-progress-ring .ring-bg { fill: none; stroke: var(--gray-200); stroke-width: var(--thickness); }
.course-progress-ring .ring-fg {
    fill: none;
    stroke: var(--primary);
    stroke-width: var(--thickness);
    stroke-linecap: round;
    transition: stroke-dashoffset 1s ease;
}
.course-progress-ring .ring-text {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 800;
    color: var(--primary);
}
.course-progress-ring.completed .ring-fg { stroke: var(--success); }
.course-progress-ring.completed .ring-text { color: var(--success); }

.filter-btn {
    border: 2px solid var(--gray-200);
    border-radius: 20px;
    padding: 0.4rem 1.2rem;
    font-size: 0.85rem;
    font-weight: 600;
    background: var(--white);
    color: var(--gray-600);
    cursor: pointer;
    transition: var(--transition);
}
.filter-btn:hover, .filter-btn.active {
    border-color: var(--primary);
    background: var(--primary);
    color: var(--white);
}

.continue-btn {
    border-radius: var(--radius);
    font-weight: 600;
    transition: var(--transition);
}
.continue-btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }

.enrolled-course-card {
    transition: var(--transition);
    border: 1px solid var(--gray-200);
}
.enrolled-course-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    border-color: transparent;
}
</style>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div>
                <h4>My Courses</h4>
                <p class="text-muted mb-0">Track and continue your learning journey.</p>
            </div>
            <a href="../courses.php" class="btn btn-primary"><i class="fas fa-search me-2"></i>Browse Courses</a>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="my-courses.php?status=all" class="filter-btn <?php echo $statusFilter === 'all' ? 'active' : ''; ?>">All</a>
            <a href="my-courses.php?status=in_progress" class="filter-btn <?php echo $statusFilter === 'in_progress' ? 'active' : ''; ?>">In Progress</a>
            <a href="my-courses.php?status=completed" class="filter-btn <?php echo $statusFilter === 'completed' ? 'active' : ''; ?>">Completed</a>
            <a href="my-courses.php?status=not_started" class="filter-btn <?php echo $statusFilter === 'not_started' ? 'active' : ''; ?>">Not Started</a>
        </div>

        <?php if (count($courses) === 0): ?>
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <h5>No courses found</h5>
            <p><?php echo $statusFilter !== 'all' ? 'No courses match this filter.' : 'You haven\'t enrolled in any courses yet.'; ?></p>
            <a href="../courses.php" class="btn btn-primary mt-2"><i class="fas fa-plus me-2"></i>Browse Courses</a>
        </div>
        <?php else: ?>
        <div class="row g-3">
            <?php foreach ($courses as $course):
                $circumference = 2 * pi() * 35;
                $offset = $circumference - ($course['progress'] / 100) * $circumference;
                $isCompleted = $course['progress'] >= 100;
            ?>
            <div class="col-lg-6">
                <div class="card enrolled-course-card h-100">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="<?php echo $course['image']; ?>" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 180px;" alt="<?php echo $course['title']; ?>">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="card-title mb-1" style="font-size: 0.95rem;"><?php echo $course['title']; ?></h6>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <img src="<?php echo $course['instructor_img']; ?>" class="rounded-circle" width="20" height="20" style="object-fit: cover;" alt="">
                                            <small class="text-muted"><?php echo $course['instructor']; ?></small>
                                        </div>
                                    </div>
                                    <?php echo getStatusBadge($course['status']); ?>
                                </div>

                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div class="course-progress-ring <?php echo $isCompleted ? 'completed' : ''; ?>">
                                        <svg viewBox="0 0 80 80">
                                            <circle class="ring-bg" cx="40" cy="40" r="35"/>
                                            <circle class="ring-fg" cx="40" cy="40" r="35"
                                                stroke-dasharray="<?php echo $circumference; ?>"
                                                stroke-dashoffset="<?php echo $offset; ?>"/>
                                        </svg>
                                        <div class="ring-text"><?php echo $course['progress']; ?>%</div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted"><?php echo $course['lessons_completed']; ?>/<?php echo $course['total_lessons']; ?> lessons</small>
                                            <small class="fw-bold" style="color: var(--primary);"><?php echo $course['progress']; ?>%</small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" style="width: <?php echo $course['progress']; ?>%; <?php echo $isCompleted ? 'background: var(--success);' : ''; ?>"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-signal me-1"></i><?php echo $course['level']; ?>
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-folder me-1"></i><?php echo $course['category']; ?>
                                    </small>
                                    <a href="../course-details.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm continue-btn">
                                        <?php if ($isCompleted): ?>
                                            <i class="fas fa-redo me-1"></i>Review
                                        <?php elseif ($course['progress'] === 0): ?>
                                            <i class="fas fa-play me-1"></i>Start Course
                                        <?php else: ?>
                                            <i class="fas fa-play me-1"></i>Continue Learning
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
