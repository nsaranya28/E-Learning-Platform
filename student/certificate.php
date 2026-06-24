<?php
session_start();
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php', 'Please login to view your certificates', 'warning');
}

$pageTitle = 'Certificates';
$hideNavbar = true;
require_once '../includes/header.php';

$userId = $_SESSION['user_id'];
$user = getUser($userId);

$certificates = [
    [
        'id' => 1,
        'course_name' => 'HTML & CSS Fundamentals',
        'instructor' => 'John Doe',
        'completed_date' => '2026-04-15',
        'cert_number' => 'CRT-A1B2C3D4E5-1-1',
        'hours' => 20,
        'grade' => 'A',
    ],
    [
        'id' => 2,
        'course_name' => 'JavaScript Essentials',
        'instructor' => 'Emily Chen',
        'completed_date' => '2026-05-01',
        'cert_number' => 'CRT-F6G7H8I9J0-1-2',
        'hours' => 25,
        'grade' => 'A+',
    ],
    [
        'id' => 3,
        'course_name' => 'Python Programming Basics',
        'instructor' => 'Jane Smith',
        'completed_date' => '2026-05-20',
        'cert_number' => 'CRT-K1L2M3N4O5-1-3',
        'hours' => 30,
        'grade' => 'A',
    ],
    [
        'id' => 4,
        'course_name' => 'UI/UX Design Masterclass',
        'instructor' => 'Sarah Johnson',
        'completed_date' => '2026-06-01',
        'cert_number' => 'CRT-P6Q7R8S9T0-1-4',
        'hours' => 35,
        'grade' => 'A+',
    ],
];
?>

<style>
:root { --sidebar-width: 260px; }

.cert-card {
    transition: var(--transition);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    background: var(--white);
    overflow: hidden;
}
.cert-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.cert-preview {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    padding: 2rem 1.5rem;
    text-align: center;
    position: relative;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.cert-preview::before {
    content: '';
    position: absolute;
    inset: 8px;
    border: 1px solid rgba(255,255,255,0.3);
    pointer-events: none;
}
.cert-preview .cert-badge-icon {
    width: 64px;
    height: 64px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--white);
    margin-bottom: 0.8rem;
}
.cert-preview h5 {
    color: var(--white);
    font-weight: 700;
    margin-bottom: 0.3rem;
    font-size: 1rem;
}
.cert-preview small {
    color: rgba(255,255,255,0.7);
    font-size: 0.8rem;
}

.cert-body {
    padding: 1.5rem;
}
.cert-body .cert-detail {
    display: flex;
    align-items: center;
    padding: 0.4rem 0;
    font-size: 0.9rem;
    border-bottom: 1px solid var(--gray-100);
}
.cert-body .cert-detail:last-child { border-bottom: none; }
.cert-body .cert-detail i {
    width: 20px;
    color: var(--primary);
    margin-right: 10px;
}
.cert-body .cert-detail .label { color: var(--gray-500); margin-right: 6px; }
.cert-body .cert-detail .value { font-weight: 600; color: var(--gray-800); }
</style>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div>
                <h4>My Certificates</h4>
                <p class="text-muted mb-0">View and share your earned certificates.</p>
            </div>
        </div>

        <?php if (count($certificates) === 0): ?>
        <div class="empty-state">
            <i class="fas fa-award"></i>
            <h5>No Certificates Yet</h5>
            <p>Complete courses to earn certificates and showcase your achievements.</p>
            <a href="my-courses.php" class="btn btn-primary mt-2"><i class="fas fa-book-open me-2"></i>My Courses</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($certificates as $cert): ?>
            <div class="col-md-6 col-lg-4">
                <div class="cert-card h-100 d-flex flex-column">
                    <div class="cert-preview">
                        <div class="cert-badge-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h5>Smart<span style="color: #93c5fd;">Learn</span></h5>
                        <small>Certificate of Completion</small>
                    </div>
                    <div class="cert-body flex-grow-1 d-flex flex-column">
                        <h6 class="fw-bold mb-3" style="font-size: 0.95rem;"><?php echo $cert['course_name']; ?></h6>
                        <div class="cert-detail">
                            <i class="fas fa-user"></i>
                            <span class="label">Student:</span>
                            <span class="value"><?php echo htmlspecialchars($user['full_name'] ?? $_SESSION['user_name'] ?? 'Student'); ?></span>
                        </div>
                        <div class="cert-detail">
                            <i class="fas fa-calendar"></i>
                            <span class="label">Date:</span>
                            <span class="value"><?php echo date('F d, Y', strtotime($cert['completed_date'])); ?></span>
                        </div>
                        <div class="cert-detail">
                            <i class="fas fa-hashtag"></i>
                            <span class="label">Cert No:</span>
                            <span class="value" style="font-size: 0.8rem;"><?php echo $cert['cert_number']; ?></span>
                        </div>
                        <div class="cert-detail">
                            <i class="fas fa-star"></i>
                            <span class="label">Grade:</span>
                            <span class="value" style="color: var(--success);"><?php echo $cert['grade']; ?></span>
                        </div>
                        <div class="cert-detail">
                            <i class="fas fa-clock"></i>
                            <span class="label">Hours:</span>
                            <span class="value"><?php echo $cert['hours']; ?> hours</span>
                        </div>
                        <div class="mt-auto pt-3 d-flex gap-2">
                            <button class="btn btn-primary btn-sm flex-grow-1" onclick="downloadCert('<?php echo $cert['cert_number']; ?>')">
                                <i class="fas fa-download me-1"></i>Download PDF
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="shareLinkedin('<?php echo urlencode($cert['course_name']); ?>', '<?php echo urlencode($cert['cert_number']); ?>')">
                                <i class="fab fa-linkedin-in"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="certificate-wrapper mt-5" id="certificatePreview" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Certificate Preview</h5>
                <button class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('certificatePreview').style.display='none'">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
            <div class="certificate" id="previewContent">
                <div class="cert-badge"><i class="fas fa-graduation-cap"></i></div>
                <h2>Smart<span style="color: var(--primary);">Learn</span></h2>
                <p class="cert-subtitle">Certificate of Completion</p>
                <p style="color: var(--gray-500);">This is to certify that</p>
                <div class="cert-name"><?php echo htmlspecialchars($user['full_name'] ?? $_SESSION['user_name'] ?? 'Student'); ?></div>
                <p style="color: var(--gray-500); margin-top: 1rem;">has successfully completed the course</p>
                <div class="cert-course" id="previewCourseName"></div>
                <div class="cert-date" id="previewDate"></div>
                <div class="cert-id" id="previewCertId"></div>
            </div>
        </div>
        <?php endif; ?>
    </main>
</div>

<script>
function downloadCert(certNumber) {
    const certCard = event.target.closest('.cert-card');
    const courseName = certCard.querySelector('h6').textContent;
    const previewDiv = document.getElementById('certificatePreview');
    document.getElementById('previewCourseName').textContent = courseName;
    document.getElementById('previewDate').textContent = 'Date: ' + new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    document.getElementById('previewCertId').textContent = 'Certificate No: ' + certNumber;
    previewDiv.style.display = 'block';
    previewDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    alert('PDF download would start: ' + courseName + ' (' + certNumber + ')');
}

function shareLinkedin(courseName, certNumber) {
    const url = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(window.location.href) +
                '&title=' + encodeURIComponent('I earned a certificate in ' + decodeURIComponent(courseName) + ' on SmartLearn!');
    window.open(url, '_blank', 'width=600,height=600');
}
</script>

<?php require_once '../includes/footer.php'; ?>
