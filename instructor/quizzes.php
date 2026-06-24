<?php
session_start();
if (!isset($_SESSION['instructor_id'])) { header('Location: ../instructor-login.php'); exit(); }
$hideNavbar = true;
$pageTitle = 'Quizzes';
require_once '../includes/header.php';

$quizzes = [
    ['id' => 1, 'title' => 'HTML & CSS Fundamentals Quiz', 'course' => 'Complete Web Development Bootcamp', 'questions' => 15, 'time_limit' => 30, 'passing_score' => 70, 'attempts' => 89, 'avg_score' => 78, 'status' => 'active'],
    ['id' => 2, 'title' => 'JavaScript Basics Assessment', 'course' => 'Complete Web Development Bootcamp', 'questions' => 20, 'time_limit' => 45, 'passing_score' => 65, 'attempts' => 67, 'avg_score' => 72, 'status' => 'active'],
    ['id' => 3, 'title' => 'Python Data Structures Quiz', 'course' => 'Python for Data Science & ML', 'questions' => 12, 'time_limit' => 25, 'passing_score' => 75, 'attempts' => 54, 'avg_score' => 81, 'status' => 'active'],
    ['id' => 4, 'title' => 'UI Design Principles Test', 'course' => 'UI/UX Design Masterclass', 'questions' => 10, 'time_limit' => 20, 'passing_score' => 70, 'attempts' => 42, 'avg_score' => 85, 'status' => 'draft'],
    ['id' => 5, 'title' => 'React Hooks Quiz', 'course' => 'Advanced React & Next.js', 'questions' => 18, 'time_limit' => 40, 'passing_score' => 70, 'attempts' => 23, 'avg_score' => 68, 'status' => 'draft'],
];

$questionTypes = ['Multiple Choice', 'True/False', 'Fill in the Blank', 'Matching'];
?>
<style>
:root { --sidebar-width: 260px; }
.dashboard-content { max-width: 1400px; }

.question-card {
    background: var(--gray-50); border-radius: var(--radius); padding: 1.5rem;
    margin-bottom: 1rem; border: 1px solid var(--gray-200); position: relative;
}
.question-card .q-number {
    position: absolute; top: -10px; left: 1rem;
    background: var(--primary); color: white; width: 28px; height: 28px;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; font-weight: 700;
}
.question-card .remove-question {
    position: absolute; top: 0.5rem; right: 0.5rem;
}
.option-row {
    display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;
}
.option-row input[type="text"] { flex: 1; }
.option-row input[type="radio"] { width: 18px; height: 18px; cursor: pointer; }

@media (max-width: 767px) {
    .quiz-tabs { flex-direction: column; }
}
</style>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-header">
                <div>
                    <h4>Quizzes</h4>
                    <p class="text-muted mb-0">Create and manage quizzes for your courses.</p>
                </div>
            </div>

            <ul class="nav nav-tabs mb-4" id="quizTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="create-tab" data-bs-toggle="tab" data-bs-target="#createQuiz" type="button" role="tab">
                        <i class="fas fa-plus me-1"></i>Create Quiz
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manageQuizzes" type="button" role="tab">
                        <i class="fas fa-list me-1"></i>Manage Quizzes
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="createQuiz" role="tabpanel">
                    <form id="createQuizForm" novalidate>
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-cog me-2" style="color: var(--primary);"></i>Quiz Settings</h6>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label for="quizTitle" class="form-label">Quiz Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="quizTitle" placeholder="e.g., Module 5 Assessment" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="quizCourse" class="form-label">Course <span class="text-danger">*</span></label>
                                                <select class="form-select" id="quizCourse" required>
                                                    <option value="">Select course</option>
                                                    <option>Complete Web Development Bootcamp</option>
                                                    <option>Python for Data Science & ML</option>
                                                    <option>UI/UX Design Masterclass</option>
                                                    <option>Advanced React & Next.js</option>
                                                    <option>Mobile App Development with Flutter</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="quizDescription" class="form-label">Description</label>
                                            <textarea class="form-control" id="quizDescription" rows="2" placeholder="Brief instructions or description for the quiz"></textarea>
                                        </div>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <label for="quizTimeLimit" class="form-label">Time Limit (minutes)</label>
                                                <input type="number" class="form-control" id="quizTimeLimit" min="1" value="30">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="quizPassingScore" class="form-label">Passing Score (%)</label>
                                                <input type="number" class="form-control" id="quizPassingScore" min="0" max="100" value="70">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="quizMaxAttempts" class="form-label">Max Attempts</label>
                                                <input type="number" class="form-control" id="quizMaxAttempts" min="1" value="3">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold mb-0"><i class="fas fa-question-circle me-2" style="color: var(--primary);"></i>Questions</h6>
                                            <div class="d-flex gap-2">
                                                <select class="form-select form-select-sm" id="questionType" style="width: auto;">
                                                    <option value="multiple_choice">Multiple Choice</option>
                                                    <option value="true_false">True/False</option>
                                                    <option value="fill_blank">Fill in the Blank</option>
                                                </select>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addQuestion()">
                                                    <i class="fas fa-plus me-1"></i>Add Question
                                                </button>
                                            </div>
                                        </div>
                                        <div id="questionsContainer">
                                            <div class="question-card" data-q="1">
                                                <span class="q-number">1</span>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-question" onclick="this.closest('.question-card').remove(); renumberQuestions();"><i class="fas fa-times"></i></button>
                                                <div class="mb-3">
                                                    <label class="form-label">Question <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="q_text[]" placeholder="Enter your question" required>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Type</label>
                                                        <select class="form-select" name="q_type[]" onchange="toggleOptions(this)">
                                                            <option value="multiple_choice">Multiple Choice</option>
                                                            <option value="true_false">True/False</option>
                                                            <option value="fill_blank">Fill in the Blank</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Points</label>
                                                        <input type="number" class="form-control" name="q_points[]" min="1" value="10">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Correct Answer</label>
                                                        <input type="text" class="form-control" name="q_answer[]" placeholder="e.g., A">
                                                    </div>
                                                </div>
                                                <div class="options-container">
                                                    <label class="form-label">Options</label>
                                                    <div class="option-row">
                                                        <input type="radio" name="q_1_correct" checked>
                                                        <input type="text" class="form-control" name="q_options[]" placeholder="Option A">
                                                    </div>
                                                    <div class="option-row">
                                                        <input type="radio" name="q_1_correct">
                                                        <input type="text" class="form-control" name="q_options[]" placeholder="Option B">
                                                    </div>
                                                    <div class="option-row">
                                                        <input type="radio" name="q_1_correct">
                                                        <input type="text" class="form-control" name="q_options[]" placeholder="Option C">
                                                    </div>
                                                    <div class="option-row">
                                                        <input type="radio" name="q_1_correct">
                                                        <input type="text" class="form-control" name="q_options[]" placeholder="Option D">
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="addOption(this)"><i class="fas fa-plus me-1"></i>Add Option</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="button" class="btn btn-outline-primary" onclick="addQuestion()">
                                                <i class="fas fa-plus me-1"></i>Add Another Question
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-end gap-2">
                                    <button type="reset" class="btn btn-outline-secondary"><i class="fas fa-undo me-2"></i>Reset</button>
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Save Quiz</button>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2" style="color: var(--info);"></i>Quiz Summary</h6>
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Questions:</span>
                                            <span class="fw-semibold" id="summaryQuestions">1</span>
                                        </div>
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Total Points:</span>
                                            <span class="fw-semibold" id="summaryPoints">10</span>
                                        </div>
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Time Limit:</span>
                                            <span class="fw-semibold" id="summaryTime">30 min</span>
                                        </div>
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Passing Score:</span>
                                            <span class="fw-semibold" id="summaryPassing">70%</span>
                                        </div>
                                        <hr>
                                        <p class="small text-muted mb-0">Questions are automatically numbered. You can reorder them after saving.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="manageQuizzes" role="tabpanel">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th>Quiz Title</th>
                                            <th>Course</th>
                                            <th>Questions</th>
                                            <th>Time Limit</th>
                                            <th>Passing Score</th>
                                            <th>Attempts</th>
                                            <th>Avg. Score</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($quizzes) > 0): ?>
                                        <?php foreach ($quizzes as $quiz): ?>
                                        <tr>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($quiz['title']); ?></td>
                                            <td><small><?php echo htmlspecialchars($quiz['course']); ?></small></td>
                                            <td><?php echo $quiz['questions']; ?></td>
                                            <td><?php echo $quiz['time_limit']; ?> min</td>
                                            <td><?php echo $quiz['passing_score']; ?>%</td>
                                            <td><?php echo $quiz['attempts']; ?></td>
                                            <td>
                                                <span class="fw-semibold" style="color: <?php echo $quiz['avg_score'] >= 70 ? 'var(--success)' : 'var(--danger)'; ?>;">
                                                    <?php echo $quiz['avg_score']; ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $quiz['status'] === 'active' ? 'badge-success' : 'badge-warning'; ?>">
                                                    <?php echo ucfirst($quiz['status']); ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-info me-1" title="Preview"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="empty-state py-3">
                                                    <i class="fas fa-question-circle"></i>
                                                    <h5>No quizzes yet</h5>
                                                    <p>Create your first quiz to assess your students.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
let questionCounter = 2;

function addQuestion() {
    const container = document.getElementById('questionsContainer');
    const qNum = questionCounter++;
    const div = document.createElement('div');
    div.className = 'question-card';
    div.dataset.q = qNum;
    div.innerHTML = `
        <span class="q-number">${qNum}</span>
        <button type="button" class="btn btn-sm btn-outline-danger remove-question" onclick="this.closest('.question-card').remove(); renumberQuestions();"><i class="fas fa-times"></i></button>
        <div class="mb-3">
            <label class="form-label">Question <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="q_text[]" placeholder="Enter your question" required>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label">Type</label>
                <select class="form-select" name="q_type[]" onchange="toggleOptions(this)">
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="true_false">True/False</option>
                    <option value="fill_blank">Fill in the Blank</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Points</label>
                <input type="number" class="form-control" name="q_points[]" min="1" value="10">
            </div>
            <div class="col-md-3">
                <label class="form-label">Correct Answer</label>
                <input type="text" class="form-control" name="q_answer[]" placeholder="e.g., A">
            </div>
        </div>
        <div class="options-container">
            <label class="form-label">Options</label>
            <div class="option-row">
                <input type="radio" name="q_${qNum}_correct" checked>
                <input type="text" class="form-control" name="q_options[]" placeholder="Option A">
            </div>
            <div class="option-row">
                <input type="radio" name="q_${qNum}_correct">
                <input type="text" class="form-control" name="q_options[]" placeholder="Option B">
            </div>
            <div class="option-row">
                <input type="radio" name="q_${qNum}_correct">
                <input type="text" class="form-control" name="q_options[]" placeholder="Option C">
            </div>
            <div class="option-row">
                <input type="radio" name="q_${qNum}_correct">
                <input type="text" class="form-control" name="q_options[]" placeholder="Option D">
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="addOption(this)"><i class="fas fa-plus me-1"></i>Add Option</button>
        </div>
    `;
    container.appendChild(div);
    updateSummary();
}

function renumberQuestions() {
    const cards = document.querySelectorAll('.question-card');
    cards.forEach((card, i) => {
        card.querySelector('.q-number').textContent = i + 1;
    });
    updateSummary();
}

function toggleOptions(select) {
    const container = select.closest('.question-card').querySelector('.options-container');
    const type = select.value;
    if (type === 'multiple_choice') {
        container.style.display = 'block';
    } else if (type === 'true_false') {
        container.style.display = 'block';
        const radios = container.querySelectorAll('input[type="radio"]');
        const inputs = container.querySelectorAll('.option-row input[type="text"]');
        if (inputs.length >= 2) {
            inputs[0].value = 'True';
            inputs[1].value = 'False';
        }
    } else {
        container.style.display = 'none';
    }
}

function addOption(btn) {
    const container = btn.closest('.options-container');
    const qCard = btn.closest('.question-card');
    const qNum = qCard.dataset.q;
    const row = document.createElement('div');
    row.className = 'option-row';
    row.innerHTML = `
        <input type="radio" name="q_${qNum}_correct">
        <input type="text" class="form-control" name="q_options[]" placeholder="Option ${String.fromCharCode(65 + container.querySelectorAll('.option-row').length)}">
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    `;
    container.insertBefore(row, btn);
}

function updateSummary() {
    const cards = document.querySelectorAll('.question-card');
    document.getElementById('summaryQuestions').textContent = cards.length;
    let total = 0;
    cards.forEach(c => {
        const pts = c.querySelector('input[name="q_points[]"]');
        if (pts) total += parseInt(pts.value) || 0;
    });
    document.getElementById('summaryPoints').textContent = total;
    const time = document.getElementById('quizTimeLimit');
    if (time) document.getElementById('summaryTime').textContent = time.value + ' min';
    const pass = document.getElementById('quizPassingScore');
    if (pass) document.getElementById('summaryPassing').textContent = pass.value + '%';
}

document.getElementById('quizTimeLimit')?.addEventListener('input', updateSummary);
document.getElementById('quizPassingScore')?.addEventListener('input', updateSummary);
document.addEventListener('input', function(e) {
    if (e.target.matches('input[name="q_points[]"]')) updateSummary();
});
</script>
<?php require_once '../includes/footer.php'; ?>
