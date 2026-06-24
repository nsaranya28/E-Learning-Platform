<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Manage Categories';
require_once '../includes/header.php';

$categories = [
    ['id' => 1, 'name' => 'Web Development', 'slug' => 'web-development', 'icon' => 'fa-code', 'parent' => null, 'status' => 'active', 'courses' => 85],
    ['id' => 2, 'name' => 'Data Science', 'slug' => 'data-science', 'icon' => 'fa-chart-bar', 'parent' => null, 'status' => 'active', 'courses' => 62],
    ['id' => 3, 'name' => 'Mobile Development', 'slug' => 'mobile-development', 'icon' => 'fa-mobile-alt', 'parent' => null, 'status' => 'active', 'courses' => 41],
    ['id' => 4, 'name' => 'DevOps & Cloud', 'slug' => 'devops-cloud', 'icon' => 'fa-cloud', 'parent' => null, 'status' => 'active', 'courses' => 38],
    ['id' => 5, 'name' => 'UI/UX Design', 'slug' => 'ui-ux-design', 'icon' => 'fa-paint-brush', 'parent' => null, 'status' => 'active', 'courses' => 46],
    ['id' => 6, 'name' => 'Business', 'slug' => 'business', 'icon' => 'fa-briefcase', 'parent' => null, 'status' => 'active', 'courses' => 29],
    ['id' => 7, 'name' => 'Cybersecurity', 'slug' => 'cybersecurity', 'icon' => 'fa-shield-alt', 'parent' => null, 'status' => 'active', 'courses' => 22],
    ['id' => 8, 'name' => 'React JS', 'slug' => 'react-js', 'icon' => 'fa-react', 'parent' => 1, 'status' => 'active', 'courses' => 18],
    ['id' => 9, 'name' => 'Python', 'slug' => 'python', 'icon' => 'fa-python', 'parent' => 2, 'status' => 'active', 'courses' => 15],
    ['id' => 10, 'name' => 'Machine Learning', 'slug' => 'machine-learning', 'icon' => 'fa-brain', 'parent' => 2, 'status' => 'inactive', 'courses' => 8],
];

$iconOptions = ['fa-code', 'fa-chart-bar', 'fa-mobile-alt', 'fa-cloud', 'fa-paint-brush', 'fa-briefcase', 'fa-shield-alt', 'fa-react', 'fa-python', 'fa-brain', 'fa-database', 'fa-server', 'fa-robot', 'fa-palette', 'fa-camera', 'fa-music', 'fa-book', 'fa-language', 'fa-calculator', 'fa-flask'];
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }
.icon-picker-grid {
    display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px; margin-top: 8px;
}
.icon-picker-item {
    width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
    border: 2px solid var(--gray-200); border-radius: var(--radius); cursor: pointer;
    font-size: 1.2rem; color: var(--gray-500); transition: var(--transition);
}
.icon-picker-item:hover, .icon-picker-item.selected {
    border-color: var(--primary); color: var(--primary); background: var(--primary-bg);
}
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Manage Categories</h4>
                    <p class="text-muted mb-0">Organize courses with categories and subcategories.</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fas fa-plus me-2"></i>Add Category</button>
            </div>

            <div class="table-custom">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Icon</th>
                                <th>Parent</th>
                                <th>Courses</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($categories) > 0): ?>
                                <?php foreach ($categories as $cat):
                                    $parentName = '-';
                                    if ($cat['parent']) {
                                        foreach ($categories as $p) {
                                            if ($p['id'] === $cat['parent']) { $parentName = $p['name']; break; }
                                        }
                                    }
                                ?>
                                <tr>
                                    <td><span class="text-muted">#<?php echo $cat['id']; ?></span></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($cat['name']); ?></td>
                                    <td><code><?php echo $cat['slug']; ?></code></td>
                                    <td><i class="fas <?php echo $cat['icon']; ?>" style="color: var(--primary); font-size: 1.1rem;"></i></td>
                                    <td><small class="text-muted"><?php echo $parentName; ?></small></td>
                                    <td><span class="badge badge-primary"><?php echo $cat['courses']; ?></span></td>
                                    <td>
                                        <span class="badge <?php echo $cat['status'] === 'active' ? 'badge-success' : 'badge-secondary'; ?>">
                                            <?php echo ucfirst($cat['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary btn-icon" title="Edit" onclick="editCategory(<?php echo $cat['id']; ?>, '<?php echo htmlspecialchars($cat['name'], ENT_QUOTES); ?>', '<?php echo $cat['slug']; ?>', '<?php echo $cat['icon']; ?>', '<?php echo $cat['parent'] ?? ''; ?>', '<?php echo $cat['status']; ?>')"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Delete" onclick="confirmDelete(<?php echo $cat['id']; ?>, '<?php echo htmlspecialchars($cat['name'], ENT_QUOTES); ?>')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center py-4 text-muted">No categories found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-tag me-2" style="color: var(--primary);"></i>Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" placeholder="e.g. Artificial Intelligence" id="catName" onkeyup="document.getElementById('catSlug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'')" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" id="catSlug" placeholder="auto-generated">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon</label>
                        <input type="hidden" id="catIcon" value="fa-folder">
                        <div class="icon-picker-grid" id="iconPicker">
                            <?php foreach ($iconOptions as $icon): ?>
                            <div class="icon-picker-item" data-icon="<?php echo $icon; ?>" onclick="selectIcon(this, '<?php echo $icon; ?>')"><i class="fas <?php echo $icon; ?>"></i></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select class="form-select" id="catParent">
                            <option value="">None (Top Level)</option>
                            <?php foreach ($categories as $p): if ($p['parent'] === null): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                            <?php endif; endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="catStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2" style="color: var(--primary);"></i>Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="editCatId">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="editCatName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" id="editCatSlug">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon</label>
                        <input type="hidden" id="editCatIcon">
                        <div class="icon-picker-grid" id="editIconPicker">
                            <?php foreach ($iconOptions as $icon): ?>
                            <div class="icon-picker-item" data-icon="<?php echo $icon; ?>" onclick="selectEditIcon(this, '<?php echo $icon; ?>')"><i class="fas <?php echo $icon; ?>"></i></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select class="form-select" id="editCatParent">
                            <option value="">None (Top Level)</option>
                            <?php foreach ($categories as $p): if ($p['parent'] === null): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                            <?php endif; endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="editCatStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let selectedIcon = 'fa-folder';
function selectIcon(el, icon) {
    document.querySelectorAll('#iconPicker .icon-picker-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    selectedIcon = icon;
    document.getElementById('catIcon').value = icon;
}
function selectEditIcon(el, icon) {
    document.querySelectorAll('#editIconPicker .icon-picker-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('editCatIcon').value = icon;
}
function editCategory(id, name, slug, icon, parent, status) {
    document.getElementById('editCatId').value = id;
    document.getElementById('editCatName').value = name;
    document.getElementById('editCatSlug').value = slug;
    document.getElementById('editCatIcon').value = icon;
    document.getElementById('editCatParent').value = parent;
    document.getElementById('editCatStatus').value = status;
    document.querySelectorAll('#editIconPicker .icon-picker-item').forEach(i => {
        i.classList.toggle('selected', i.dataset.icon === icon);
    });
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}
function confirmDelete(id, name) {
    if (confirm('Delete "' + name + '"? Child categories will be unlinked.')) {
        alert('Category deleted successfully!');
    }
}
</script>
<?php require_once '../includes/footer.php'; ?>
