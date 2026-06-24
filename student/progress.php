<?php
session_start();
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php', 'Please login to view your progress', 'warning');
}

$pageTitle = 'Progress Tracking';
$hideNavbar = true;
require_once '../includes/header.php';

$userId = $_SESSION['user_id'];
$user = getUser($userId);

$overallStats = [
    'total_lessons' => 235,
    'completed_lessons' => 119,
    'total_hours' => 187,
    'quiz_taken' => 24,
    'avg_score' => 82,
    'streak_days' => 7,
];

$courseProgress = [
    [
        'id' => 1, 'title' => 'Complete Web Development Bootcamp',
        'progress' => 75, 'status' => 'in_progress',
        'lessons' => [
            ['name' => 'Introduction to HTML', 'completed' => true, 'duration' => '45 min'],
            ['name' => 'CSS Fundamentals', 'completed' => true, 'duration' => '60 min'],
            ['name' => 'JavaScript Basics', 'completed' => true, 'duration' => '90 min'],
            ['name' => 'DOM Manipulation', 'completed' => true, 'duration' => '55 min'],
            ['name' => 'Responsive Design', 'completed' => true, 'duration' => '50 min'],
            ['name' => 'React Fundamentals', 'completed' => false, 'duration' => '120 min'],
            ['name' => 'State Management', 'completed' => false, 'duration' => '80 min'],
            ['name' => 'Final Project', 'completed' => false, 'duration' => '180 min'],
        ],
        'time_spent' => '42h 30m',
        'quiz_scores' => [
            ['quiz' => 'HTML Basics Quiz', 'score' => 90, 'total' => 100, 'date' => '2026-05-10'],
            ['quiz' => 'CSS Styling Quiz', 'score' => 85, 'total' => 100, 'date' => '2026-05-18'],
            ['quiz' => 'JavaScript Fundamentals Quiz', 'score' => 78, 'total' => 100, 'date' => '2026-05-25'],
            ['quiz' => 'React Basics Quiz', 'score' => 92, 'total' => 100, 'date' => '2026-06-02'],
        ],
    ],
    [
        'id' => 2, 'title' => 'Python for Data Science & Machine Learning',
        'progress' => 45, 'status' => 'in_progress',
        'lessons' => [
            ['name' => 'Python Basics', 'completed' => true, 'duration' => '60 min'],
            ['name' => 'NumPy & Pandas', 'completed' => true, 'duration' => '90 min'],
            ['name' => 'Data Visualization', 'completed' => true, 'duration' => '75 min'],
            ['name' => 'Data Cleaning Techniques', 'completed' => false, 'duration' => '60 min'],
            ['name' => 'Machine Learning Intro', 'completed' => false, 'duration' => '120 min'],
        ],
        'time_spent' => '28h 15m',
        'quiz_scores' => [
            ['quiz' => 'Python Basics Quiz', 'score' => 95, 'total' => 100, 'date' => '2026-05-28'],
            ['quiz' => 'NumPy & Pandas Quiz', 'score' => 82, 'total' => 100, 'date' => '2026-06-03'],
        ],
    ],
    [
        'id' => 3, 'title' => 'UI/UX Design Masterclass',
        'progress' => 100, 'status' => 'completed',
        'lessons' => [
            ['name' => 'Design Principles', 'completed' => true, 'duration' => '45 min'],
            ['name' => 'Color Theory', 'completed' => true, 'duration' => '50 min'],
            ['name' => 'Typography', 'completed' => true, 'duration' => '40 min'],
            ['name' => 'Wireframing', 'completed' => true, 'duration' => '60 min'],
            ['name' => 'Prototyping in Figma', 'completed' => true, 'duration' => '90 min'],
            ['name' => 'User Testing', 'completed' => true, 'duration' => '45 min'],
        ],
        'time_spent' => '35h 00m',
        'quiz_scores' => [
            ['quiz' => 'Design Fundamentals Quiz', 'score' => 88, 'total' => 100, 'date' => '2026-04-15'],
            ['quiz' => 'Figma Tools Quiz', 'score' => 94, 'total' => 100, 'date' => '2026-04-22'],
            ['quiz' => 'UX Principles Quiz', 'score' => 91, 'total' => 100, 'date' => '2026-05-01'],
        ],
    ],
];

$weeklyHours = [8.5, 10.2, 6.0, 12.5, 7.8, 5.0, 0];
$weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
$maxWeekly = max($weeklyHours) ?: 1;
?>

<style>
:root { --sidebar-width: 260px; }

.stat-card-sm {
    background: var(--white);
    border-radius: var(--radius);
    padding: 1rem 1.2rem;
    box-shadow: var(--shadow-sm);
    text-align: center;
}
.stat-card-sm .num {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--primary);
}
.stat-card-sm .label {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin: 0;
}

.checklist-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.6rem 0;
    border-bottom: 1px solid var(--gray-100);
    font-size: 0.9rem;
}
.checklist-item:last-child { border-bottom: none; }
.checklist-item .check-icon {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.7rem;
}
.checklist-item .check-icon.done { background: #d1fae5; color: var(--success); }
.checklist-item .check-icon.pending { background: var(--gray-100); color: var(--gray-400); }
.checklist-item .lesson-name { flex: 1; }
.checklist-item .lesson-name.completed { text-decoration: line-through; color: var(--gray-400); }
.checklist-item .lesson-duration { color: var(--gray-400); font-size: 0.8rem; }

.quiz-score-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-100);
    font-size: 0.9rem;
}
.quiz-score-row:last-child { border-bottom: none; }
.quiz-score-row .q-name { flex: 1; }
.quiz-score-row .q-date { color: var(--gray-400); font-size: 0.8rem; }
.quiz-score-row .q-score { font-weight: 700; white-space: nowrap; }

.time-stat {
    text-align: center;
    padding: 1.5rem 1rem;
}
.time-stat .time-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary);
}
.time-stat .time-label {
    color: var(--gray-500);
    font-size: 0.85rem;
    margin: 0;
}

.progress-course-header {
    cursor: pointer;
    user-select: none;
}
.progress-course-header:hover { background: var(--gray-50); }
</style>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div>
                <h4>Progress Tracking</h4>
                <p class="text-muted mb-0">Monitor your learning progress and performance.</p>
            </div>
            <span class="badge badge-primary" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                <i class="fas fa-fire me-1"></i><?php echo $overallStats['streak_days']; ?>-Day Streak
            </span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-4 col-lg-2">
                <div class="stat-card-sm">
                    <div class="num"><?php echo $overallStats['completed_lessons']; ?>/<?php echo $overallStats['total_lessons']; ?></div>
                    <p class="label">Lessons Done</p>
                </div>
            </div>
            <div class="col-4 col-lg-2">
                <div class="stat-card-sm">
                    <div class="num"><?php echo round(($overallStats['completed_lessons'] / $overallStats['total_lessons']) * 100); ?>%</div>
                    <p class="label">Overall Progress</p>
                </div>
            </div>
            <div class="col-4 col-lg-2">
                <div class="stat-card-sm">
                    <div class="num"><?php echo $overallStats['total_hours']; ?>h</div>
                    <p class="label">Total Time</p>
                </div>
            </div>
            <div class="col-4 col-lg-2">
                <div class="stat-card-sm">
                    <div class="num"><?php echo $overallStats['quiz_taken']; ?></div>
                    <p class="label">Quizzes Taken</p>
                </div>
            </div>
            <div class="col-4 col-lg-2">
                <div class="stat-card-sm">
                    <div class="num"><?php echo $overallStats['avg_score']; ?>%</div>
                    <p class="label">Avg Quiz Score</p>
                </div>
            </div>
            <div class="col-4 col-lg-2">
                <div class="stat-card-sm">
                    <div class="num" style="color: var(--warning);"><?php echo $overallStats['streak_days']; ?></div>
                    <p class="label">Day Streak</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-clock me-2" style="color: var(--primary);"></i>Hours Spent This Week</h6>
                        <div class="d-flex align-items-end gap-2" style="height: 150px;">
                            <?php foreach ($weekDays as $i => $day):
                                $pct = ($weeklyHours[$i] / $maxWeekly) * 100;
                                $isToday = $i == date('N') - 1;
                            ?>
                            <div class="flex-grow-1 d-flex flex-column align-items-center">
                                <small class="fw-bold mb-1" style="color: var(--primary); font-size: 0.75rem;"><?php echo $weeklyHours[$i] > 0 ? $weeklyHours[$i] . 'h' : ''; ?></small>
                                <div class="w-100" style="background: var(--gray-100); border-radius: var(--radius-sm) var(--radius-sm) 0 0; flex: 1; width: 100%; position: relative;">
                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: <?php echo $pct; ?>%; background: <?php echo $isToday ? 'var(--primary)' : 'var(--primary-bg)'; ?>; border-radius: var(--radius-sm) var(--radius-sm) 0 0; transition: var(--transition);"></div>
                                </div>
                                <small class="mt-1" style="font-size: 0.7rem; color: var(--gray-500);"><?php echo $day; ?></small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="fw-bold mb-3"><i class="fas fa-hourglass-half me-2" style="color: var(--info);"></i>Time Breakdown</h6>
                        <div class="time-stat">
                            <div class="time-value"><?php echo $overallStats['total_hours']; ?>h</div>
                            <p class="time-label">Total Learning Time</p>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-center py-2" style="background: var(--primary-bg); border-radius: var(--radius);">
                                    <div class="fw-bold" style="color: var(--primary);"><?php echo $overallStats['total_hours'] * 0.6; ?>h</div>
                                    <small class="text-muted">Lectures</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center py-2" style="background: #d1fae5; border-radius: var(--radius);">
                                    <div class="fw-bold" style="color: var(--success);"><?php echo $overallStats['total_hours'] * 0.25; ?>h</div>
                                    <small class="text-muted">Quizzes</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center py-2" style="background: #fef3c7; border-radius: var(--radius);">
                                    <div class="fw-bold" style="color: var(--warning);"><?php echo $overallStats['total_hours'] * 0.1; ?>h</div>
                                    <small class="text-muted">Projects</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center py-2" style="background: #ede9fe; border-radius: var(--radius);">
                                    <div class="fw-bold" style="color: #7c3aed;"><?php echo $overallStats['total_hours'] * 0.05; ?>h</div>
                                    <small class="text-muted">Discussion</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach ($courseProgress as $course): ?>
        <?php
            $isCompleted = $course['progress'] >= 100;
            $completedLessons = count(array_filter($course['lessons'], fn($l) => $l['completed']));
            $totalLessons = count($course['lessons']);
            $avgQuizScore = count($course['quiz_scores']) > 0
                ? round(array_sum(array_column($course['quiz_scores'], 'score')) / count($course['quiz_scores']))
                : 0;
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 progress-course-header" data-bs-toggle="collapse" data-bs-target="#course-<?php echo $course['id']; ?>-detail">
                    <div>
                        <h6 class="fw-bold mb-1"><?php echo $course['title']; ?></h6>
                        <div class="d-flex align-items-center gap-3">
                            <small class="text-muted"><i class="fas fa-check-circle me-1"></i><?php echo $completedLessons; ?>/<?php echo $totalLessons; ?> lessons</small>
                            <small class="text-muted"><i class="fas fa-clock me-1"></i><?php echo $course['time_spent']; ?></small>
                            <small class="text-muted"><i class="fas fa-chart-bar me-1"></i>Avg Quiz: <?php echo $avgQuizScore; ?>%</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 120px;">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold" style="color: var(--primary);"><?php echo $course['progress']; ?>%</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" style="width: <?php echo $course['progress']; ?>%; <?php echo $isCompleted ? 'background: var(--success);' : ''; ?>"></div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </div>

                <div class="collapse show" id="course-<?php echo $course['id']; ?>-detail">
                    <div class="row g-4 mt-1">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2" style="font-size: 0.85rem; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fas fa-tasks me-1"></i>Lesson Checklist
                            </h6>
                            <div style="max-height: 260px; overflow-y: auto;">
                                <?php foreach ($course['lessons'] as $lesson): ?>
                                <div class="checklist-item">
                                    <div class="check-icon <?php echo $lesson['completed'] ? 'done' : 'pending'; ?>">
                                        <i class="fas <?php echo $lesson['completed'] ? 'fa-check' : 'fa-times'; ?>"></i>
                                    </div>
                                    <span class="lesson-name <?php echo $lesson['completed'] ? 'completed' : ''; ?>"><?php echo $lesson['name']; ?></span>
                                    <span class="lesson-duration"><?php echo $lesson['duration']; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2" style="font-size: 0.85rem; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fas fa-poll me-1"></i>Quiz Scores
                            </h6>
                            <?php if (count($course['quiz_scores']) > 0): ?>
                                <div style="max-height: 260px; overflow-y: auto;">
                                    <?php foreach ($course['quiz_scores'] as $qs): ?>
                                    <div class="quiz-score-row">
                                        <span class="q-name"><?php echo $qs['quiz']; ?></span>
                                        <span class="q-date"><?php echo date('M d', strtotime($qs['date'])); ?></span>
                                        <span class="q-score" style="color: <?php echo $qs['score'] >= 80 ? 'var(--success)' : ($qs['score'] >= 60 ? 'var(--warning)' : 'var(--danger)'); ?>;">
                                            <?php echo $qs['score']; ?>/<?php echo $qs['total']; ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3 mb-0">No quizzes taken yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
