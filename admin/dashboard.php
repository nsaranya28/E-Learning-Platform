<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Dashboard';
require_once '../includes/header.php';

$totalUsers = 12580;
$totalInstructors = 245;
$totalCourses = 520;
$totalRevenue = 284500;
$activeEnrollments = 8740;
$certificatesIssued = 4230;

$chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$chartData = [18500, 22300, 19800, 25600, 28900, 31200, 27800, 34500, 38200, 41000, 39800, 45200];
$maxVal = max($chartData) ?: 1;

$recentRegistrations = [
    ['id' => 1, 'name' => 'Alice Johnson', 'email' => 'alice@example.com', 'role' => 'Student', 'date' => '2026-06-23', 'avatar' => 'AJ'],
    ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bob@example.com', 'role' => 'Student', 'date' => '2026-06-23', 'avatar' => 'BS'],
    ['id' => 3, 'name' => 'Carol White', 'email' => 'carol@example.com', 'role' => 'Instructor', 'date' => '2026-06-22', 'avatar' => 'CW'],
    ['id' => 4, 'name' => 'David Lee', 'email' => 'david@example.com', 'role' => 'Student', 'date' => '2026-06-22', 'avatar' => 'DL'],
    ['id' => 5, 'name' => 'Emma Brown', 'email' => 'emma@example.com', 'role' => 'Student', 'date' => '2026-06-21', 'avatar' => 'EB'],
];

$activities = [
    ['icon' => 'fa-user-plus', 'color' => 'green', 'text' => 'New student registered: Sarah Wilson', 'time' => '5 min ago'],
    ['icon' => 'fa-book', 'color' => 'blue', 'text' => 'Course "Advanced React" published', 'time' => '1 hour ago'],
    ['icon' => 'fa-star', 'color' => 'yellow', 'text' => 'New 5-star review on Python Bootcamp', 'time' => '3 hours ago'],
    ['icon' => 'fa-certificate', 'color' => 'purple', 'text' => 'Certificate issued to Michael Chen', 'time' => '5 hours ago'],
    ['icon' => 'fa-exclamation-triangle', 'color' => 'red', 'text' => 'Instructor John Doe flagged a report', 'time' => '1 day ago'],
    ['icon' => 'fa-dollar-sign', 'color' => 'green', 'text' => 'Revenue milestone: $25,000 this month', 'time' => '2 days ago'],
];
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }
.activity-item {
    display: flex; align-items: flex-start; gap: 0.8rem; padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-100);
}
.activity-item:last-child { border-bottom: none; }
.activity-icon {
    width: 36px; height: 36px; border-radius: 50%; display: flex;
    align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem;
}
.activity-icon.green { background: #d1fae5; color: var(--success); }
.activity-icon.yellow { background: #fef3c7; color: var(--warning); }
.activity-icon.blue { background: var(--primary-bg); color: var(--primary); }
.activity-icon.purple { background: #ede9fe; color: #7c3aed; }
.activity-icon.red { background: #fee2e2; color: var(--danger); }
.activity-text { flex: 1; }
.activity-text p { margin: 0; font-size: 0.9rem; color: var(--gray-700); }
.activity-text small { color: var(--gray-400); font-size: 0.75rem; }
.revenue-chart {
    display: flex; align-items: flex-end; gap: 4px; height: 200px;
    padding: 1rem 0 1.5rem;
}
.chart-column {
    flex: 1; border-radius: 4px 4px 0 0; position: relative;
    background: var(--primary); min-height: 4px;
    transition: var(--transition); opacity: 0.85;
}
.chart-column:hover { opacity: 1; }
.chart-x-labels {
    display: flex; gap: 4px; font-size: 0.65rem; color: var(--gray-400);
}
.chart-x-labels span { flex: 1; text-align: center; }
.quick-action-btn {
    display: flex; align-items: center; gap: 12px; padding: 1rem 1.2rem;
    border: 2px dashed var(--gray-300); border-radius: var(--radius);
    color: var(--gray-600); transition: var(--transition); cursor: pointer;
    background: var(--white);
}
.quick-action-btn:hover {
    border-color: var(--primary); color: var(--primary);
    background: var(--primary-bg); text-decoration: none;
}
.quick-action-btn i { font-size: 1.2rem; width: 24px; text-align: center; }
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Admin Dashboard</h4>
                    <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>! Here's your platform overview.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="window.location.reload()"><i class="fas fa-sync-alt me-1"></i>Refresh</button>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-4 col-xl-2">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <h3><?php echo number_format($totalUsers); ?></h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-xl-2">
                    <div class="stat-card">
                        <div class="stat-icon purple"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $totalInstructors; ?></h3>
                            <p>Instructors</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-xl-2">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="fas fa-book-open"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $totalCourses; ?></h3>
                            <p>Total Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-xl-2">
                    <div class="stat-card">
                        <div class="stat-icon yellow"><i class="fas fa-dollar-sign"></i></div>
                        <div class="stat-info">
                            <h3>$<?php echo number_format($totalRevenue); ?></h3>
                            <p>Total Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-xl-2">
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="fas fa-graduation-cap"></i></div>
                        <div class="stat-info">
                            <h3><?php echo number_format($activeEnrollments); ?></h3>
                            <p>Enrollments</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-xl-2">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#ede9fe;color:#7c3aed;"><i class="fas fa-award"></i></div>
                        <div class="stat-info">
                            <h3><?php echo number_format($certificatesIssued); ?></h3>
                            <p>Certificates</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0"><i class="fas fa-chart-line me-2" style="color: var(--primary);"></i>Revenue Overview (<?php echo date('Y'); ?>)</h6>
                                <span class="badge badge-success">+<?php echo number_format(end($chartData) - $chartData[0]); ?> growth</span>
                            </div>
                            <div class="revenue-chart">
                                <?php foreach ($chartData as $i => $val):
                                    $height = max(4, ($val / $maxVal) * 200);
                                ?>
                                <div class="chart-column" style="height: <?php echo $height; ?>px;" title="<?php echo $chartLabels[$i] . ': $' . number_format($val); ?>"></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="chart-x-labels">
                                <?php foreach ($chartLabels as $label): ?>
                                <span><?php echo $label; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-user-plus me-2" style="color: var(--success);"></i>Recent Registrations</h6>
                            <div class="table-responsive">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentRegistrations as $reg): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span style="width:32px;height:32px;border-radius:50%;background:var(--primary-bg);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;"><?php echo $reg['avatar']; ?></span>
                                                    <span class="fw-semibold"><?php echo htmlspecialchars($reg['name']); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($reg['email']); ?></td>
                                            <td><span class="badge <?php echo $reg['role'] === 'Instructor' ? 'badge-primary' : 'badge-success'; ?>"><?php echo $reg['role']; ?></span></td>
                                            <td><small class="text-muted"><?php echo date('M d, Y', strtotime($reg['date'])); ?></small></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-bolt me-2" style="color: var(--accent);"></i>Quick Actions</h6>
                            <div class="d-flex flex-column gap-2">
                                <a href="courses.php" class="quick-action-btn"><i class="fas fa-plus-circle" style="color: var(--primary);"></i>Add New Course</a>
                                <a href="instructors.php" class="quick-action-btn"><i class="fas fa-user-plus" style="color: var(--success);"></i>Add Instructor</a>
                                <a href="students.php" class="quick-action-btn"><i class="fas fa-user-graduate" style="color: var(--info);"></i>Manage Students</a>
                                <a href="categories.php" class="quick-action-btn"><i class="fas fa-tag" style="color: var(--warning);"></i>Manage Categories</a>
                                <a href="reports.php" class="quick-action-btn"><i class="fas fa-download" style="color: var(--purple);"></i>Export Reports</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-bell me-2" style="color: var(--info);"></i>Platform Activity</h6>
                            <div style="max-height: 360px; overflow-y: auto;">
                                <?php foreach ($activities as $act): ?>
                                <div class="activity-item">
                                    <div class="activity-icon <?php echo $act['color']; ?>"><i class="fas <?php echo $act['icon']; ?>"></i></div>
                                    <div class="activity-text">
                                        <p><?php echo $act['text']; ?></p>
                                        <small><?php echo $act['time']; ?></small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once '../includes/footer.php'; ?>
