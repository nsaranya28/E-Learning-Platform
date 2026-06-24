<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Reports & Analytics';
require_once '../includes/header.php';

$totalRevenue = 284500;
$totalUsers = 12580;
$totalCourses = 520;
$totalEnrollments = 8740;
$growthRate = 12.5;

$monthlyRevenue = [18500, 22300, 19800, 25600, 28900, 31200, 27800, 34500, 38200, 41000, 39800, 45200];
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$maxRevenue = max($monthlyRevenue) ?: 1;

$userGrowth = [8200, 8600, 9100, 9600, 10200, 10800, 11200, 11600, 11900, 12200, 12450, 12580];
$maxUsers = max($userGrowth) ?: 1;

$popularCourses = [
    ['title' => 'Complete Web Development Bootcamp', 'instructor' => 'Michael Chen', 'enrollments' => 234, 'rating' => 4.8, 'revenue' => 20857],
    ['title' => 'Python for Data Science & ML', 'instructor' => 'Dr. Sarah Johnson', 'enrollments' => 189, 'rating' => 4.7, 'revenue' => 15118],
    ['title' => 'UI/UX Design Masterclass', 'instructor' => 'Emily Roberts', 'enrollments' => 156, 'rating' => 4.9, 'revenue' => 10918],
    ['title' => 'Introduction to Cybersecurity', 'instructor' => 'David Park', 'enrollments' => 312, 'rating' => 4.3, 'revenue' => 0],
    ['title' => 'Business Management Fundamentals', 'instructor' => 'Lisa Anderson', 'enrollments' => 145, 'rating' => 4.4, 'revenue' => 8699],
    ['title' => 'Advanced React & Next.js Patterns', 'instructor' => 'Michael Chen', 'enrollments' => 98, 'rating' => 4.6, 'revenue' => 9309],
];

$categoryDistribution = [
    ['name' => 'Web Development', 'count' => 85, 'color' => '#2563eb'],
    ['name' => 'Data Science', 'count' => 62, 'color' => '#10b981'],
    ['name' => 'UI/UX Design', 'count' => 46, 'color' => '#f59e0b'],
    ['name' => 'Mobile Development', 'count' => 41, 'color' => '#ef4444'],
    ['name' => 'DevOps & Cloud', 'count' => 38, 'color' => '#7c3aed'],
    ['name' => 'Business', 'count' => 29, 'color' => '#06b6d4'],
    ['name' => 'Cybersecurity', 'count' => 22, 'color' => '#f97316'],
];
$maxCategoryCount = max(array_column($categoryDistribution, 'count')) ?: 1;
$totalCategoryCourses = array_sum(array_column($categoryDistribution, 'count'));
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }
.chart-bar-row {
    display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
}
.chart-bar-row .bar-label { min-width: 120px; font-size: 0.85rem; color: var(--gray-600); }
.chart-bar-row .bar-track {
    flex: 1; height: 28px; background: var(--gray-100); border-radius: 6px; overflow: hidden;
}
.chart-bar-row .bar-fill {
    height: 100%; border-radius: 6px; transition: var(--transition);
    display: flex; align-items: center; padding-left: 10px;
    font-size: 0.8rem; font-weight: 600; color: var(--white); min-width: fit-content;
}
.chart-bar-row .bar-value { min-width: 50px; text-align: right; font-size: 0.85rem; font-weight: 600; color: var(--gray-600); }
.revenue-bar { height: 160px; display: flex; align-items: flex-end; gap: 3px; }
.revenue-bar .col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }
.revenue-bar .col .bar {
    width: 100%; border-radius: 4px 4px 0 0; min-height: 4px;
    background: var(--primary); opacity: 0.85; transition: var(--transition);
}
.revenue-bar .col .bar:hover { opacity: 1; }
.revenue-bar .col .label { font-size: 0.6rem; color: var(--gray-400); text-align: center; }
.export-btn {
    display: inline-flex; align-items: center; gap: 8px; padding: 0.6rem 1.2rem;
    border: 2px dashed var(--gray-300); border-radius: var(--radius);
    color: var(--gray-600); transition: var(--transition); cursor: pointer;
    background: var(--white); font-size: 0.9rem;
}
.export-btn:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-bg); }
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Reports & Analytics</h4>
                    <p class="text-muted mb-0">Platform performance metrics and insights.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="export-btn" onclick="alert('Export CSV feature coming soon!')"><i class="fas fa-file-csv" style="color: var(--success);"></i>Export CSV</button>
                    <button class="export-btn" onclick="alert('Export PDF feature coming soon!')"><i class="fas fa-file-pdf" style="color: var(--danger);"></i>Export PDF</button>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-dollar-sign"></i></div>
                        <div class="stat-info">
                            <h3>$<?php echo number_format($totalRevenue); ?></h3>
                            <p>Total Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <h3><?php echo number_format($totalUsers); ?></h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon purple"><i class="fas fa-book-open"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $totalCourses; ?></h3>
                            <p>Active Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon yellow"><i class="fas fa-graduation-cap"></i></div>
                        <div class="stat-info">
                            <h3><?php echo number_format($totalEnrollments); ?></h3>
                            <p>Enrollments</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0"><i class="fas fa-chart-line me-2" style="color: var(--primary);"></i>Monthly Revenue (<?php echo date('Y'); ?>)</h6>
                                <span class="badge badge-success">+<?php echo $growthRate; ?>% growth</span>
                            </div>
                            <div class="revenue-bar">
                                <?php foreach ($months as $i => $month):
                                    $height = max(4, ($monthlyRevenue[$i] / $maxRevenue) * 160);
                                    $isMax = $monthlyRevenue[$i] === $maxRevenue;
                                ?>
                                <div class="col">
                                    <div class="bar" style="height: <?php echo $height; ?>px; background: <?php echo $isMax ? 'var(--accent)' : 'var(--primary)'; ?>;" title="$<?php echo number_format($monthlyRevenue[$i]); ?>"></div>
                                    <span class="label"><?php echo $month; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-chart-area me-2" style="color: var(--success);"></i>User Growth (<?php echo date('Y'); ?>)</h6>
                            <div class="revenue-bar">
                                <?php foreach ($months as $i => $month):
                                    $height = max(4, ($userGrowth[$i] / $maxUsers) * 160);
                                ?>
                                <div class="col">
                                    <div class="bar" style="height: <?php echo $height; ?>px; background: var(--success);" title="<?php echo number_format($userGrowth[$i]); ?> users"></div>
                                    <span class="label"><?php echo $month; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-trophy me-2" style="color: var(--accent);"></i>Popular Courses</h6>
                            <div style="max-height: 320px; overflow-y: auto;">
                                <?php foreach (array_slice($popularCourses, 0, 5) as $pc): ?>
                                <div class="d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom: 1px solid var(--gray-100);">
                                    <div style="width: 6px; height: 6px; border-radius: 50%; background: var(--primary); flex-shrink: 0;"></div>
                                    <div style="flex: 1; min-width: 0;">
                                        <small class="fw-semibold d-block text-truncate"><?php echo htmlspecialchars($pc['title']); ?></small>
                                        <small class="text-muted"><?php echo $pc['enrollments']; ?> enrollments &bull; <?php echo $pc['rating']; ?> rating</small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <a href="courses.php" class="btn btn-sm btn-outline-primary w-100 mt-2">View All Courses</a>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-pie-chart me-2" style="color: var(--purple);"></i>Category Distribution</h6>
                            <div>
                                <?php foreach ($categoryDistribution as $cd): ?>
                                <div class="chart-bar-row">
                                    <span class="bar-label"><i class="fas fa-circle me-1" style="color: <?php echo $cd['color']; ?>; font-size: 0.5rem;"></i><?php echo $cd['name']; ?></span>
                                    <div class="bar-track">
                                        <div class="bar-fill" style="width: <?php echo ($cd['count'] / $maxCategoryCount) * 100; ?>%; background: <?php echo $cd['color']; ?>;"><?php echo $cd['count']; ?></div>
                                    </div>
                                    <span class="bar-value"><?php echo round(($cd['count'] / $totalCategoryCourses) * 100); ?>%</span>
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
