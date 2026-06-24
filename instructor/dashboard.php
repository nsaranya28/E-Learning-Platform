<?php
session_start();
if (!isset($_SESSION['instructor_id'])) { header('Location: ../instructor-login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Dashboard';
require_once '../includes/header.php';

$totalCourses = 12;
$totalStudents = 847;
$totalRevenue = 45280;
$averageRating = 4.7;

$recentCourses = [
    ['id' => 1, 'title' => 'Complete Web Development Bootcamp', 'category' => 'Web Development', 'price' => 89.99, 'students' => 234, 'rating' => 4.8, 'status' => 'published'],
    ['id' => 2, 'title' => 'Python for Data Science & ML', 'category' => 'Data Science', 'price' => 79.99, 'students' => 189, 'rating' => 4.7, 'status' => 'published'],
    ['id' => 3, 'title' => 'UI/UX Design Masterclass', 'category' => 'Design', 'price' => 69.99, 'students' => 156, 'rating' => 4.9, 'status' => 'published'],
    ['id' => 4, 'title' => 'Advanced React & Next.js', 'category' => 'Web Development', 'price' => 94.99, 'students' => 98, 'rating' => 4.6, 'status' => 'draft'],
    ['id' => 5, 'title' => 'Mobile App Development with Flutter', 'category' => 'Mobile Development', 'price' => 84.99, 'students' => 67, 'rating' => 4.5, 'status' => 'published'],
];

$chartLabels = ['May 26', 'May 27', 'May 28', 'May 29', 'May 30', 'May 31', 'Jun 1', 'Jun 2', 'Jun 3', 'Jun 4', 'Jun 5', 'Jun 6', 'Jun 7', 'Jun 8', 'Jun 9', 'Jun 10', 'Jun 11', 'Jun 12', 'Jun 13', 'Jun 14', 'Jun 15', 'Jun 16', 'Jun 17', 'Jun 18', 'Jun 19', 'Jun 20', 'Jun 21', 'Jun 22', 'Jun 23', 'Jun 24'];
$chartData = [12, 18, 15, 22, 19, 25, 30, 28, 35, 32, 40, 38, 42, 45, 39, 48, 52, 50, 55, 58, 53, 60, 62, 58, 65, 70, 68, 72, 75, 80];
$maxVal = max($chartData) ?: 1;

$recentActivities = [
    ['student' => 'Emily Johnson', 'course' => 'Complete Web Development Bootcamp', 'action' => 'Completed Module 5', 'time' => '2 hours ago', 'avatar' => 'EJ'],
    ['student' => 'Michael Chen', 'course' => 'Python for Data Science', 'action' => 'Submitted assignment', 'time' => '4 hours ago', 'avatar' => 'MC'],
    ['student' => 'Sarah Williams', 'course' => 'UI/UX Design Masterclass', 'action' => 'Scored 92% on quiz', 'time' => '6 hours ago', 'avatar' => 'SW'],
    ['student' => 'David Brown', 'course' => 'Complete Web Development', 'action' => 'Enrolled in course', 'time' => '1 day ago', 'avatar' => 'DB'],
    ['student' => 'Lisa Anderson', 'course' => 'Advanced React & Next.js', 'action' => 'Posted a question', 'time' => '1 day ago', 'avatar' => 'LA'],
    ['student' => 'James Wilson', 'course' => 'Mobile App Development', 'action' => 'Started Module 2', 'time' => '2 days ago', 'avatar' => 'JW'],
];
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }

.activities-list { max-height: 400px; overflow-y: auto; }
.activity-item {
    display: flex; align-items: flex-start; gap: 0.8rem; padding: 0.8rem 0;
    border-bottom: 1px solid var(--gray-100);
}
.activity-item:last-child { border-bottom: none; }
.activity-avatar {
    width: 38px; height: 38px; border-radius: 50%; display: flex;
    align-items: center; justify-content: center; font-size: 0.8rem;
    font-weight: 700; flex-shrink: 0;
}
.activity-info { flex: 1; }
.activity-info p { margin: 0; font-size: 0.9rem; color: var(--gray-700); }
.activity-info small { color: var(--gray-400); font-size: 0.75rem; }

.enrollment-chart {
    display: flex; align-items: flex-end; gap: 3px; height: 180px;
    padding: 1rem 0 1.5rem;
}
.chart-column {
    flex: 1; border-radius: 3px 3px 0 0; position: relative;
    background: var(--primary); min-height: 4px;
    transition: var(--transition); opacity: 0.8;
}
.chart-column.today { background: var(--accent); opacity: 1; }
.chart-column:hover { opacity: 1; transform: scaleY(1.02); transform-origin: bottom; }
.chart-x-labels {
    display: flex; gap: 3px; font-size: 0.6rem; color: var(--gray-400);
}
.chart-x-labels span { flex: 1; text-align: center; }
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Welcome back, <?php echo htmlspecialchars($_SESSION['instructor_name'] ?? 'Instructor'); ?>!</h4>
                    <p class="text-muted mb-0">Here's an overview of your teaching performance.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="create-course.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>New Course</a>
                    <a href="manage-courses.php" class="btn btn-outline-primary"><i class="fas fa-edit me-2"></i>Manage Courses</a>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-book-open"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $totalCourses; ?></h3>
                            <p>Total Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $totalStudents; ?></h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon yellow"><i class="fas fa-dollar-sign"></i></div>
                        <div class="stat-info">
                            <h3>$<?php echo number_format($totalRevenue); ?></h3>
                            <p>Total Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon purple"><i class="fas fa-star"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $averageRating; ?></h3>
                            <p>Average Rating</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0"><i class="fas fa-chart-line me-2" style="color: var(--primary);"></i>Enrollments (Last 30 Days)</h6>
                                <span class="badge badge-success">+<?php echo array_sum(array_slice($chartData, -7)); ?> this week</span>
                            </div>
                            <div class="enrollment-chart">
                                <?php foreach ($chartData as $i => $val):
                                    $height = max(4, ($val / $maxVal) * 180);
                                    $isToday = $i === count($chartData) - 1;
                                ?>
                                <div class="chart-column <?php echo $isToday ? 'today' : ''; ?>" style="height: <?php echo $height; ?>px;" title="<?php echo $chartLabels[$i] . ': ' . $val . ' enrollments'; ?>"></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="chart-x-labels">
                                <?php foreach ([0, 4, 9, 14, 19, 24, 29] as $i): if ($i < count($chartLabels)): ?>
                                <span><?php echo $chartLabels[$i]; ?></span>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-clock me-2" style="color: var(--info);"></i>Recent Student Activity</h6>
                            <div class="activities-list">
                                <?php foreach ($recentActivities as $act): ?>
                                <div class="activity-item">
                                    <div class="activity-avatar" style="background: var(--primary-bg); color: var(--primary);"><?php echo $act['avatar']; ?></div>
                                    <div class="activity-info">
                                        <p><strong><?php echo htmlspecialchars($act['student']); ?></strong> <?php echo htmlspecialchars($act['action']); ?> in <strong><?php echo htmlspecialchars($act['course']); ?></strong></p>
                                        <small><?php echo $act['time']; ?></small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="fas fa-book me-2" style="color: var(--primary);"></i>Recent Courses</h6>
                        <a href="manage-courses.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Course Title</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Students</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentCourses as $course): ?>
                                <tr>
                                    <td><a href="manage-courses.php?id=<?php echo $course['id']; ?>" class="fw-semibold" style="color: var(--gray-800);"><?php echo htmlspecialchars($course['title']); ?></a></td>
                                    <td><?php echo $course['category']; ?></td>
                                    <td>$<?php echo number_format($course['price'], 2); ?></td>
                                    <td><?php echo $course['students']; ?></td>
                                    <td>
                                        <span class="text-warning"><i class="fas fa-star me-1"></i><?php echo $course['rating']; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $course['status'] === 'published' ? 'badge-success' : 'badge-warning'; ?>">
                                            <?php echo ucfirst($course['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once '../includes/footer.php'; ?>
