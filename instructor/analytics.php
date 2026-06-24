<?php
session_start();
if (!isset($_SESSION['instructor_id'])) { header('Location: ../instructor-login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Analytics';
require_once '../includes/header.php';

$coursePerformance = [
    ['title' => 'Complete Web Development Bootcamp', 'students' => 234, 'revenue' => 20856, 'rating' => 4.8, 'completion' => 68],
    ['title' => 'Python for Data Science & ML', 'students' => 189, 'revenue' => 15118, 'rating' => 4.7, 'completion' => 72],
    ['title' => 'UI/UX Design Masterclass', 'students' => 156, 'revenue' => 10918, 'rating' => 4.9, 'completion' => 81],
    ['title' => 'Mobile App Development with Flutter', 'students' => 67, 'revenue' => 5694, 'rating' => 4.5, 'completion' => 55],
    ['title' => 'Advanced React & Next.js', 'students' => 98, 'revenue' => 9309, 'rating' => 4.6, 'completion' => 42],
];

$monthlyEnrollments = [
    ['month' => 'Jan', 'count' => 45], ['month' => 'Feb', 'count' => 62], ['month' => 'Mar', 'count' => 58],
    ['month' => 'Apr', 'count' => 75], ['month' => 'May', 'count' => 82], ['month' => 'Jun', 'count' => 90],
];
$maxMonthly = max(array_column($monthlyEnrollments, 'count')) ?: 1;

$studentGrowth = [
    ['month' => 'Jan', 'count' => 420], ['month' => 'Feb', 'count' => 480], ['month' => 'Mar', 'count' => 540],
    ['month' => 'Apr', 'count' => 610], ['month' => 'May', 'count' => 720], ['month' => 'Jun', 'count' => 847],
];
$maxGrowth = max(array_column($studentGrowth, 'count')) ?: 1;

$demographics = [
    ['country' => 'United States', 'count' => 342, 'flag' => 'US'],
    ['country' => 'United Kingdom', 'count' => 98, 'flag' => 'UK'],
    ['country' => 'Canada', 'count' => 76, 'flag' => 'CA'],
    ['country' => 'Australia', 'count' => 54, 'flag' => 'AU'],
    ['country' => 'India', 'count' => 89, 'flag' => 'IN'],
    ['country' => 'Germany', 'count' => 47, 'flag' => 'DE'],
    ['country' => 'Others', 'count' => 141, 'flag' => ''],
];
$totalDemographics = array_sum(array_column($demographics, 'count')) ?: 1;

$recentMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
$revenueData = [12500, 15800, 14200, 18900, 21000, 22800];
$maxRevenue = max($revenueData) ?: 1;
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }

.chart-container {
    background: var(--white); border-radius: var(--radius-md); padding: 1.5rem;
    box-shadow: var(--shadow-sm); margin-bottom: 1.5rem;
}
.chart-container h6 { font-weight: 700; margin-bottom: 1.5rem; }
.chart-container h6 i { margin-right: 0.5rem; }

.bar-chart {
    display: flex; align-items: flex-end; gap: 1rem; height: 200px;
    padding: 0.5rem 0;
}
.bar-chart .bar-item {
    flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;
}
.bar-chart .bar-item .bar {
    width: 100%; border-radius: 4px 4px 0 0; min-height: 8px;
    transition: var(--transition); position: relative;
}
.bar-chart .bar-item .bar:hover { opacity: 0.85; }
.bar-chart .bar-item .bar-label {
    font-size: 0.75rem; color: var(--gray-500); text-align: center;
}
.bar-chart .bar-item .bar-value {
    font-size: 0.7rem; font-weight: 700; color: var(--gray-600);
}

.donut-chart {
    width: 180px; height: 180px; border-radius: 50%;
    position: relative; margin: 0 auto;
}
.donut-chart .donut-segment {
    position: absolute; inset: 0; border-radius: 50%;
}
.donut-center {
    position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
    text-align: center;
}
.donut-center h3 { font-weight: 800; margin: 0; }
.donut-center small { color: var(--gray-500); }

.demographics-list .demo-item {
    display: flex; align-items: center; gap: 0.8rem; padding: 0.6rem 0;
    border-bottom: 1px solid var(--gray-100);
}
.demographics-list .demo-item:last-child { border-bottom: none; }
.demographics-list .demo-bar {
    flex: 1; height: 8px; background: var(--gray-200); border-radius: 4px; overflow: hidden;
}
.demographics-list .demo-bar .fill {
    height: 100%; border-radius: 4px; transition: width 0.6s ease;
}
.demographics-list .demo-pct { font-size: 0.85rem; font-weight: 600; min-width: 40px; text-align: right; }

.rating-stars-display i { color: var(--accent); margin-right: 1px; }
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Analytics</h4>
                    <p class="text-muted mb-0">Track your teaching performance and student engagement.</p>
                </div>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>Last 6 Months</option>
                        <option>Last Year</option>
                        <option>All Time</option>
                    </select>
                    <button class="btn btn-outline-primary btn-sm"><i class="fas fa-download me-1"></i>Export Report</button>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <h6><i class="fas fa-dollar-sign" style="color: var(--primary);"></i>Monthly Revenue</h6>
                        <div class="bar-chart">
                            <?php foreach ($recentMonths as $i => $month):
                                $height = max(8, ($revenueData[$i] / $maxRevenue) * 200);
                            ?>
                            <div class="bar-item">
                                <div class="bar-value">$<?php echo number_format($revenueData[$i] / 1000, 1); ?>k</div>
                                <div class="bar" style="height: <?php echo $height; ?>px; background: linear-gradient(to top, var(--primary), var(--primary-light));"></div>
                                <div class="bar-label"><?php echo $month; ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-container">
                        <h6><i class="fas fa-users" style="color: var(--success);"></i>Student Growth</h6>
                        <div class="bar-chart" style="height: 180px;">
                            <?php foreach ($studentGrowth as $sg):
                                $height = max(8, ($sg['count'] / $maxGrowth) * 180);
                            ?>
                            <div class="bar-item">
                                <div class="bar-value"><?php echo $sg['count']; ?></div>
                                <div class="bar" style="height: <?php echo $height; ?>px; background: linear-gradient(to top, var(--success), #34d399);"></div>
                                <div class="bar-label"><?php echo $sg['month']; ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fas fa-trophy" style="color: var(--warning);"></i>Course Performance</h6>
                            <span class="badge badge-primary">Top Performing</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Students</th>
                                        <th>Revenue</th>
                                        <th>Rating</th>
                                        <th>Completion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($coursePerformance as $cp): ?>
                                    <tr>
                                        <td class="fw-semibold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($cp['title']); ?></td>
                                        <td><?php echo $cp['students']; ?></td>
                                        <td class="fw-semibold" style="color: var(--success);">$<?php echo number_format($cp['revenue']); ?></td>
                                        <td>
                                            <div class="rating-stars-display">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star" style="color: <?php echo $i <= floor($cp['rating']) ? 'var(--accent)' : 'var(--gray-200)'; ?>;"></i>
                                                <?php endfor; ?>
                                                <small class="ms-1"><?php echo $cp['rating']; ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 6px;">
                                                    <div class="progress-bar" style="width: <?php echo $cp['completion']; ?>%; background: <?php echo $cp['completion'] >= 70 ? 'var(--success)' : 'var(--warning)'; ?>;"></div>
                                                </div>
                                                <small class="fw-semibold"><?php echo $cp['completion']; ?>%</small>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-container">
                        <h6><i class="fas fa-globe" style="color: var(--info);"></i>Student Demographics</h6>
                        <div class="demographics-list">
                            <?php foreach ($demographics as $d):
                                $pct = round(($d['count'] / $totalDemographics) * 100);
                                $colors = ['var(--primary)', 'var(--success)', 'var(--warning)', 'var(--danger)', 'var(--info)', '#8b5cf6', 'var(--gray-400)'];
                                $color = $colors[array_search($d, $demographics)] ?? 'var(--primary)';
                            ?>
                            <div class="demo-item">
                                <span class="fw-semibold" style="font-size: 0.85rem; min-width: 100px;"><?php echo htmlspecialchars($d['country']); ?></span>
                                <div class="demo-bar">
                                    <div class="fill" style="width: <?php echo $pct; ?>%; background: <?php echo $color; ?>;"></div>
                                </div>
                                <span class="demo-pct"><?php echo $pct; ?>%</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="chart-container">
                        <h6><i class="fas fa-calendar-check" style="color: var(--accent);"></i>Monthly Enrollments</h6>
                        <div class="bar-chart" style="height: 150px;">
                            <?php foreach ($monthlyEnrollments as $me):
                                $height = max(8, ($me['count'] / $maxMonthly) * 150);
                            ?>
                            <div class="bar-item">
                                <div class="bar-value"><?php echo $me['count']; ?></div>
                                <div class="bar" style="height: <?php echo $height; ?>px; background: linear-gradient(to top, var(--accent), #fbbf24);"></div>
                                <div class="bar-label"><?php echo $me['month']; ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once '../includes/footer.php'; ?>
