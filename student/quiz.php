<?php
session_start();
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php', 'Please login to access quizzes', 'warning');
}

$pageTitle = 'Quiz';
$hideNavbar = true;
require_once '../includes/header.php';

$userId = $_SESSION['user_id'];

$quizTitle = 'JavaScript Fundamentals Assessment';
$courseName = 'Complete Web Development Bootcamp';
$totalQuestions = 10;
$timeLimit = 15;

$questions = [
    [
        'id' => 1,
        'question' => 'Which of the following is NOT a JavaScript data type?',
        'options' => ['String', 'Number', 'Float', 'Boolean'],
        'correct' => 2,
    ],
    [
        'id' => 2,
        'question' => 'What does the "=== " operator do in JavaScript?',
        'options' => ['Assignment', 'Comparison with type coercion', 'Strict equality comparison', 'Logical AND'],
        'correct' => 2,
    ],
    [
        'id' => 3,
        'question' => 'Which method is used to add an element to the end of an array?',
        'options' => ['push()', 'pop()', 'shift()', 'unshift()'],
        'correct' => 0,
    ],
    [
        'id' => 4,
        'question' => 'What is the output of typeof null in JavaScript?',
        'options' => ['null', 'undefined', 'object', 'boolean'],
        'correct' => 2,
    ],
    [
        'id' => 5,
        'question' => 'Which keyword is used to declare a constant variable in ES6?',
        'options' => ['var', 'let', 'const', 'static'],
        'correct' => 2,
    ],
    [
        'id' => 6,
        'question' => 'What does the map() method return?',
        'options' => ['A new array', 'The original array modified', 'A boolean', 'A string'],
        'correct' => 0,
    ],
    [
        'id' => 7,
        'question' => 'Which symbol is used for single-line comments in JavaScript?',
        'options' => ['//', '/*', '#', '<!--'],
        'correct' => 0,
    ],
    [
        'id' => 8,
        'question' => 'What is the correct way to create a Promise in JavaScript?',
        'options' => ['new Promise()', 'Promise.create()', 'new Promise(executor)', 'Promise.new()'],
        'correct' => 2,
    ],
    [
        'id' => 9,
        'question' => 'Which method converts a JSON string to a JavaScript object?',
        'options' => ['JSON.stringify()', 'JSON.parse()', 'JSON.convert()', 'JSON.toObject()'],
        'correct' => 1,
    ],
    [
        'id' => 10,
        'question' => 'What is the spread operator symbol in JavaScript?',
        'options' => ['...', '..', '**', '&&'],
        'correct' => 0,
    ],
];
?>

<style>
:root { --sidebar-width: 260px; }

.quiz-nav-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid var(--gray-200);
    background: var(--white);
    font-weight: 700;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}
.quiz-nav-btn:hover { border-color: var(--primary); color: var(--primary); }
.quiz-nav-btn.answered { background: var(--primary); border-color: var(--primary); color: var(--white); }
.quiz-nav-btn.current { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2); }

.quiz-nav-btn.correct-answer { background: var(--success); border-color: var(--success); color: var(--white); }
.quiz-nav-btn.incorrect-answer { background: var(--danger); border-color: var(--danger); color: var(--white); }

.result-circle {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-weight: 800;
}
.result-circle.pass { background: #d1fae5; color: var(--success); border: 6px solid var(--success); }
.result-circle.fail { background: #fee2e2; color: var(--danger); border: 6px solid var(--danger); }
.result-circle .score-num { font-size: 2.8rem; line-height: 1; }
.result-circle .score-label { font-size: 0.9rem; font-weight: 600; }

.quiz-option input[type="radio"] { display: none; }
.quiz-option.selected { border-color: var(--primary); background: var(--primary-bg); }
.quiz-option.correct { border-color: var(--success); background: #d1fae5; }
.quiz-option.incorrect { border-color: var(--danger); background: #fee2e2; }
.quiz-option.disabled { pointer-events: none; opacity: 0.7; }
</style>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="quiz-container" id="quizApp">
            <div class="quiz-header">
                <div>
                    <h5 class="fw-bold mb-1"><?php echo $quizTitle; ?></h5>
                    <small class="text-muted"><i class="fas fa-book me-1"></i><?php echo $courseName; ?></small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-primary"><i class="fas fa-question-circle me-1"></i><span id="currentQuestionNum">1</span>/<?php echo $totalQuestions; ?></span>
                    <div class="quiz-timer" id="quizTimer"><?php echo $timeLimit; ?>:00</div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div id="questionsContainer">
                        <?php foreach ($questions as $i => $q): ?>
                        <div class="quiz-question" data-qid="<?php echo $q['id']; ?>" <?php echo $i > 0 ? 'style="display:none;"' : ''; ?>>
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">Question <?php echo $i + 1; ?></h5>
                                <span class="badge badge-primary" style="font-size: 0.8rem;">1 Mark</span>
                            </div>
                            <p class="fw-medium mb-3" style="font-size: 1.05rem;"><?php echo $q['question']; ?></p>
                            <div class="quiz-options-list">
                                <?php foreach ($q['options'] as $j => $opt): ?>
                                <label class="quiz-option" data-opt-index="<?php echo $j; ?>">
                                    <input type="radio" name="q_<?php echo $q['id']; ?>" value="<?php echo $j; ?>">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="option-letter badge bg-light text-dark" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                            <?php echo chr(65 + $j); ?>
                                        </span>
                                        <?php echo $opt; ?>
                                    </span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button class="btn btn-outline-primary" id="prevBtn" onclick="navigateQuestion(-1)" disabled>
                            <i class="fas fa-arrow-left me-2"></i>Previous
                        </button>
                        <button class="btn btn-primary" id="nextBtn" onclick="navigateQuestion(1)">
                            Next<i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <button class="btn btn-success" id="submitBtn" style="display:none;" onclick="submitQuiz()">
                            <i class="fas fa-check me-2"></i>Submit Quiz
                        </button>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-th me-2" style="color: var(--primary);"></i>Question Navigator</h6>
                            <div class="d-flex flex-wrap gap-2 mb-3" id="questionNav">
                                <?php for ($i = 1; $i <= $totalQuestions; $i++): ?>
                                <button class="quiz-nav-btn" data-qindex="<?php echo $i - 1; ?>" onclick="goToQuestion(<?php echo $i - 1; ?>)"><?php echo $i; ?></button>
                                <?php endfor; ?>
                            </div>
                            <div class="d-flex gap-3 mb-3" style="font-size: 0.8rem; color: var(--gray-500);">
                                <span><span style="display:inline-block; width:12px; height:12px; background:var(--primary); border-radius:3px; margin-right:4px;"></span>Answered</span>
                                <span><span style="display:inline-block; width:12px; height:12px; background:var(--white); border:2px solid var(--gray-300); border-radius:3px; margin-right:4px;"></span>Unanswered</span>
                            </div>
                            <hr>
                            <div class="text-center">
                                <button class="btn btn-success w-100 mb-2" onclick="submitQuiz()">
                                    <i class="fas fa-check-circle me-2"></i>Submit Quiz
                                </button>
                                <small class="text-muted">Make sure to answer all questions before submitting.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="quiz-container" id="quizResults" style="display:none;">
            <div class="card">
                <div class="card-body text-center py-5">
                    <h4 class="fw-bold mb-4">Quiz Results</h4>
                    <div class="result-circle pass mx-auto mb-4" id="resultCircle">
                        <div class="score-num" id="resultScore">0</div>
                        <div class="score-label">Score</div>
                    </div>
                    <div class="row justify-content-center g-3 mb-4">
                        <div class="col-auto">
                            <div class="px-4 py-2" style="background: #d1fae5; border-radius: var(--radius);">
                                <div class="fw-bold" style="color: var(--success); font-size: 1.3rem;" id="resultCorrect">0</div>
                                <small class="text-muted">Correct</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="px-4 py-2" style="background: #fee2e2; border-radius: var(--radius);">
                                <div class="fw-bold" style="color: var(--danger); font-size: 1.3rem;" id="resultIncorrect">0</div>
                                <small class="text-muted">Incorrect</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="px-4 py-2" style="background: var(--gray-100); border-radius: var(--radius);">
                                <div class="fw-bold" style="color: var(--gray-600); font-size: 1.3rem;" id="resultUnanswered">0</div>
                                <small class="text-muted">Unanswered</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h2 class="fw-bold" style="color: var(--primary);" id="resultPercentage">0%</h2>
                        <p class="text-muted mb-0" id="resultMessage">Great job!</p>
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="quiz.php" class="btn btn-outline-primary"><i class="fas fa-redo me-2"></i>Retake Quiz</a>
                        <a href="progress.php" class="btn btn-primary"><i class="fas fa-chart-line me-2"></i>View Progress</a>
                    </div>
                </div>
            </div>

            <div class="mt-4" id="reviewSection">
                <h5 class="fw-bold mb-3"><i class="fas fa-search me-2" style="color: var(--primary);"></i>Review Answers</h5>
                <?php foreach ($questions as $i => $q): ?>
                <div class="quiz-question review-question" data-review-qid="<?php echo $q['id']; ?>">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="mb-0">Question <?php echo $i + 1; ?></h6>
                        <span class="review-status"></span>
                    </div>
                    <p><?php echo $q['question']; ?></p>
                    <div class="quiz-options-list">
                        <?php foreach ($q['options'] as $j => $opt): ?>
                        <div class="quiz-option disabled" data-opt-index="<?php echo $j; ?>">
                            <span class="d-flex align-items-center gap-2">
                                <span class="option-letter badge bg-light text-dark" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                    <?php echo chr(65 + $j); ?>
                                </span>
                                <?php echo $opt; ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>

<script>
const questions = <?php echo json_encode($questions); ?>;
const totalQuestions = <?php echo $totalQuestions; ?>;
const timeLimit = <?php echo $timeLimit; ?>;
const userAnswers = new Array(totalQuestions).fill(null);
let currentQuestion = 0;
let timerInterval;
let timeRemaining = timeLimit * 60;
let quizSubmitted = false;

function startTimer() {
    timerInterval = setInterval(() => {
        timeRemaining--;
        const mins = Math.floor(timeRemaining / 60);
        const secs = timeRemaining % 60;
        document.getElementById('quizTimer').textContent = mins + ':' + String(secs).padStart(2, '0');
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            submitQuiz();
        }
    }, 1000);
}

function navigateQuestion(dir) {
    const newIndex = currentQuestion + dir;
    if (newIndex < 0 || newIndex >= totalQuestions) return;
    goToQuestion(newIndex);
}

function goToQuestion(index) {
    document.querySelectorAll('.quiz-question').forEach((el, i) => {
        el.style.display = i === index ? 'block' : 'none';
    });
    currentQuestion = index;
    document.getElementById('currentQuestionNum').textContent = index + 1;
    document.getElementById('prevBtn').disabled = index === 0;
    document.getElementById('nextBtn').style.display = index === totalQuestions - 1 ? 'none' : 'inline-block';
    document.getElementById('submitBtn').style.display = index === totalQuestions - 1 ? 'inline-block' : 'none';
    document.querySelectorAll('.quiz-nav-btn').forEach((btn, i) => {
        btn.classList.toggle('current', i === index);
    });
}

function selectOption(questionId, optIndex) {
    if (quizSubmitted) return;
    const qIndex = questions.findIndex(q => q.id === questionId);
    if (qIndex === -1) return;
    userAnswers[qIndex] = optIndex;
    const container = document.querySelector(`.quiz-question[data-qid="${questionId}"]`);
    container.querySelectorAll('.quiz-option').forEach(el => {
        el.classList.toggle('selected', parseInt(el.dataset.optIndex) === optIndex);
    });
    document.querySelectorAll('.quiz-nav-btn')[qIndex].classList.add('answered');
}

function submitQuiz() {
    if (quizSubmitted) return;
    let answeredCount = userAnswers.filter(a => a !== null).length;
    if (answeredCount < totalQuestions) {
        if (!confirm(`You have ${totalQuestions - answeredCount} unanswered question(s). Are you sure you want to submit?`)) {
            return;
        }
    }
    quizSubmitted = true;
    clearInterval(timerInterval);
    let correctCount = 0;
    let incorrectCount = 0;
    let unansweredCount = 0;

    userAnswers.forEach((answer, i) => {
        const q = questions[i];
        const navBtn = document.querySelectorAll('.quiz-nav-btn')[i];
        if (answer === null) {
            unansweredCount++;
            navBtn.classList.add('incorrect-answer');
        } else if (answer === q.correct) {
            correctCount++;
            navBtn.classList.add('correct-answer');
        } else {
            incorrectCount++;
            navBtn.classList.add('incorrect-answer');
        }
    });

    const percentage = Math.round((correctCount / totalQuestions) * 100);
    document.getElementById('resultScore').textContent = correctCount + '/' + totalQuestions;
    document.getElementById('resultCorrect').textContent = correctCount;
    document.getElementById('resultIncorrect').textContent = incorrectCount;
    document.getElementById('resultUnanswered').textContent = unansweredCount;
    document.getElementById('resultPercentage').textContent = percentage + '%';
    const circle = document.getElementById('resultCircle');
    if (percentage >= 60) {
        circle.className = 'result-circle pass mx-auto mb-4';
        document.getElementById('resultMessage').textContent = 'Congratulations! You passed the quiz!';
    } else {
        circle.className = 'result-circle fail mx-auto mb-4';
        document.getElementById('resultMessage').textContent = 'Keep practicing! You can retake this quiz.';
    }

    document.querySelectorAll('.quiz-option').forEach(el => el.classList.add('disabled'));

    document.querySelectorAll('.review-question').forEach((reviewEl) => {
        const qid = parseInt(reviewEl.dataset.reviewQid);
        const qIndex = questions.findIndex(q => q.id === qid);
        const q = questions[qIndex];
        const answer = userAnswers[qIndex];
        const options = reviewEl.querySelectorAll('.quiz-option');
        options.forEach((opt, j) => {
            if (j === q.correct) opt.classList.add('correct');
            if (answer !== null && j === answer && answer !== q.correct) opt.classList.add('incorrect');
            if (answer !== null && j === answer && answer === q.correct) opt.classList.add('selected');
        });
        const statusEl = reviewEl.querySelector('.review-status');
        if (answer === null) {
            statusEl.innerHTML = '<span class="badge badge-warning">Unanswered</span>';
        } else if (answer === q.correct) {
            statusEl.innerHTML = '<span class="badge badge-success">Correct</span>';
        } else {
            statusEl.innerHTML = '<span class="badge badge-danger">Incorrect</span>';
        }
    });

    document.getElementById('quizApp').style.display = 'none';
    document.getElementById('quizResults').style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.addEventListener('DOMContentLoaded', () => {
    startTimer();
    document.querySelectorAll('.quiz-option').forEach(el => {
        el.addEventListener('click', function() {
            const qEl = this.closest('.quiz-question');
            if (!qEl) return;
            const qid = parseInt(qEl.dataset.qid);
            const optIndex = parseInt(this.dataset.optIndex);
            selectOption(qid, optIndex);
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
