<?php
session_start();
if (!isset($_SESSION['instructor_id'])) { header('Location: ../instructor-login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Create Course';
require_once '../includes/header.php';

$categories = ['Web Development', 'Data Science', 'Mobile Development', 'DevOps & Cloud', 'Design & Creative', 'Business', 'Marketing', 'Photography', 'Music', 'Health & Fitness'];
$levels = ['Beginner', 'Intermediate', 'Advanced', 'All Levels'];
$languages = ['English', 'Spanish', 'French', 'German', 'Arabic', 'Hindi', 'Chinese', 'Japanese'];
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1000px; }

.form-section {
    background: var(--white); border-radius: var(--radius-md); padding: 2rem;
    box-shadow: var(--shadow-sm); margin-bottom: 1.5rem;
}
.form-section h6 {
    font-weight: 700; color: var(--secondary); margin-bottom: 1.5rem;
    padding-bottom: 0.8rem; border-bottom: 2px solid var(--gray-100);
}
.form-section h6 i { color: var(--primary); margin-right: 0.5rem; }

.input-group-text { background: var(--gray-50); border: 2px solid var(--gray-200); border-right: none; }
.input-group .form-control { border-left: none; }
.input-group .form-control:focus { border-left: none; }

.objective-input {
    display: flex; gap: 0.5rem; margin-bottom: 0.5rem;
}
.objective-input .btn {
    padding: 0.7rem 1rem; border-radius: var(--radius); flex-shrink: 0;
}

.preview-card {
    position: sticky; top: 2rem;
}
.preview-card .preview-thumb {
    height: 180px; background: var(--gray-100); border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    color: var(--gray-400); font-size: 0.9rem; overflow: hidden;
}
.preview-card .preview-thumb img { width: 100%; height: 100%; object-fit: cover; }
.preview-card .preview-title { font-weight: 700; font-size: 1.1rem; margin-top: 1rem; }
.preview-card .preview-meta { font-size: 0.85rem; color: var(--gray-500); }
.preview-card .preview-price { font-size: 1.5rem; font-weight: 800; color: var(--primary); }

@media (max-width: 991px) {
    .preview-card { position: static; }
}
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Create New Course</h4>
                    <p class="text-muted mb-0">Fill in the details below to create a new course.</p>
                </div>
            </div>

            <form action="../api/courses/create.php" method="POST" enctype="multipart/form-data" id="createCourseForm" novalidate>
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="form-section">
                            <h6><i class="fas fa-info-circle"></i>Basic Information</h6>
                            <div class="mb-3">
                                <label for="title" class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="e.g., Complete Web Development Bootcamp 2026" required>
                                <div class="invalid-feedback">Please enter a course title.</div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" id="category" name="category_id" required>
                                        <option value="">Select category</option>
                                        <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="level" class="form-label">Level <span class="text-danger">*</span></label>
                                    <select class="form-select" id="level" name="level" required>
                                        <option value="">Select level</option>
                                        <?php foreach ($levels as $lvl): ?>
                                        <option value="<?php echo $lvl; ?>"><?php echo $lvl; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="language" class="form-label">Language</label>
                                    <select class="form-select" id="language" name="language">
                                        <option value="English">English</option>
                                        <?php foreach ($languages as $lang): ?>
                                        <option value="<?php echo $lang; ?>"><?php echo $lang; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" placeholder="89.99" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="discount_price" class="form-label">Discount Price ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="discount_price" name="discount_price" min="0" step="0.01" placeholder="49.99">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="duration" class="form-label">Duration</label>
                                    <input type="text" class="form-control" id="duration" name="duration" placeholder="e.g., 12 hours, 8 weeks">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h6><i class="fas fa-align-left"></i>Description</h6>
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="2" placeholder="Brief summary (max 200 characters)" maxlength="200"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="6" placeholder="Detailed course description..." required></textarea>
                                <div class="invalid-feedback">Please enter a course description.</div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h6><i class="fas fa-list-check"></i>Learning Objectives</h6>
                            <p class="text-muted small mb-3">Add the key learning outcomes for students who take this course.</p>
                            <div id="objectivesContainer">
                                <div class="objective-input">
                                    <input type="text" class="form-control" name="objectives[]" placeholder="e.g., Build responsive websites using HTML & CSS">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="objective-input">
                                    <input type="text" class="form-control" name="objectives[]" placeholder="e.g., Create dynamic web applications with JavaScript">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="objective-input">
                                    <input type="text" class="form-control" name="objectives[]" placeholder="e.g., Deploy applications to cloud platforms">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addObjective()">
                                <i class="fas fa-plus me-1"></i>Add Objective
                            </button>
                        </div>

                        <div class="form-section">
                            <h6><i class="fas fa-clipboard-list"></i>Requirements</h6>
                            <div class="mb-3">
                                <textarea class="form-control" id="requirements" name="requirements" rows="4" placeholder="What do students need to know or have before taking this course?"></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <h6><i class="fas fa-image"></i>Course Thumbnail</h6>
                            <div class="mb-3">
                                <label for="thumbnail_url" class="form-label">Thumbnail URL</label>
                                <input type="url" class="form-control" id="thumbnail_url" name="thumbnail_url" placeholder="https://example.com/image.jpg" oninput="updatePreview()">
                                <div class="form-text">Enter a URL for the course thumbnail image.</div>
                            </div>
                            <div class="mb-0">
                                <label for="thumbnail_file" class="form-label">Or Upload Image</label>
                                <input type="file" class="form-control" id="thumbnail_file" name="thumbnail_file" accept="image/*">
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="manage-courses.php" class="btn btn-outline-secondary"><i class="fas fa-times me-2"></i>Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-paper-plane me-2"></i>Create Course</button>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="preview-card card">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fas fa-eye me-2" style="color: var(--primary);"></i>Preview</h6>
                                <div class="preview-thumb" id="previewThumb">
                                    <i class="fas fa-image me-2"></i>No image selected
                                </div>
                                <div class="preview-title" id="previewTitle">Course Title</div>
                                <div class="preview-meta mt-2">
                                    <span id="previewCategory">Category</span> &middot;
                                    <span id="previewLevel">Level</span>
                                </div>
                                <div class="preview-price mt-2" id="previewPrice">$0.00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>
<script>
function addObjective() {
    const container = document.getElementById('objectivesContainer');
    const div = document.createElement('div');
    div.className = 'objective-input';
    div.innerHTML = '<input type="text" class="form-control" name="objectives[]" placeholder="e.g., Master advanced concepts">' +
        '<button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>';
    container.appendChild(div);
}

function updatePreview() {
    const url = document.getElementById('thumbnail_url').value;
    const thumb = document.getElementById('previewThumb');
    if (url) {
        thumb.innerHTML = '<img src="' + url + '" alt="Preview" onerror="this.parentElement.innerHTML=\'<i class=\\\'fas fa-image me-2\\\'></i>Invalid image\'">';
    } else {
        thumb.innerHTML = '<i class="fas fa-image me-2"></i>No image selected';
    }
}

document.getElementById('title')?.addEventListener('input', function() {
    document.getElementById('previewTitle').textContent = this.value || 'Course Title';
});
document.getElementById('category')?.addEventListener('change', function() {
    document.getElementById('previewCategory').textContent = this.value || 'Category';
});
document.getElementById('level')?.addEventListener('change', function() {
    document.getElementById('previewLevel').textContent = this.value || 'Level';
});
document.getElementById('price')?.addEventListener('input', function() {
    document.getElementById('previewPrice').textContent = this.value ? '$' + parseFloat(this.value).toFixed(2) : '$0.00';
});

(function() {
    const form = document.getElementById('createCourseForm');
    form?.addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
})();
</script>
<?php require_once '../includes/footer.php'; ?>
