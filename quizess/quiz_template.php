<?php
// Single-question flow (server-driven) — similar to auto_quiz.php UI
if (session_status() === PHP_SESSION_NONE) session_start();

// Fetch question IDs for this category
$ids = [];
$idRes = $conn->query("SELECT id FROM quizzes WHERE category_id = $category_id ORDER BY id ASC");
if ($idRes) {
        while ($r = $idRes->fetch_assoc()) $ids[] = (int)$r['id'];
}

// Clear quiz session if requested (retake)
if (isset($_GET['new'])) {
        unset($_SESSION['quiz_ids']);
        unset($_SESSION['quiz_index']);
        unset($_SESSION['quiz_answers']);
        unset($_SESSION['quiz_metadata']);
        unset($_SESSION['current_feedback']);
}

// Handle start / answer actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action']) && $_POST['action'] === 'start') {
                if (empty($ids)) {
                        $error = 'No questions available for this category.';
                } else {
                        $_SESSION['quiz_ids'] = $ids;
                        $_SESSION['quiz_index'] = 0;
                        $_SESSION['quiz_answers'] = [];
                        $_SESSION['quiz_metadata'] = [];

                        $idList = implode(',', $ids);
                        $qRes = $conn->query("SELECT id, question, option_a, option_b, option_c, option_d, correct_option FROM quizzes WHERE id IN ($idList) ORDER BY FIELD(id, $idList)");
                        if ($qRes) {
                                while ($row = $qRes->fetch_assoc()) {
                                        $_SESSION['quiz_metadata'][$row['id']] = [
                                                'question' => $row['question'],
                                                'option_a' => $row['option_a'],
                                                'option_b' => $row['option_b'],
                                                'option_c' => $row['option_c'],
                                                'option_d' => $row['option_d'],
                                                'correct_option' => $row['correct_option']
                                        ];
                                }
                        }

                        // Redirect to show first question
                        header('Location: ' . $_SERVER['REQUEST_URI']);
                        exit;
                }
        }
        elseif (isset($_POST['action']) && $_POST['action'] === 'answer') {
                $qid = isset($_POST['qid']) ? (int)$_POST['qid'] : null;
                $ans = isset($_POST['answer']) ? $_POST['answer'] : null;
                if ($qid && $ans && isset($_SESSION['quiz_metadata'][$qid])) {
                        $_SESSION['quiz_answers'][$qid] = $ans;
                        $correct = $_SESSION['quiz_metadata'][$qid]['correct_option'];
                        $isCorrect = (strtolower($ans) === strtolower($correct));
                        $_SESSION['current_feedback'] = [
                                'qid' => $qid,
                                'question' => $_SESSION['quiz_metadata'][$qid]['question'],
                                'options' => [
                                        'A' => $_SESSION['quiz_metadata'][$qid]['option_a'],
                                        'B' => $_SESSION['quiz_metadata'][$qid]['option_b'],
                                        'C' => $_SESSION['quiz_metadata'][$qid]['option_c'],
                                        'D' => $_SESSION['quiz_metadata'][$qid]['option_d']
                                ],
                                'userAnswer' => strtoupper($ans),
                                'correctAnswer' => strtoupper($correct),
                                'isCorrect' => $isCorrect
                        ];
                        $_SESSION['quiz_index']++;

                        header('Location: ' . $_SERVER['REQUEST_URI']);
                        exit;
                }
        }
}

// Load quiz state
$currentQuestion = null;
$feedback = null;
$totalQuestions = 0;
$currentIndex = 0;
if (isset($_SESSION['quiz_ids']) && isset($_SESSION['quiz_index'])) {
        $totalQuestions = count($_SESSION['quiz_ids']);
        $currentIndex = $_SESSION['quiz_index'];
        if (isset($_SESSION['current_feedback'])) {
                $feedback = $_SESSION['current_feedback'];
                unset($_SESSION['current_feedback']);
        }
        if ($currentIndex < $totalQuestions) {
                $qid = $_SESSION['quiz_ids'][$currentIndex];
                if (isset($_SESSION['quiz_metadata'][$qid])) {
                        $currentQuestion = array_merge(['id' => $qid], $_SESSION['quiz_metadata'][$qid]);
                }
        }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($category_name) ?> Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        main{padding-top:30px}
        .option-label{cursor:pointer}
    </style>
</head>
<body>
<?php include_once __DIR__ . "/../nav.php"; ?>
<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="text-center mb-4"><?= htmlspecialchars($category_name) ?> Quiz</h2>
            <div class="mb-3 text-center">
                <a href="quizzes.php" class="btn btn-outline-primary me-2">← Back to Categories</a>
                <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
            </div>

            <?php if (!isset($_SESSION['quiz_ids'])): ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <div class="card">
                    <div class="card-body text-center">
                        <p class="lead">This quiz contains <?= count($ids) ?> questions.</p>
                        <form method="post">
                            <input type="hidden" name="action" value="start">
                            <button class="btn btn-primary btn-lg">Start Quiz</button>
                        </form>
                    </div>
                </div>

            <?php elseif ($currentIndex >= $totalQuestions): ?>
                <?php
                    $score = 0;
                    foreach ($_SESSION['quiz_ids'] as $qid) {
                        if (isset($_SESSION['quiz_answers'][$qid])) {
                            $ua = $_SESSION['quiz_answers'][$qid];
                            $correct = $_SESSION['quiz_metadata'][$qid]['correct_option'];
                            if (strtolower($ua) === strtolower($correct)) $score++;
                        }
                    }
                ?>
                <div class="card text-center">
                    <div class="card-body">
                        <h3>Quiz Complete!</h3>
                        <h1 class="display-4 my-4"><?= $score ?>/<?= $totalQuestions ?></h1>
                        <p class="lead">You scored <?= round(($score/$totalQuestions)*100) ?>%</p>
                        <a href="<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])) ?>?new=1" class="btn btn-primary mt-3">Take Another Quiz</a>
                    </div>
                </div>

            <?php elseif ($currentQuestion): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Question <?= $currentIndex + 1 ?> of <?= $totalQuestions ?></span>
                        <span><?= round((($currentIndex)/$totalQuestions)*100) ?>% Complete</span>
                    </div>
                    <div class="progress mb-3"><div class="progress-bar" role="progressbar" style="width: <?= round((($currentIndex)/$totalQuestions)*100) ?>%"></div></div>
                </div>

                <?php if ($feedback): ?>
                    <div class="alert <?= $feedback['isCorrect'] ? 'alert-success' : 'alert-danger' ?> mb-4">
                        <h5><?= $feedback['isCorrect'] ? '✓ Correct!' : '✗ Incorrect' ?></h5>
                        <p><strong>Your answer:</strong> <?= htmlspecialchars($feedback['userAnswer']) ?> — <?= htmlspecialchars($feedback['options'][$feedback['userAnswer']]) ?></p>
                        <?php if (!$feedback['isCorrect']): ?>
                            <p><strong>Correct answer:</strong> <?= htmlspecialchars($feedback['correctAnswer']) ?> — <?= htmlspecialchars($feedback['options'][$feedback['correctAnswer']]) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($currentQuestion['question']) ?></h5>
                        <form method="post" class="mt-3">
                            <input type="hidden" name="action" value="answer">
                            <input type="hidden" name="qid" value="<?= (int)$currentQuestion['id'] ?>">
                            <?php foreach (['A'=>'option_a','B'=>'option_b','C'=>'option_c','D'=>'option_d'] as $opt => $col): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="opt_<?= $opt ?>" name="answer" value="<?= $opt ?>" required>
                                    <label class="form-check-label option-label" for="opt_<?= $opt ?>"><?= strtoupper($opt) ?>. <?= htmlspecialchars($currentQuestion[$col]) ?></label>
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-primary mt-4 w-100">Submit Answer</button>
                        </form>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</main>

</body>
</html>