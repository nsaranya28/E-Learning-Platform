<?php
session_start();
if (!isset($_SESSION['instructor_id'])) { header('Location: ../instructor-login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'My Courses';
require_once '../includes/header.php';

$statusFilter = $_GET['status'] ?? 'all';

$courses = [
    ['id' => 1, 'title' => 'Complete Web Development Bootcamp', 'category' => 'Web Development', 'level' => 'Beginner', 'price' => 89.99, 'students' => 234, 'rating' => 4.8, 'status' => 'published', 'created' => '2026-01-15', 'image' => 'https://placehold.co/600x400/2563eb/ffffff?text=Web+Dev'],
    ['id' => 2, 'title' => 'Python for Data Science & Machine Learning', 'category' => 'Data Science', 'level' => 'Intermediate', 'price' => 79.99, 'students' => 189, 'rating' => 4.7, 'status' => 'published', 'created' => '2026-02-20', 'image' => 'https://placehold.co/600x400/10b981/ffffff?text=Python+DS'],
    ['id' => 3, 'title' => 'UI/UX Design Masterclass', 'category' => 'Design', 'level' => 'All Levels', 'price' => 69.99, 'students' => 156, 'rating' => 4.9, 'status' => 'published', 'created' => '2026-03-10', 'image' => 'https://placehold.co/600x400/f59e0b/ffffff?text=UI%2FUX'],
    ['id' => 4, 'title' => 'Advanced React & Next.js Patterns', 'category' => 'Web Development', 'level' => 'Advanced', 'price' => 94.99, 'students' => 98, 'rating' => 4.6, 'status' => 'draft', 'created' => '2026-04-05', 'image' => 'https://placehold.co/600x400/ef4444/ffffff?text=React'],
    ['id' => 5, 'title' => 'Mobile App Development with Flutter & Dart', 'category' => 'Mobile Development', 'level' => 'Intermediate', 'price' => 84.99, 'students' => 67, 'rating' => 4.5, 'status' => 'published', 'created' => '2026-04-18', 'image' => 'https://placehold.co/600x400/8b5cf6/ffffff?text=Flutter'],
    ['id' => 6, 'title' => 'AWS Cloud Architecture Fundamentals', 'category' => 'DevOps & Cloud', 'level' => 'Advanced', 'price' => 99.99, 'students' => 45, 'rating' => 4.4, 'status' => 'draft', 'created' => '2026-05-01', 'image' => 'https://placehold.co/600x400/ec4899/ffffff?text=AWS'],
    ['id' => 7, 'title' => 'Digital Marketing Strategy 2026', 'category' => 'Marketing', 'level' => 'Beginner', 'price' => 49.99, 'students' => 112, 'rating' => 4.3, 'status' => 'archived', 'created' => '2025-11-10', 'image' => 'https://placehold.co/600x400/14b8a6/ffffff?text=Marketing'],
    ['id' => 8, 'title' => 'Introduction to Machine Learning with TensorFlow', 'category' => 'Data Science', 'level' => 'Beginner', 'price' => 74.99, 'students' => 203, 'rating' => 4.8, 'status' => 'published', 'created' => '2026-01-28', 'image' => 'https://placehold.co/600x400/3b82f6/ffffff?text=ML'],
];

if ($statusFilter !== 'all') {
    $courses = array_filter($courses, fn($c) => $c['status'] === $statusFilter);
}
$filteredCount = count($courses);
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }

.status-tabs {
    display: flex; gap: 0.5rem; flex-wrap: wrap;
}
.status-tab {
    padding: 0.5rem 1.2rem; border-radius: 20px; font-size: 0.85rem;
    font-weight: 600; cursor: pointer; border: 2px solid var(--gray-200);
    color: var(--gray-600); background: var(--white); transition: var(--transition);
    text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;
}
.status-tab:hover { border-color: var(--primary); color: var(--primary); }
.status-tab.active { border-color: var(--primary); background: var(--primary-bg); color: var(--primary); }
.status-tab .count {
    background: var(--gray-100); padding: 0.1rem 0.5rem; border-radius: 10px;
    font-size: 0.75rem;
}
.status-tab.active .count { background: var(--primary); color: white; }

.course-thumb-sm {
    width: 48px; height: 36px; border-radius: var(--radius-sm); object-fit: cover;
    flex-shrink: 0;
}
.actions-dropdown .btn-icon {
    width: 36px; height: 36px; border-radius: var(--radius);
}
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>My Courses</h4>
                    <p class="text-muted mb-0">Manage and organize your courses.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="create-course.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Create Course</a>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="status-tabs">
                    <a href="?status=all" class="status-tab <?php echo $statusFilter === 'all' ? 'active' : ''; ?>">
                        All <span class="count"><?php echo $filteredCount; ?></span>
                    </a>
                    <a href="?status=published" class="status-tab <?php echo $statusFilter === 'published' ? 'active' : ''; ?>">
                        <i class="fas fa-check-circle" style="color: var(--success);"></i> Published
                    </a>
                    <a href="?status=draft" class="status-tab <?php echo $statusFilter === 'draft' ? 'active' : ''; ?>">
                        <i class="fas fa-pen" style="color: var(--warning);"></i> Draft
                    </a>
                    <a href="?status=archived" class="status-tab <?php echo $statusFilter === 'archived' ? 'active' : ''; ?>">
                        <i class="fas fa-archive"></i> Archived
                    </a>
                </div>
                <div class="d-flex gap-2">
                    <div class="input-group" style="max-width: 260px;">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search courses..." id="searchInput" onkeyup="filterTable()">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0" id="coursesTable">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Category</th>
                                    <th>Level</th>
                                    <th>Price</th>
                                    <th>Students</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($courses) > 0): ?>
                                <?php foreach ($courses as $course): ?>
                                <tr class="course-row">
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="<?php echo $course['image']; ?>" alt="" class="course-thumb-sm">
                                            <div>
                                                <a href="manage-courses.php?id=<?php echo $course['id']; ?>" class="fw-semibold" style="color: var(--gray-800); font-size: 0.9rem;"><?php echo htmlspecialchars($course['title']); ?></a>
                                                <small class="d-block text-muted">Created: <?php echo date('M d, Y', strtotime($course['created'])); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo $course['category']; ?></td>
                                    <td><span class="badge badge-primary"><?php echo $course['level']; ?></span></td>
                                    <td class="fw-semibold">$<?php echo number_format($course['price'], 2); ?></td>
                                    <td><?php echo $course['students']; ?></td>
                                    <td><span class="text-warning"><i class="fas fa-star me-1"></i><?php echo $course['rating']; ?></span></td>
                                    <td>
                                        <span class="badge <?php
                                            echo $course['status'] === 'published' ? 'badge-success' : ($course['status'] === 'draft' ? 'badge-warning' : 'badge-danger');
                                        ?>"><?php echo ucfirst($course['status']); ?></span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                                <?php if ($course['status'] === 'published'): ?>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-pause me-2" style="color: var(--warning);"></i>Unpublish</a></li>
                                                <?php elseif ($course['status'] === 'draft'): ?>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-check me-2" style="color: var(--success);"></i>Publish</a></li>
                                                <?php endif; ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="empty-state py-4">
                                            <i class="fas fa-book-open"></i>
                                            <h5>No courses found</h5>
                                            <p>No courses match the current filter. Try a different status.</p>
                                            <a href="create-course.php" class="btn btn-primary">Create Your First Course</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if (count($courses) > 0): ?>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Showing <?php echo count($courses); ?> course<?php echo count($courses) !== 1 ? 's' : ''; ?></small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('.course-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}
</script>
<?php require_once '../includes/footer.php'; ?>
