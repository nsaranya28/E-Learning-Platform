<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Manage Students';
require_once '../includes/header.php';

$statusFilter = $_GET['status'] ?? 'all';
$searchQuery = $_GET['search'] ?? '';

$students = [
    ['id' => 1001, 'name' => 'Alice Johnson', 'email' => 'alice.j@example.com', 'phone' => '+1 555-0101', 'courses' => 4, 'status' => 'active', 'joined' => '2026-01-15', 'avatar' => 'AJ'],
    ['id' => 1002, 'name' => 'Bob Smith', 'email' => 'bob.smith@example.com', 'phone' => '+1 555-0102', 'courses' => 2, 'status' => 'active', 'joined' => '2026-02-20', 'avatar' => 'BS'],
    ['id' => 1003, 'name' => 'Carol White', 'email' => 'carol.w@example.com', 'phone' => '+1 555-0103', 'courses' => 6, 'status' => 'active', 'joined' => '2025-11-10', 'avatar' => 'CW'],
    ['id' => 1004, 'name' => 'David Lee', 'email' => 'david.lee@example.com', 'phone' => '+1 555-0104', 'courses' => 1, 'status' => 'inactive', 'joined' => '2026-03-05', 'avatar' => 'DL'],
    ['id' => 1005, 'name' => 'Emma Brown', 'email' => 'emma.b@example.com', 'phone' => '+1 555-0105', 'courses' => 3, 'status' => 'active', 'joined' => '2026-04-18', 'avatar' => 'EB'],
    ['id' => 1006, 'name' => 'Frank Wilson', 'email' => 'frank.w@example.com', 'phone' => '+1 555-0106', 'courses' => 0, 'status' => 'banned', 'joined' => '2025-09-12', 'avatar' => 'FW'],
    ['id' => 1007, 'name' => 'Grace Kim', 'email' => 'grace.k@example.com', 'phone' => '+1 555-0107', 'courses' => 5, 'status' => 'active', 'joined' => '2026-05-01', 'avatar' => 'GK'],
    ['id' => 1008, 'name' => 'Henry Davis', 'email' => 'henry.d@example.com', 'phone' => '+1 555-0108', 'courses' => 2, 'status' => 'inactive', 'joined' => '2026-05-22', 'avatar' => 'HD'],
];

if ($statusFilter !== 'all') {
    $students = array_filter($students, fn($s) => $s['status'] === $statusFilter);
}
if ($searchQuery) {
    $students = array_filter($students, fn($s) => stripos($s['name'], $searchQuery) !== false || stripos($s['email'], $searchQuery) !== false);
}
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }
.filter-btn { font-size: 0.85rem; padding: 0.35rem 0.8rem; border-radius: 20px; }
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Manage Students</h4>
                    <p class="text-muted mb-0">View and manage all registered students.</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal"><i class="fas fa-plus me-2"></i>Add Student</button>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <form method="GET" class="d-flex gap-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" class="form-control" name="search" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                                </div>
                                <button class="btn btn-primary" type="submit">Search</button>
                            </form>
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex gap-2 align-items-center justify-content-md-end flex-wrap">
                                <span class="text-muted small me-2">Filter:</span>
                                <a href="?status=all" class="btn btn-sm filter-btn <?php echo $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary'; ?>">All</a>
                                <a href="?status=active" class="btn btn-sm filter-btn <?php echo $statusFilter === 'active' ? 'btn-success' : 'btn-outline-secondary'; ?>">Active</a>
                                <a href="?status=inactive" class="btn btn-sm filter-btn <?php echo $statusFilter === 'inactive' ? 'btn-warning' : 'btn-outline-secondary'; ?>">Inactive</a>
                                <a href="?status=banned" class="btn btn-sm filter-btn <?php echo $statusFilter === 'banned' ? 'btn-danger' : 'btn-outline-secondary'; ?>">Banned</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-custom">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Courses</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($students) > 0): ?>
                                <?php foreach ($students as $s): ?>
                                <tr>
                                    <td><span class="text-muted">#<?php echo $s['id']; ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="width:34px;height:34px;border-radius:50%;background:var(--primary-bg);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;"><?php echo $s['avatar']; ?></span>
                                            <span class="fw-semibold"><?php echo htmlspecialchars($s['name']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($s['email']); ?></td>
                                    <td><?php echo htmlspecialchars($s['phone']); ?></td>
                                    <td><span class="badge badge-primary"><?php echo $s['courses']; ?> enrolled</span></td>
                                    <td>
                                        <span class="badge <?php echo $s['status'] === 'active' ? 'badge-success' : ($s['status'] === 'inactive' ? 'badge-warning' : 'badge-danger'); ?>">
                                            <?php echo ucfirst($s['status']); ?>
                                        </span>
                                    </td>
                                    <td><small class="text-muted"><?php echo date('M d, Y', strtotime($s['joined'])); ?></small></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary btn-icon" title="Edit" data-bs-toggle="modal" data-bs-target="#editStudentModal" onclick="populateEditStudent(<?php echo $s['id']; ?>, '<?php echo htmlspecialchars($s['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($s['email'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($s['phone'], ENT_QUOTES); ?>', '<?php echo $s['status']; ?>')"><i class="fas fa-edit"></i></button>
                                            <?php if ($s['status'] !== 'banned'): ?>
                                            <button class="btn btn-sm btn-outline-danger btn-icon" title="<?php echo $s['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>" onclick="confirmAction('<?php echo $s['status'] === 'active' ? 'Deactivate' : 'Activate'; ?> student <?php echo htmlspecialchars($s['name'], ENT_QUOTES); ?>?')"><i class="fas <?php echo $s['status'] === 'active' ? 'fa-ban' : 'fa-check-circle'; ?>"></i></button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center py-4 text-muted">No students found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">Showing <?php echo count($students); ?> students</small>
                    <nav><ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul></nav>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-graduate me-2" style="color: var(--primary);"></i>Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" placeholder="Enter email address" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" placeholder="Enter phone">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" placeholder="Set password" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Student Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2" style="color: var(--primary);"></i>Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="editStudentId">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editStudentName">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editStudentEmail">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="editStudentPhone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="editStudentStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="banned">Banned</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Student</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function populateEditStudent(id, name, email, phone, status) {
    document.getElementById('editStudentId').value = id;
    document.getElementById('editStudentName').value = name;
    document.getElementById('editStudentEmail').value = email;
    document.getElementById('editStudentPhone').value = phone;
    document.getElementById('editStudentStatus').value = status;
}
function confirmAction(msg) {
    if (confirm(msg)) { alert('Action performed successfully!'); }
}
</script>
<?php require_once '../includes/footer.php'; ?>
