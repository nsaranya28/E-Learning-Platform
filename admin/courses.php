<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Manage Courses';
require_once '../includes/header.php';

$statusFilter = $_GET['status'] ?? 'all';
$categoryFilter = $_GET['category'] ?? 'all';

$courses = [
    ['id' => 1, 'title' => 'Complete Web Development Bootcamp', 'instructor' => 'Michael Chen', 'category' => 'Web Development', 'price' => 89.99, 'students' => 234, 'rating' => 4.8, 'status' => 'published', 'featured' => true],
    ['id' => 2, 'title' => 'Python for Data Science & ML', 'instructor' => 'Dr. Sarah Johnson', 'category' => 'Data Science', 'price' => 79.99, 'students' => 189, 'rating' => 4.7, 'status' => 'published', 'featured' => true],
    ['id' => 3, 'title' => 'UI/UX Design Masterclass', 'instructor' => 'Emily Roberts', 'category' => 'Design', 'price' => 69.99, 'students' => 156, 'rating' => 4.9, 'status' => 'published', 'featured' => false],
    ['id' => 4, 'title' => 'Advanced React & Next.js Patterns', 'instructor' => 'Michael Chen', 'category' => 'Web Development', 'price' => 94.99, 'students' => 98, 'rating' => 4.6, 'status' => 'draft', 'featured' => false],
    ['id' => 5, 'title' => 'Mobile App Development with Flutter', 'instructor' => 'Robert Taylor', 'category' => 'Mobile Development', 'price' => 84.99, 'students' => 67, 'rating' => 4.5, 'status' => 'published', 'featured' => false],
    ['id' => 6, 'title' => 'Introduction to Cybersecurity', 'instructor' => 'David Park', 'category' => 'Cybersecurity', 'price' => 0, 'students' => 312, 'rating' => 4.3, 'status' => 'published', 'featured' => false],
    ['id' => 7, 'title' => 'Business Management Fundamentals', 'instructor' => 'Lisa Anderson', 'category' => 'Business', 'price' => 59.99, 'students' => 145, 'rating' => 4.4, 'status' => 'archived', 'featured' => false],
    ['id' => 8, 'title' => 'Machine Learning A-Z: Hands-On Python', 'instructor' => 'Dr. Sarah Johnson', 'category' => 'Data Science', 'price' => 99.99, 'students' => 210, 'rating' => 4.8, 'status' => 'draft', 'featured' => false],
];

$categories = array_unique(array_column($courses, 'category'));
sort($categories);

if ($statusFilter !== 'all') {
    $courses = array_filter($courses, fn($c) => $c['status'] === $statusFilter);
}
if ($categoryFilter !== 'all') {
    $courses = array_filter($courses, fn($c) => $c['category'] === $categoryFilter);
}
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Manage Courses</h4>
                    <p class="text-muted mb-0">View, edit, and manage all platform courses.</p>
                </div>
                <button class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add Course</button>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control" placeholder="Search courses..." id="courseSearch">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" onchange="window.location='?status='+this.value+'&category=<?php echo $categoryFilter; ?>'">
                                <option value="all" <?php echo $statusFilter === 'all' ? 'selected' : ''; ?>>All Statuses</option>
                                <option value="published" <?php echo $statusFilter === 'published' ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo $statusFilter === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="archived" <?php echo $statusFilter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" onchange="window.location='?status=<?php echo $statusFilter; ?>&category='+this.value">
                                <option value="all" <?php echo $categoryFilter === 'all' ? 'selected' : ''; ?>>All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo $categoryFilter === $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                                <?php endforeach; ?>
                            </select>
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
                                <th>Title</th>
                                <th>Instructor</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Students</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($courses) > 0): ?>
                                <?php foreach ($courses as $c): ?>
                                <tr>
                                    <td><span class="text-muted">#<?php echo $c['id']; ?></span></td>
                                    <td>
                                        <a href="#" class="fw-semibold" style="color: var(--gray-800);"><?php echo htmlspecialchars($c['title']); ?></a>
                                        <?php if ($c['featured']): ?><span class="badge bg-warning text-dark ms-1">Featured</span><?php endif; ?>
                                    </td>
                                    <td><small><?php echo htmlspecialchars($c['instructor']); ?></small></td>
                                    <td><span class="badge badge-primary"><?php echo $c['category']; ?></span></td>
                                    <td class="fw-bold" style="color: var(--primary);"><?php echo $c['price'] == 0 ? 'Free' : '$' . number_format($c['price'], 2); ?></td>
                                    <td><?php echo $c['students']; ?></td>
                                    <td><span class="text-warning"><i class="fas fa-star me-1"></i><?php echo $c['rating']; ?></span></td>
                                    <td>
                                        <span class="badge <?php echo $c['status'] === 'published' ? 'badge-success' : ($c['status'] === 'draft' ? 'badge-warning' : 'badge-secondary'); ?>">
                                            <?php echo ucfirst($c['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary btn-icon" title="Edit"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-<?php echo $c['featured'] ? 'warning' : 'secondary'; ?> btn-icon" title="<?php echo $c['featured'] ? 'Unfeature' : 'Feature'; ?>" onclick="confirmAction('<?php echo $c['featured'] ? 'Unfeature' : 'Feature'; ?> this course?')"><i class="fas fa-star"></i></button>
                                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Delete" onclick="confirmDelete(<?php echo $c['id']; ?>, '<?php echo htmlspecialchars($c['title'], ENT_QUOTES); ?>')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="9" class="text-center py-4 text-muted">No courses found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">Showing <?php echo count($courses); ?> courses</small>
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

<script>
function confirmAction(msg) {
    if (confirm(msg)) { alert('Action performed successfully!'); }
}
function confirmDelete(id, title) {
    if (confirm('Are you sure you want to delete "' + title + '"? This action cannot be undone.')) {
        alert('Course #' + id + ' deleted successfully!');
    }
}
</script>
<?php require_once '../includes/footer.php'; ?>
