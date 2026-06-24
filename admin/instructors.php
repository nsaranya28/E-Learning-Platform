<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Manage Instructors';
require_once '../includes/header.php';

$statusFilter = $_GET['status'] ?? 'all';

$instructors = [
    ['id' => 201, 'name' => 'Dr. Sarah Johnson', 'email' => 'sarah.j@example.com', 'qualification' => 'PhD Computer Science', 'courses' => 8, 'students' => 1240, 'status' => 'approved', 'avatar' => 'SJ', 'bio' => 'Expert in AI and Machine Learning with 15+ years of teaching experience.', 'specialization' => 'Data Science & AI'],
    ['id' => 202, 'name' => 'Prof. Michael Chen', 'email' => 'michael.c@example.com', 'qualification' => 'MSc Software Engineering', 'courses' => 12, 'students' => 2100, 'status' => 'approved', 'avatar' => 'MC', 'bio' => 'Full-stack developer and educator passionate about web technologies.', 'specialization' => 'Web Development'],
    ['id' => 203, 'name' => 'Emily Roberts', 'email' => 'emily.r@example.com', 'qualification' => 'BFA Graphic Design', 'courses' => 5, 'students' => 890, 'status' => 'approved', 'avatar' => 'ER', 'bio' => 'Award-winning UI/UX designer with experience at top tech companies.', 'specialization' => 'UI/UX Design'],
    ['id' => 204, 'name' => 'James Wilson', 'email' => 'james.w@example.com', 'qualification' => 'PhD Mathematics', 'courses' => 3, 'students' => 450, 'status' => 'pending', 'avatar' => 'JW', 'bio' => 'Mathematics professor specializing in statistical analysis and ML foundations.', 'specialization' => 'Mathematics & Statistics'],
    ['id' => 205, 'name' => 'Lisa Anderson', 'email' => 'lisa.a@example.com', 'qualification' => 'MBA, PMP', 'courses' => 6, 'students' => 1560, 'status' => 'approved', 'avatar' => 'LA', 'bio' => 'Business strategist and project management professional.', 'specialization' => 'Business & Management'],
    ['id' => 206, 'name' => 'Robert Taylor', 'email' => 'robert.t@example.com', 'qualification' => 'BSc Computer Science', 'courses' => 2, 'students' => 120, 'status' => 'pending', 'avatar' => 'RT', 'bio' => 'Mobile developer with focus on Flutter and React Native.', 'specialization' => 'Mobile Development'],
    ['id' => 207, 'name' => 'Maria Garcia', 'email' => 'maria.g@example.com', 'qualification' => 'PhD Linguistics', 'courses' => 4, 'students' => 670, 'status' => 'rejected', 'avatar' => 'MG', 'bio' => 'Language acquisition researcher.', 'specialization' => 'Language Learning'],
    ['id' => 208, 'name' => 'David Park', 'email' => 'david.p@example.com', 'qualification' => 'MSc Cybersecurity', 'courses' => 0, 'students' => 0, 'status' => 'suspended', 'avatar' => 'DP', 'bio' => 'Cybersecurity professional.', 'specialization' => 'Cybersecurity'],
];

if ($statusFilter !== 'all') {
    $instructors = array_filter($instructors, fn($i) => $i['status'] === $statusFilter);
}
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }
.instructor-avatar-lg { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; }
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Manage Instructors</h4>
                    <p class="text-muted mb-0">Review, approve, and manage instructor accounts.</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInstructorModal"><i class="fas fa-plus me-2"></i>Add Instructor</button>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="?status=all" class="btn btn-sm filter-btn <?php echo $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary'; ?>">All</a>
                        <a href="?status=approved" class="btn btn-sm filter-btn <?php echo $statusFilter === 'approved' ? 'btn-success' : 'btn-outline-secondary'; ?>">Approved</a>
                        <a href="?status=pending" class="btn btn-sm filter-btn <?php echo $statusFilter === 'pending' ? 'btn-warning' : 'btn-outline-secondary'; ?>">Pending</a>
                        <a href="?status=rejected" class="btn btn-sm filter-btn <?php echo $statusFilter === 'rejected' ? 'btn-danger' : 'btn-outline-secondary'; ?>">Rejected</a>
                        <a href="?status=suspended" class="btn btn-sm filter-btn <?php echo $statusFilter === 'suspended' ? 'btn-secondary' : 'btn-outline-secondary'; ?>">Suspended</a>
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
                                <th>Qualification</th>
                                <th>Courses</th>
                                <th>Students</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($instructors) > 0): ?>
                                <?php foreach ($instructors as $inst): ?>
                                <tr>
                                    <td><span class="text-muted">#<?php echo $inst['id']; ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="width:34px;height:34px;border-radius:50%;background:var(--primary-bg);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;"><?php echo $inst['avatar']; ?></span>
                                            <span class="fw-semibold"><?php echo htmlspecialchars($inst['name']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($inst['email']); ?></td>
                                    <td><small><?php echo htmlspecialchars($inst['qualification']); ?></small></td>
                                    <td><span class="badge badge-primary"><?php echo $inst['courses']; ?></span></td>
                                    <td><?php echo number_format($inst['students']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $inst['status'] === 'approved' ? 'badge-success' : ($inst['status'] === 'pending' ? 'badge-warning' : ($inst['status'] === 'rejected' ? 'badge-danger' : 'badge-secondary')); ?>">
                                            <?php echo ucfirst($inst['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-info btn-icon" title="View Details" onclick="viewInstructor(<?php echo $inst['id']; ?>, '<?php echo htmlspecialchars($inst['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($inst['bio'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($inst['specialization'], ENT_QUOTES); ?>', <?php echo $inst['courses']; ?>, <?php echo $inst['students']; ?>)"><i class="fas fa-eye"></i></button>
                                            <?php if ($inst['status'] === 'pending'): ?>
                                            <button class="btn btn-sm btn-outline-success btn-icon" title="Approve" onclick="confirmAction('Approve <?php echo htmlspecialchars($inst['name'], ENT_QUOTES); ?>?')"><i class="fas fa-check"></i></button>
                                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Reject" onclick="confirmAction('Reject <?php echo htmlspecialchars($inst['name'], ENT_QUOTES); ?>?')"><i class="fas fa-times"></i></button>
                                            <?php elseif ($inst['status'] === 'approved'): ?>
                                            <button class="btn btn-sm btn-outline-warning btn-icon" title="Suspend" onclick="confirmAction('Suspend <?php echo htmlspecialchars($inst['name'], ENT_QUOTES); ?>?')"><i class="fas fa-pause"></i></button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-primary btn-icon" title="Edit"><i class="fas fa-edit"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center py-4 text-muted">No instructors found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Instructor Modal -->
<div class="modal fade" id="addInstructorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-chalkboard-teacher me-2" style="color: var(--primary);"></i>Add New Instructor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" placeholder="Full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" placeholder="Email address" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Qualification</label>
                        <input type="text" class="form-control" placeholder="e.g. PhD, MSc, BSc" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Specialization</label>
                        <input type="text" class="form-control" placeholder="e.g. Data Science, Web Development">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" rows="3" placeholder="Brief biography"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" placeholder="Set password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Instructor Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Instructor Modal -->
<div class="modal fade" id="viewInstructorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-user me-2" style="color: var(--primary);"></i>Instructor Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="viewInstAvatar" src="" alt="" class="instructor-avatar-lg mb-3">
                <h5 id="viewInstName" class="fw-bold"></h5>
                <p id="viewInstSpecialization" class="text-muted mb-2"></p>
                <p id="viewInstBio" class="small"></p>
                <hr>
                <div class="row g-3">
                    <div class="col-6">
                        <h6 class="fw-bold text-primary" id="viewInstCourses">0</h6>
                        <small class="text-muted">Courses</small>
                    </div>
                    <div class="col-6">
                        <h6 class="fw-bold text-success" id="viewInstStudents">0</h6>
                        <small class="text-muted">Students</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewInstructor(id, name, bio, specialization, courses, students) {
    document.getElementById('viewInstName').textContent = name;
    document.getElementById('viewInstBio').textContent = bio;
    document.getElementById('viewInstSpecialization').textContent = specialization;
    document.getElementById('viewInstCourses').textContent = courses;
    document.getElementById('viewInstStudents').textContent = students.toLocaleString();
    document.getElementById('viewInstAvatar').src = 'https://placehold.co/160x160/2563eb/ffffff?text=' + name.charAt(0);
    new bootstrap.Modal(document.getElementById('viewInstructorModal')).show();
}
function confirmAction(msg) {
    if (confirm(msg)) { alert('Action performed successfully!'); }
}
</script>
<?php require_once '../includes/footer.php'; ?>
