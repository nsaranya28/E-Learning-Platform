<?php
session_start();
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php', 'Please login to access your dashboard', 'warning');
}

$pageTitle = 'Dashboard';
$hideNavbar = true;
require_once '../includes/header.php';

$userId = $_SESSION['user_id'];
$user = getUser($userId);

$enrolledCount = 12;
$inProgressCount = 5;
$completedCount = 7;
$certificateCount = 4;

$recentCourses = [
    ['id' => 1, 'title' => 'Complete Web Development Bootcamp', 'image' => 'https://placehold.co/600x400/2563eb/ffffff?text=Web+Dev', 'instructor' => 'John Doe', 'progress' => 75, 'category' => 'Web Development'],
    ['id' => 2, 'title' => 'Python for Data Science & Machine Learning', 'image' => 'https://placehold.co/600x400/10b981/ffffff?text=Python+DS', 'instructor' => 'Jane Smith', 'progress' => 45, 'category' => 'Data Science'],
    ['id' => 3, 'title' => 'UI/UX Design Masterclass', 'image' => 'https://placehold.co/600x400/f59e0b/ffffff?text=UI%2FUX', 'instructor' => 'Sarah Johnson', 'progress' => 90, 'category' => 'Design'],
    ['id' => 4, 'title' => 'React & Next.js Advanced Patterns', 'image' => 'https://placehold.co/600x400/ef4444/ffffff?text=React', 'instructor' => 'Mike Wilson', 'progress' => 20, 'category' => 'Web Development'],
];

$deadlines = [
    ['course' => 'Complete Web Development Bootcamp', 'task' => 'Final Project Submission', 'date' => '2026-07-15', 'days_left' => 21, 'priority' => 'high'],
    ['course' => 'Python for Data Science', 'task' => 'Module 5 Quiz', 'date' => '2026-06-18', 'days_left' => 5, 'priority' => 'medium'],
    ['course' => 'UI/UX Design Masterclass', 'task' => 'Portfolio Project', 'date' => '2026-06-25', 'days_left' => 1, 'priority' => 'urgent'],
];

$activities = [
    ['icon' => 'fa-check-circle', 'color' => 'green', 'text' => 'Completed lesson "React Hooks Deep Dive"', 'time' => '2 hours ago'],
    ['icon' => 'fa-star', 'color' => 'yellow', 'text' => 'Earned certificate for "HTML & CSS Fundamentals"', 'time' => '1 day ago'],
    ['icon' => 'fa-play-circle', 'color' => 'blue', 'text' => 'Started course "Advanced JavaScript"', 'time' => '3 days ago'],
    ['icon' => 'fa-trophy', 'color' => 'purple', 'text' => 'Scored 90% on "Python Basics Quiz"', 'time' => '5 days ago'],
    ['icon' => 'fa-clock', 'color' => 'red', 'text' => 'Resumed "Data Structures & Algorithms"', 'time' => '1 week ago'],
    ['icon' => 'fa-users', 'color' => 'blue', 'text' => 'Joined study group for "Machine Learning"', 'time' => '2 weeks ago'],
];
?>

<style>
:root { --sidebar-width: 260px; }

.dashboard-sidebar .nav-link[href="dashboard.php"] {
    background: var(--primary);
    color: var(--white) !important;
}

.dashboard-content { max-width: 1400px; }

.deadline-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.8rem 0;
    border-bottom: 1px solid var(--gray-100);
}
.deadline-item:last-child { border-bottom: none; }
.deadline-item .priority-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.deadline-item .priority-dot.urgent { background: var(--danger); }
.deadline-item .priority-dot.high { background: var(--warning); }
.deadline-item .priority-dot.medium { background: var(--info); }
.deadline-item .deadline-info { flex: 1; }
.deadline-item .deadline-info h6 { font-size: 0.9rem; font-weight: 600; margin: 0; }
.deadline-item .deadline-info small { color: var(--gray-500); font-size: 0.8rem; }
.deadline-item .deadline-date {
    text-align: right;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--gray-600);
    white-space: nowrap;
}
.deadline-item .deadline-date .days {
    display: block;
    font-size: 0.75rem;
    font-weight: 400;
    color: var(--gray-400);
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 0.8rem;
    padding: 0.8rem 0;
    border-bottom: 1px solid var(--gray-100);
}
.activity-item:last-child { border-bottom: none; }
.activity-item .activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.85rem;
}
.activity-item .activity-icon.green { background: #d1fae5; color: var(--success); }
.activity-item .activity-icon.yellow { background: #fef3c7; color: var(--warning); }
.activity-item .activity-icon.blue { background: var(--primary-bg); color: var(--primary); }
.activity-item .activity-icon.purple { background: #ede9fe; color: #7c3aed; }
.activity-item .activity-icon.red { background: #fee2e2; color: var(--danger); }
.activity-item .activity-text { flex: 1; }
.activity-item .activity-text p { margin: 0; font-size: 0.9rem; color: var(--gray-700); }
.activity-item .activity-text small { color: var(--gray-400); font-size: 0.75rem; }

.progress-chart-placeholder {
    height: 200px;
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    padding-top: 1rem;
}
.progress-chart-placeholder .chart-bar {
    flex: 1;
    background: var(--primary-bg);
    border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    position: relative;
    min-height: 30px;
    transition: var(--transition);
}
.progress-chart-placeholder .chart-bar:hover { opacity: 0.8; }
.progress-chart-placeholder .chart-bar .bar-label {
    position: absolute;
    bottom: -24px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.7rem;
    color: var(--gray-500);
    white-space: nowrap;
}

.course-progress-ring {
    --size: 60px;
    --thickness: 4px;
    width: var(--size);
    height: var(--size);
    border-radius: 50%;
    position: relative;
    flex-shrink: 0;
}
.course-progress-ring .ring-fill {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: conic-gradient(var(--primary) var(--pct), var(--gray-200) var(--pct));
}
.course-progress-ring .ring-inner {
    position: absolute;
    inset: var(--thickness);
    background: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--primary);
}

.dashboard-quick-actions .btn {
    border: 2px dashed var(--gray-300);
    color: var(--gray-500);
    padding: 1rem;
    width: 100%;
    text-align: center;
    transition: var(--transition);
}
.dashboard-quick-actions .btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-bg);
}
</style>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Welcome back, <?php echo htmlspecialchars($user['full_name'] ?? $_SESSION['user_name'] ?? 'Student'); ?>!</h4>
                    <p class="text-muted mb-0">Here's what's happening with your learning today.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="my-courses.php" class="btn btn-primary"><i class="fas fa-book-open me-2"></i>My Courses</a>
                    <a href="../courses.php" class="btn btn-outline-primary"><i class="fas fa-plus me-2"></i>Browse Courses</a>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-book-open"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $enrolledCount; ?></h3>
                            <p>Enrolled Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon yellow"><i class="fas fa-spinner"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $inProgressCount; ?></h3>
                            <p>In Progress</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $completedCount; ?></h3>
                            <p>Completed</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon purple"><i class="fas fa-award"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $certificateCount; ?></h3>
                            <p>Certificates</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-play-circle me-2" style="color: var(--primary);"></i>Continue Learning</h5>
                        <a href="my-courses.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="row g-3">
                        <?php foreach ($recentCourses as $course): ?>
                        <div class="col-md-6">
                            <div class="card course-card h-100">
                                <div class="position-relative">
                                    <img src="<?php echo $course['image']; ?>" class="card-img-top" alt="<?php echo $course['title']; ?>">
                                    <span class="course-level"><?php echo $course['category']; ?></span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title" style="font-size: 0.95rem;"><?php echo $course['title']; ?></h6>
                                    <p class="card-text mb-2"><small><i class="fas fa-user-tie me-1"></i><?php echo $course['instructor']; ?></small></p>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted">Progress</small>
                                            <small class="fw-bold" style="color: var(--primary);"><?php echo $course['progress']; ?>%</small>
                                        </div>
                                        <div class="progress mb-3">
                                            <div class="progress-bar" style="width: <?php echo $course['progress']; ?>%;"></div>
                                        </div>
                                        <a href="../course-details.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-play me-1"></i>Continue Learning
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-calendar-alt me-2" style="color: var(--warning);"></i>Upcoming Deadlines</h6>
                            <?php if (count($deadlines) > 0): ?>
                                <?php foreach ($deadlines as $dl): ?>
                                <div class="deadline-item">
                                    <span class="priority-dot <?php echo $dl['priority']; ?>"></span>
                                    <div class="deadline-info">
                                        <h6><?php echo $dl['task']; ?></h6>
                                        <small><?php echo $dl['course']; ?></small>
                                    </div>
                                    <div class="deadline-date">
                                        <?php echo date('M d', strtotime($dl['date'])); ?>
                                        <span class="days"><?php echo $dl['days_left']; ?> days left</span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center mb-0 py-3">No upcoming deadlines!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-clock me-2" style="color: var(--info);"></i>Recent Activity</h6>
                            <?php if (count($activities) > 0): ?>
                                <?php foreach ($activities as $act): ?>
                                <div class="activity-item">
                                    <div class="activity-icon <?php echo $act['color']; ?>">
                                        <i class="fas <?php echo $act['icon']; ?>"></i>
                                    </div>
                                    <div class="activity-text">
                                        <p><?php echo $act['text']; ?></p>
                                        <small><?php echo $act['time']; ?></small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center mb-0 py-3">No recent activity.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="fas fa-chart-bar me-2" style="color: var(--primary);"></i>Weekly Learning Activity</h6>
                        <div class="d-flex gap-2">
                            <span class="badge badge-primary">This Week</span>
                            <span class="badge" style="background: var(--gray-100); color: var(--gray-600); cursor: pointer;">Last Week</span>
                        </div>
                    </div>
                    <div class="progress-chart-placeholder">
                        <?php
                        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                        $hours = [2.5, 3.0, 1.5, 4.0, 2.0, 5.5, 3.5];
                        $maxHours = max($hours) ?: 1;
                        foreach ($days as $i => $day):
                            $pct = ($hours[$i] / $maxHours) * 100;
                            $color = $i == date('N') - 1 ? 'var(--primary)' : 'var(--primary-bg)';
                            $fillColor = $i == date('N') - 1 ? 'var(--primary)' : 'var(--primary)';
                        ?>
                        <div class="chart-bar" style="height: <?php echo $pct; ?>%; background: <?php echo $color; ?>;">
                            <span class="bar-label"><?php echo $day; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-4">
                        <small class="text-muted">Total: <?php echo array_sum($hours); ?> hours this week</small>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
