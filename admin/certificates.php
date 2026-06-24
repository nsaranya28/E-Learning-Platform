<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Manage Certificates';
require_once '../includes/header.php';

$searchQuery = $_GET['search'] ?? '';

$certificates = [
    ['id' => 'CRT-A1B2C3D4-1001-1', 'student' => 'Alice Johnson', 'course' => 'Complete Web Development Bootcamp', 'date' => '2026-06-15', 'status' => 'active', 'grade' => 'A'],
    ['id' => 'CRT-E5F6G7H8-1002-2', 'student' => 'Bob Smith', 'course' => 'Python for Data Science & ML', 'date' => '2026-06-10', 'status' => 'active', 'grade' => 'A+'],
    ['id' => 'CRT-I9J0K1L2-1003-3', 'student' => 'Carol White', 'course' => 'UI/UX Design Masterclass', 'date' => '2026-05-28', 'status' => 'active', 'grade' => 'A'],
    ['id' => 'CRT-M3N4O5P6-1004-1', 'student' => 'David Lee', 'course' => 'Complete Web Development Bootcamp', 'date' => '2026-05-20', 'status' => 'revoked', 'grade' => 'B+'],
    ['id' => 'CRT-Q7R8S9T0-1005-6', 'student' => 'Emma Brown', 'course' => 'Introduction to Cybersecurity', 'date' => '2026-05-15', 'status' => 'active', 'grade' => 'A'],
    ['id' => 'CRT-U1V2W3X4-1006-4', 'student' => 'Frank Wilson', 'course' => 'Advanced React & Next.js Patterns', 'date' => '2026-04-30', 'status' => 'active', 'grade' => 'B'],
    ['id' => 'CRT-Y5Z6A7B8-1007-7', 'student' => 'Grace Kim', 'course' => 'Business Management Fundamentals', 'date' => '2026-04-22', 'status' => 'revoked', 'grade' => 'A-'],
    ['id' => 'CRT-C9D0E1F2-1008-2', 'student' => 'Henry Davis', 'course' => 'Python for Data Science & ML', 'date' => '2026-04-10', 'status' => 'active', 'grade' => 'A'],
];

if ($searchQuery) {
    $certificates = array_filter($certificates, fn($c) =>
        stripos($c['id'], $searchQuery) !== false ||
        stripos($c['student'], $searchQuery) !== false ||
        stripos($c['course'], $searchQuery) !== false
    );
}
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }
.preview-certificate {
    max-width: 700px; margin: 0 auto; background: var(--white);
    border: 3px solid var(--primary); padding: 2.5rem; text-align: center;
    position: relative; box-shadow: var(--shadow-xl);
}
.preview-certificate::before {
    content: ''; position: absolute; top: 10px; left: 10px; right: 10px; bottom: 10px;
    border: 1px solid var(--gray-200); pointer-events: none;
}
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Manage Certificates</h4>
                    <p class="text-muted mb-0">View, verify, and revoke issued certificates.</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control" name="search" placeholder="Search by certificate number, student name, or course..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i>Search</button>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <button class="btn btn-outline-secondary" onclick="window.location='certificates.php'"><i class="fas fa-sync-alt me-1"></i>Clear</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-custom">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Certificate #</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Issue Date</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($certificates) > 0): ?>
                                <?php foreach ($certificates as $cert): ?>
                                <tr>
                                    <td><code style="font-size: 0.8rem;"><?php echo htmlspecialchars($cert['id']); ?></code></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($cert['student']); ?></td>
                                    <td><small><?php echo htmlspecialchars($cert['course']); ?></small></td>
                                    <td><small class="text-muted"><?php echo date('M d, Y', strtotime($cert['date'])); ?></small></td>
                                    <td><span class="badge <?php echo $cert['grade'][0] === 'A' ? 'badge-success' : 'badge-primary'; ?>"><?php echo $cert['grade']; ?></span></td>
                                    <td>
                                        <span class="badge <?php echo $cert['status'] === 'active' ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo ucfirst($cert['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-info btn-icon" title="Preview" onclick="previewCertificate('<?php echo htmlspecialchars($cert['id'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($cert['student'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($cert['course'], ENT_QUOTES); ?>', '<?php echo $cert['date']; ?>', '<?php echo $cert['grade']; ?>')"><i class="fas fa-eye"></i></button>
                                            <?php if ($cert['status'] === 'active'): ?>
                                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Revoke" onclick="confirmRevoke('<?php echo htmlspecialchars($cert['id'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($cert['student'], ENT_QUOTES); ?>')"><i class="fas fa-ban"></i></button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center py-4 text-muted">No certificates found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Certificate Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: transparent; border: none;">
            <div class="text-end mb-2">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="preview-certificate">
                <div style="width:80px;height:80px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2.5rem;color:var(--white);">
                    <i class="fas fa-award"></i>
                </div>
                <h2 style="font-weight: 800; color: var(--secondary); margin-bottom: 0.5rem;">Certificate of Completion</h2>
                <p style="color: var(--gray-500); margin-bottom: 2rem;">This is to certify that</p>
                <h3 id="previewStudentName" style="font-size: 1.8rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem; border-bottom: 2px solid var(--gray-200); display: inline-block; padding-bottom: 0.5rem;"></h3>
                <p style="color: var(--gray-500); margin: 1.5rem 0 0.5rem;">has successfully completed the course</p>
                <h5 id="previewCourseName" style="font-weight: 700; color: var(--secondary); margin-bottom: 1.5rem;"></h5>
                <div class="d-flex justify-content-center gap-5 mb-3">
                    <div><small class="text-muted">Grade</small><br><strong id="previewGrade" style="color: var(--primary);"></strong></div>
                    <div><small class="text-muted">Issue Date</small><br><strong id="previewDate" style="color: var(--primary);"></strong></div>
                </div>
                <p style="font-size: 0.8rem; color: var(--gray-400);" id="previewCertId"></p>
            </div>
        </div>
    </div>
</div>

<script>
function previewCertificate(id, student, course, date, grade) {
    document.getElementById('previewStudentName').textContent = student;
    document.getElementById('previewCourseName').textContent = course;
    document.getElementById('previewDate').textContent = new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    document.getElementById('previewGrade').textContent = grade;
    document.getElementById('previewCertId').textContent = 'Certificate #: ' + id;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}
function confirmRevoke(id, student) {
    if (confirm('Are you sure you want to revoke certificate ' + id + ' for ' + student + '?\n\nThis action cannot be undone.')) {
        alert('Certificate revoked successfully!');
    }
}
</script>
<?php require_once '../includes/footer.php'; ?>
