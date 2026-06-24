<?php
session_start();
if (!isset($_SESSION['instructor_id'])) { header('Location: ../instructor-login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Students';
require_once '../includes/header.php';

$students = [
    ['id' => 1, 'name' => 'Emily Johnson', 'email' => 'emily.j@example.com', 'course' => 'Complete Web Development Bootcamp', 'progress' => 75, 'last_activity' => '2026-06-23 14:30:00', 'enrolled' => '2026-02-10', 'avatar' => 'EJ', 'country' => 'US', 'score' => 88],
    ['id' => 2, 'name' => 'Michael Chen', 'email' => 'michael.c@example.com', 'course' => 'Python for Data Science & ML', 'progress' => 45, 'last_activity' => '2026-06-23 11:15:00', 'enrolled' => '2026-03-05', 'avatar' => 'MC', 'country' => 'SG', 'score' => 72],
    ['id' => 3, 'name' => 'Sarah Williams', 'email' => 'sarah.w@example.com', 'course' => 'UI/UX Design Masterclass', 'progress' => 90, 'last_activity' => '2026-06-22 16:45:00', 'enrolled' => '2026-01-20', 'avatar' => 'SW', 'country' => 'UK', 'score' => 95],
    ['id' => 4, 'name' => 'David Brown', 'email' => 'david.b@example.com', 'course' => 'Complete Web Development Bootcamp', 'progress' => 20, 'last_activity' => '2026-06-21 09:00:00', 'enrolled' => '2026-05-01', 'avatar' => 'DB', 'country' => 'US', 'score' => 65],
    ['id' => 5, 'name' => 'Lisa Anderson', 'email' => 'lisa.a@example.com', 'course' => 'Advanced React & Next.js', 'progress' => 60, 'last_activity' => '2026-06-22 13:20:00', 'enrolled' => '2026-04-15', 'avatar' => 'LA', 'country' => 'AU', 'score' => 80],
    ['id' => 6, 'name' => 'James Wilson', 'email' => 'james.w@example.com', 'course' => 'Mobile App Development with Flutter', 'progress' => 35, 'last_activity' => '2026-06-20 10:30:00', 'enrolled' => '2026-04-28', 'avatar' => 'JW', 'country' => 'CA', 'score' => 70],
    ['id' => 7, 'name' => 'Maria Garcia', 'email' => 'maria.g@example.com', 'course' => 'Python for Data Science & ML', 'progress' => 80, 'last_activity' => '2026-06-23 08:45:00', 'enrolled' => '2026-02-22', 'avatar' => 'MG', 'country' => 'ES', 'score' => 91],
    ['id' => 8, 'name' => 'Alex Turner', 'email' => 'alex.t@example.com', 'course' => 'UI/UX Design Masterclass', 'progress' => 15, 'last_activity' => '2026-06-18 15:00:00', 'enrolled' => '2026-05-20', 'avatar' => 'AT', 'country' => 'UK', 'score' => 58],
];

$courses = array_unique(array_column($students, 'course'));
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }

.student-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.85rem; flex-shrink: 0;
}

.modal-detail-label { font-size: 0.85rem; color: var(--gray-500); margin-bottom: 0.2rem; }
.modal-detail-value { font-weight: 600; color: var(--gray-800); }
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Students</h4>
                    <p class="text-muted mb-0">Manage and track your enrolled students.</p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="d-flex gap-2 flex-wrap">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search students..." id="searchInput" onkeyup="filterStudents()">
                    </div>
                    <select class="form-select" style="width: auto;" id="courseFilter" onchange="filterStudents()">
                        <option value="">All Courses</option>
                        <?php foreach ($courses as $c): ?>
                        <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select class="form-select" style="width: auto;" id="progressFilter" onchange="filterStudents()">
                        <option value="">All Progress</option>
                        <option value="high">High (75-100%)</option>
                        <option value="medium">Medium (25-75%)</option>
                        <option value="low">Low (0-25%)</option>
                    </select>
                </div>
                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-download me-1"></i>Export CSV</button>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0" id="studentsTable">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Progress</th>
                                    <th>Last Activity</th>
                                    <th>Enrolled</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($students) > 0): ?>
                                <?php foreach ($students as $s): 
                                    $pct = $s['progress'];
                                    $barColor = $pct >= 75 ? 'var(--success)' : ($pct >= 25 ? 'var(--warning)' : 'var(--danger)');
                                ?>
                                <tr class="student-row">
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="student-avatar" style="background: var(--primary-bg); color: var(--primary);"><?php echo $s['avatar']; ?></div>
                                            <div>
                                                <a href="#" class="fw-semibold" style="color: var(--gray-800); font-size: 0.9rem;" onclick="showStudent(<?php echo $s['id']; ?>); return false;"><?php echo htmlspecialchars($s['name']); ?></a>
                                                <small class="d-block text-muted"><?php echo htmlspecialchars($s['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><small><?php echo htmlspecialchars($s['course']); ?></small></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2" style="min-width: 120px;">
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar" style="width: <?php echo $pct; ?>%; background: <?php echo $barColor; ?>;"></div>
                                            </div>
                                            <small class="fw-semibold" style="color: <?php echo $barColor; ?>;"><?php echo $pct; ?>%</small>
                                        </div>
                                    </td>
                                    <td><small class="text-muted"><?php echo date('M d, g:i A', strtotime($s['last_activity'])); ?></small></td>
                                    <td><small class="text-muted"><?php echo date('M d, Y', strtotime($s['enrolled'])); ?></small></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1" onclick="showStudent(<?php echo $s['id']; ?>)" title="View Details"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-info me-1" title="Message"><i class="fas fa-envelope"></i></button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Assign Quiz"><i class="fas fa-tasks"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="empty-state py-3">
                                            <i class="fas fa-users"></i>
                                            <h5>No students enrolled</h5>
                                            <p>Students will appear here once they enroll in your courses.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if (count($students) > 0): ?>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Showing <?php echo count($students); ?> student<?php echo count($students) !== 1 ? 's' : ''; ?></small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Student Details Modal -->
<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-graduate me-2" style="color: var(--primary);"></i>Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div id="modalAvatar" class="student-avatar" style="width: 60px; height: 60px; font-size: 1.3rem; background: var(--primary-bg); color: var(--primary);"></div>
                    <div>
                        <h5 class="fw-bold mb-1" id="modalName"></h5>
                        <p class="text-muted mb-0" id="modalEmail"></p>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="modal-detail-label">Course</div>
                        <div class="modal-detail-value" id="modalCourse"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="modal-detail-label">Progress</div>
                        <div class="modal-detail-value" id="modalProgress"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="modal-detail-label">Enrolled</div>
                        <div class="modal-detail-value" id="modalEnrolled"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="modal-detail-label">Avg. Score</div>
                        <div class="modal-detail-value" id="modalScore"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="modal-detail-label mb-1">Progress Bar</label>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" id="modalProgressBar" style="width: 0%;"></div>
                    </div>
                </div>
                <h6 class="fw-bold mt-4 mb-2">Recent Activity</h6>
                <div id="modalActivity">
                    <div class="activity-item"><div class="activity-info"><p>Completed Module 4 - Advanced Concepts</p><small>3 days ago</small></div></div>
                    <div class="activity-item"><div class="activity-info"><p>Scored 85% on Module 4 Quiz</p><small>5 days ago</small></div></div>
                    <div class="activity-item"><div class="activity-info"><p>Started Module 5 - Final Project</p><small>1 week ago</small></div></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary"><i class="fas fa-envelope me-1"></i>Send Message</button>
                <button class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
const studentData = <?php echo json_encode($students); ?>;

function showStudent(id) {
    const s = studentData.find(st => st.id === id);
    if (!s) return;
    document.getElementById('modalAvatar').textContent = s.avatar;
    document.getElementById('modalName').textContent = s.name;
    document.getElementById('modalEmail').textContent = s.email;
    document.getElementById('modalCourse').textContent = s.course;
    document.getElementById('modalProgress').textContent = s.progress + '%';
    document.getElementById('modalEnrolled').textContent = s.enrolled;
    document.getElementById('modalScore').textContent = s.score + '%';
    const bar = document.getElementById('modalProgressBar');
    bar.style.width = s.progress + '%';
    bar.style.background = s.progress >= 75 ? 'var(--success)' : (s.progress >= 25 ? 'var(--warning)' : 'var(--danger)');
    new bootstrap.Modal(document.getElementById('studentModal')).show();
}

function filterStudents() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const course = document.getElementById('courseFilter').value;
    const progress = document.getElementById('progressFilter').value;
    const rows = document.querySelectorAll('.student-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const courseCell = row.querySelector('td:nth-child(2)')?.textContent || '';
        const progressCell = row.querySelector('td:nth-child(3) .fw-semibold')?.textContent || '0';
        const pct = parseInt(progressCell);
        let show = text.includes(search) && (!course || courseCell.includes(course));
        if (show && progress) {
            if (progress === 'high' && pct < 75) show = false;
            else if (progress === 'medium' && (pct < 25 || pct > 75)) show = false;
            else if (progress === 'low' && pct > 25) show = false;
        }
        row.style.display = show ? '' : 'none';
    });
}
</script>
<?php require_once '../includes/footer.php'; ?>
