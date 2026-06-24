<?php
$pageTitle = 'Courses';
require_once 'includes/functions.php';

$categories = getCategories();

$filters = [];
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$level = $_GET['level'] ?? '';
$price = $_GET['price'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 9;

if (!empty($search)) $filters['search'] = $search;
if (!empty($category)) $filters['category'] = $category;
if (!empty($level)) $filters['level'] = $level;
if (!empty($price)) $filters['price'] = $price;

$allCourses = getCourses($filters);
$totalCourses = count($allCourses);
$totalPages = max(1, ceil($totalCourses / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$courses = array_slice($allCourses, $offset, $perPage);

include 'includes/header.php';
?>

<section class="hero-section" style="padding: 3rem 0 2rem;">
    <div class="container">
        <div class="section-header mb-3">
            <h1 class="section-title">Explore Our Courses</h1>
            <p class="section-subtitle">Find the perfect course for your learning journey with our AI-powered recommendations.</p>
        </div>
        <form method="GET" action="" class="search-bar mx-auto mb-4" style="max-width: 700px;">
            <i class="fas fa-search ms-3 text-muted"></i>
            <input type="text" id="searchInput" name="search" placeholder="Search courses..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit"><i class="fas fa-search me-2"></i>Search</button>
        </form>
    </div>
</section>

<section class="section-padding pt-0">
    <div class="container">
        <form method="GET" action="" class="filter-section mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label mb-1">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['slug']); ?>" <?php echo $category === $cat['slug'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label mb-1">Level</label>
                    <select name="level" class="form-select">
                        <option value="">All Levels</option>
                        <option value="beginner" <?php echo $level === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                        <option value="intermediate" <?php echo $level === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                        <option value="advanced" <?php echo $level === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label mb-1">Price</label>
                    <select name="price" class="form-select">
                        <option value="">All Prices</option>
                        <option value="free" <?php echo $price === 'free' ? 'selected' : ''; ?>>Free</option>
                        <option value="paid" <?php echo $price === 'paid' ? 'selected' : ''; ?>>Paid</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label mb-1">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                        <option value="price-low" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price-high" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-filter me-2"></i>Apply Filters</button>
                    <a href="courses.php" class="btn btn-outline-secondary flex-fill"><i class="fas fa-times me-2"></i>Clear</a>
                </div>
            </div>
        </form>

        <?php if (!empty($search) || !empty($category) || !empty($level) || !empty($price)): ?>
        <div class="mb-3">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Showing results for
                <?php if (!empty($search)): ?>search "<strong><?php echo htmlspecialchars($search); ?></strong>"<?php endif; ?>
                <?php if (!empty($category)): ?><?php $catName = ''; foreach ($categories as $cat) { if ($cat['slug'] === $category) { $catName = $cat['name']; break; } } ?>in <strong><?php echo htmlspecialchars($catName); ?></strong><?php endif; ?>
                <?php if (!empty($level)): ?>(<strong><?php echo ucfirst($level); ?></strong>)<?php endif; ?>
                <?php if (!empty($price)): ?>(<strong><?php echo ucfirst($price); ?></strong>)<?php endif; ?>
                &mdash; <?php echo $totalCourses; ?> course<?php echo $totalCourses !== 1 ? 's' : ''; ?> found
            </small>
        </div>
        <?php endif; ?>

        <?php if (!empty($courses)): ?>
        <div class="row g-4">
            <?php foreach ($courses as $course): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card course-card h-100">
                    <div style="position: relative;">
                        <img src="https://picsum.photos/seed/<?php echo $course['id']; ?>/400/250" class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>">
                        <span class="course-level"><?php echo ucfirst($course['level']); ?></span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge badge-primary"><?php echo htmlspecialchars($course['category_name'] ?? 'General'); ?></span>
                            <?php if (!empty($course['featured'])): ?><span class="badge bg-warning text-dark">Featured</span><?php endif; ?>
                        </div>
                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($course['short_description'] ?? $course['description'], 0, 100)); ?>...</p>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($course['instructor_name'] ?? 'Instructor'); ?>&background=2563eb&color=fff&size=30" class="instructor-img" alt="">
                            <small class="text-muted"><?php echo htmlspecialchars($course['instructor_name'] ?? 'Expert Instructor'); ?></small>
                        </div>
                        <?php echo getRatingStars($course['rating']); ?>
                        <div class="course-meta">
                            <span><i class="fas fa-users me-1"></i><?php echo $course['total_students']; ?></span>
                            <span><i class="fas fa-book me-1"></i><?php echo $course['total_lessons']; ?> lessons</span>
                            <span class="ms-auto course-price">
                                <?php if (!empty($course['discount_price'])): ?>
                                    <span class="original"><?php echo formatPrice($course['price']); ?></span>
                                    <?php echo formatPrice($course['discount_price']); ?>
                                <?php else: ?>
                                    <?php echo formatPrice($course['price']); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <a href="course-details.php?id=<?php echo $course['id']; ?>" class="stretched-link"></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <nav class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>"><i class="fas fa-chevron-left"></i></a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>"><i class="fas fa-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <h5>No Courses Found</h5>
            <p>We couldn't find any courses matching your criteria. Try adjusting your filters or search terms.</p>
            <a href="courses.php" class="btn btn-primary"><i class="fas fa-times me-2"></i>Clear All Filters</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
