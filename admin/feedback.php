<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Feedback Management';
require_once '../includes/header.php';

$typeFilter = $_GET['type'] ?? 'all';
$statusFilter = $_GET['status'] ?? 'all';

$feedbacks = [
    ['id' => 1, 'name' => 'Alice Johnson', 'email' => 'alice@example.com', 'subject' => 'Course suggestion', 'type' => 'feature', 'message' => 'I would love to see a course on Blockchain development. It would be very helpful for my career transition.', 'status' => 'pending', 'date' => '2026-06-23'],
    ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bob@example.com', 'subject' => 'Video playback issue', 'type' => 'bug', 'message' => 'The video player keeps buffering on Module 3 of the Python course. I have a stable internet connection so it might be a server issue.', 'status' => 'read', 'date' => '2026-06-22'],
    ['id' => 3, 'name' => 'Carol White', 'email' => 'carol@example.com', 'subject' => 'Great platform!', 'type' => 'general', 'message' => 'Just wanted to say that SmartLearn is an amazing platform. The AI assistant feature is incredibly helpful.', 'status' => 'resolved', 'date' => '2026-06-21'],
    ['id' => 4, 'name' => 'David Lee', 'email' => 'david@example.com', 'subject' => 'Refund request', 'type' => 'complaint', 'message' => 'I accidentally purchased the wrong course. I would like to request a refund for the Advanced React course.', 'status' => 'pending', 'date' => '2026-06-20'],
    ['id' => 5, 'name' => 'Emma Brown', 'email' => 'emma@example.com', 'subject' => 'Mobile app feature', 'type' => 'feature', 'message' => 'It would be great if you could develop a mobile app for offline learning. I commute a lot and would love to watch lectures offline.', 'status' => 'read', 'date' => '2026-06-19'],
    ['id' => 6, 'name' => 'Frank Wilson', 'email' => 'frank@example.com', 'subject' => 'Quiz scoring error', 'type' => 'bug', 'message' => 'There seems to be an issue with the scoring system in Module 5 quiz. I answered correctly but it marked it wrong.', 'status' => 'resolved', 'date' => '2026-06-18'],
    ['id' => 7, 'name' => 'Grace Kim', 'email' => 'grace@example.com', 'subject' => 'Certificate not generating', 'type' => 'bug', 'message' => 'I completed the UI/UX Design course but my certificate is not being generated. It says "processing" for the past 2 days.', 'status' => 'pending', 'date' => '2026-06-17'],
    ['id' => 8, 'name' => 'Henry Davis', 'email' => 'henry@example.com', 'subject' => 'General inquiry', 'type' => 'general', 'message' => 'Do you offer group discounts for organizations? Our company is interested in enrolling 20 employees.', 'status' => 'read', 'date' => '2026-06-16'],
];

if ($typeFilter !== 'all') {
    $feedbacks = array_filter($feedbacks, fn($f) => $f['type'] === $typeFilter);
}
if ($statusFilter !== 'all') {
    $feedbacks = array_filter($feedbacks, fn($f) => $f['status'] === $statusFilter);
}
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }
.feedback-message-box {
    background: var(--gray-50); border-radius: var(--radius); padding: 1rem;
    max-height: 200px; overflow-y: auto; font-size: 0.9rem;
    border-left: 3px solid var(--primary);
}
.filter-btn { font-size: 0.85rem; padding: 0.35rem 0.8rem; border-radius: 20px; }
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Feedback Management</h4>
                    <p class="text-muted mb-0">Review and manage user feedback, bug reports, and feature requests.</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <span class="text-muted small me-1">Type:</span>
                                <a href="?type=all&status=<?php echo $statusFilter; ?>" class="btn btn-sm filter-btn <?php echo $typeFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary'; ?>">All</a>
                                <a href="?type=general&status=<?php echo $statusFilter; ?>" class="btn btn-sm filter-btn <?php echo $typeFilter === 'general' ? 'btn-primary' : 'btn-outline-secondary'; ?>">General</a>
                                <a href="?type=bug&status=<?php echo $statusFilter; ?>" class="btn btn-sm filter-btn <?php echo $typeFilter === 'bug' ? 'btn-danger' : 'btn-outline-secondary'; ?>">Bug</a>
                                <a href="?type=feature&status=<?php echo $statusFilter; ?>" class="btn btn-sm filter-btn <?php echo $typeFilter === 'feature' ? 'btn-success' : 'btn-outline-secondary'; ?>">Feature</a>
                                <a href="?type=complaint&status=<?php echo $statusFilter; ?>" class="btn btn-sm filter-btn <?php echo $typeFilter === 'complaint' ? 'btn-warning' : 'btn-outline-secondary'; ?>">Complaint</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 align-items-center justify-content-md-end flex-wrap">
                                <span class="text-muted small me-1">Status:</span>
                                <a href="?type=<?php echo $typeFilter; ?>&status=all" class="btn btn-sm filter-btn <?php echo $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary'; ?>">All</a>
                                <a href="?type=<?php echo $typeFilter; ?>&status=pending" class="btn btn-sm filter-btn <?php echo $statusFilter === 'pending' ? 'btn-warning' : 'btn-outline-secondary'; ?>">Pending</a>
                                <a href="?type=<?php echo $typeFilter; ?>&status=read" class="btn btn-sm filter-btn <?php echo $statusFilter === 'read' ? 'btn-info' : 'btn-outline-secondary'; ?>">Read</a>
                                <a href="?type=<?php echo $typeFilter; ?>&status=resolved" class="btn btn-sm filter-btn <?php echo $statusFilter === 'resolved' ? 'btn-success' : 'btn-outline-secondary'; ?>">Resolved</a>
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
                                <th>Subject</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($feedbacks) > 0): ?>
                                <?php foreach ($feedbacks as $fb): ?>
                                <tr>
                                    <td><span class="text-muted">#<?php echo $fb['id']; ?></span></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($fb['name']); ?></td>
                                    <td><small><?php echo htmlspecialchars($fb['email']); ?></small></td>
                                    <td><small><?php echo htmlspecialchars($fb['subject']); ?></small></td>
                                    <td>
                                        <span class="badge <?php echo $fb['type'] === 'bug' ? 'badge-danger' : ($fb['type'] === 'feature' ? 'badge-success' : ($fb['type'] === 'complaint' ? 'badge-warning' : 'badge-primary')); ?>">
                                            <?php echo ucfirst($fb['type']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $fb['status'] === 'pending' ? 'badge-warning' : ($fb['status'] === 'read' ? 'badge-info' : 'badge-success' ? 'badge-info' : 'badge-success'); ?>" style="<?php echo $fb['status'] === 'read' ? 'background: #dbeafe; color: #1e40af;' : ''; ?>">
                                            <?php echo ucfirst($fb['status']); ?>
                                        </span>
                                    </td>
                                    <td><small class="text-muted"><?php echo date('M d, Y', strtotime($fb['date'])); ?></small></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-info btn-icon" title="View" onclick="viewFeedback(<?php echo $fb['id']; ?>, '<?php echo htmlspecialchars($fb['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($fb['email'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($fb['subject'], ENT_QUOTES); ?>', '<?php echo $fb['type']; ?>', '<?php echo htmlspecialchars($fb['message'], ENT_QUOTES); ?>', '<?php echo $fb['date']; ?>', '<?php echo $fb['status']; ?>')"><i class="fas fa-eye"></i></button>
                                            <?php if ($fb['status'] === 'pending'): ?>
                                            <button class="btn btn-sm btn-outline-primary btn-icon" title="Mark as Read" onclick="markAs('read', <?php echo $fb['id']; ?>)"><i class="fas fa-check"></i></button>
                                            <?php endif; ?>
                                            <?php if ($fb['status'] !== 'resolved'): ?>
                                            <button class="btn btn-sm btn-outline-success btn-icon" title="Mark as Resolved" onclick="markAs('resolved', <?php echo $fb['id']; ?>)"><i class="fas fa-check-double"></i></button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center py-4 text-muted">No feedback found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- View Feedback Modal -->
<div class="modal fade" id="viewFeedbackModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-comment-dots me-2" style="color: var(--primary);"></i>Feedback Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Name</small>
                        <p class="fw-semibold mb-0" id="viewFbName"></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Email</small>
                        <p class="fw-semibold mb-0" id="viewFbEmail"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Subject</small>
                        <p class="fw-semibold mb-0" id="viewFbSubject"></p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Type</small>
                        <p id="viewFbType" class="mb-0"></p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Date</small>
                        <p class="mb-0" id="viewFbDate"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Message</small>
                    <div class="feedback-message-box mt-1">
                        <p class="mb-0" id="viewFbMessage"></p>
                    </div>
                </div>
                <hr>
                <div class="d-flex gap-2" id="viewFbActions">
                    <button class="btn btn-primary" onclick="alert('Reply functionality coming soon!')"><i class="fas fa-reply me-1"></i>Reply</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewFeedback(id, name, email, subject, type, message, date, status) {
    document.getElementById('viewFbName').textContent = name;
    document.getElementById('viewFbEmail').textContent = email;
    document.getElementById('viewFbSubject').textContent = subject;
    document.getElementById('viewFbType').innerHTML = '<span class="badge ' + (type === 'bug' ? 'badge-danger' : type === 'feature' ? 'badge-success' : type === 'complaint' ? 'badge-warning' : 'badge-primary') + '">' + type.charAt(0).toUpperCase() + type.slice(1) + '</span>';
    document.getElementById('viewFbDate').textContent = new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    document.getElementById('viewFbMessage').textContent = message;
    new bootstrap.Modal(document.getElementById('viewFeedbackModal')).show();
}
function markAs(action, id) {
    if (confirm('Mark feedback #' + id + ' as ' + action + '?')) {
        alert('Feedback #' + id + ' marked as ' + action + '!');
        location.reload();
    }
}
</script>
<?php require_once '../includes/footer.php'; ?>
