<?php
session_start();
require_once 'config.php';

// Fetch categories
$cats = [];
$categoryMap = [];
$cRes = $conn->query("SELECT id, name FROM quiz_categories ORDER BY name");
if ($cRes) {
    while ($r = $cRes->fetch_assoc()) {
        $cats[] = $r;
        $categoryMap[(int)$r['id']] = $r['name'];
    }
}

$error = '';
$currentQuestion = null;
$currentIndex = 0;
$totalQuestions = 0;
$feedback = null; // Stores feedback after answer submission

// Clear quiz session if new quiz requested
if (isset($_GET['new'])) {
    unset($_SESSION['quiz_ids']);
    unset($_SESSION['quiz_index']);
    unset($_SESSION['quiz_answers']);
    unset($_SESSION['quiz_metadata']);
    unset($_SESSION['current_feedback']);
    unset($_SESSION['quiz_run_meta']);
    unset($_SESSION['quiz_score_saved']);
}

// Handle form submission (start new quiz or answer question)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If starting a new quiz
    if (isset($_POST['action']) && $_POST['action'] === 'start') {
        $cat = isset($_POST['category']) && $_POST['category'] !== '' ? (int)$_POST['category'] : null;
        $count = isset($_POST['count']) ? max(1, (int)$_POST['count']) : 10;

        // Fetch randomized IDs first
        $ids = [];
        if ($cat) {
            $idStmt = $conn->prepare("SELECT id FROM quizzes WHERE category_id = ? ORDER BY RAND() LIMIT ?");
            $idStmt->bind_param('ii', $cat, $count);
        } else {
            $idStmt = $conn->prepare("SELECT id FROM quizzes ORDER BY RAND() LIMIT ?");
            $idStmt->bind_param('i', $count);
        }

        if ($idStmt && $idStmt->execute()) {
            $r = $idStmt->get_result();
            while ($row = $r->fetch_assoc()) $ids[] = (int)$row['id'];
            $idStmt->close();
        }

        if (empty($ids)) {
            $error = 'No questions found for the selected options.';
        } else {
            // Store quiz data in session
            $_SESSION['quiz_ids'] = $ids;
            $_SESSION['quiz_answers'] = array(); // Store user answers
            $_SESSION['quiz_index'] = 0;
            $_SESSION['quiz_metadata'] = array();

            // Fetch question details and correct answers
            $idList = implode(',', $ids);
            $mRes = $conn->query("SELECT id, question, option_a, option_b, option_c, option_d, correct_option FROM quizzes WHERE id IN ($idList) ORDER BY FIELD(id, $idList)");
            if ($mRes) {
                while ($row = $mRes->fetch_assoc()) {
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

            $_SESSION['quiz_run_meta'] = [
                'category_id' => $cat ?: null,
                'category_label' => $cat ? ($categoryMap[$cat] ?? 'Category ' . $cat) : 'Mixed',
                'question_count' => count($ids)
            ];
            $_SESSION['quiz_score_saved'] = false;
            
            // Redirect to display first question
            header('Location: auto_quiz.php');
            exit;
        }
    }
    // If answering a question
    elseif (isset($_POST['action']) && $_POST['action'] === 'answer') {
        $qid = isset($_POST['qid']) ? (int)$_POST['qid'] : null;
        $userAnswer = isset($_POST['answer']) ? $_POST['answer'] : null;

        if ($qid && $userAnswer && isset($_SESSION['quiz_metadata'][$qid])) {
            // Record user answer
            $_SESSION['quiz_answers'][$qid] = $userAnswer;

            // Check if correct
            $correct = $_SESSION['quiz_metadata'][$qid]['correct_option'];
            $isCorrect = (strtolower($userAnswer) === strtolower($correct));

            // Prepare feedback
            $feedback = [
                'qid' => $qid,
                'question' => $_SESSION['quiz_metadata'][$qid]['question'],
                'options' => [
                    'a' => $_SESSION['quiz_metadata'][$qid]['option_a'],
                    'b' => $_SESSION['quiz_metadata'][$qid]['option_b'],
                    'c' => $_SESSION['quiz_metadata'][$qid]['option_c'],
                    'd' => $_SESSION['quiz_metadata'][$qid]['option_d']
                ],
                'userAnswer' => $userAnswer,
                'correctAnswer' => $correct,
                'isCorrect' => $isCorrect
            ];

            $_SESSION['current_feedback'] = $feedback;

            // Move to next question
            $_SESSION['quiz_index']++;
            
            // Redirect to display feedback and next question
            header('Location: auto_quiz.php');
            exit;
        }
    }
}

// Load current quiz state if active
if (isset($_SESSION['quiz_ids']) && isset($_SESSION['quiz_index'])) {
    $totalQuestions = count($_SESSION['quiz_ids']);
    $currentIndex = $_SESSION['quiz_index'];

    // Load feedback if just answered
    if (isset($_SESSION['current_feedback'])) {
        $feedback = $_SESSION['current_feedback'];
        unset($_SESSION['current_feedback']);
    }

    // Load next question if quiz not finished
    if ($currentIndex < $totalQuestions) {
        $qid = $_SESSION['quiz_ids'][$currentIndex];
        if (isset($_SESSION['quiz_metadata'][$qid])) {
            $currentQuestion = array_merge(
                ['id' => $qid],
                $_SESSION['quiz_metadata'][$qid]
            );
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>Auto Quiz | M2a</title>
  <style>
    main { padding-top: 80px; }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>
<main class="container mt-4">
  <h2 class="mb-4">Quiz</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- STAGE 1: Start Quiz Form -->
  <?php if (!isset($_SESSION['quiz_ids'])): ?>
    <form method="post" class="row g-3">
      <input type="hidden" name="action" value="start">
      <div class="col-md-4">
        <label class="form-label">Category</label>
        <select name="category" class="form-select">
          <option value="">Any</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Questions</label>
        <input type="number" name="count" class="form-control" value="10" min="1" max="50">
      </div>
      <div class="col-12 align-self-end">
        <button class="btn btn-primary">Start Quiz</button>
      </div>
    </form>

  <!-- STAGE 2: Quiz Finished - Show Results -->
  <?php elseif ($currentIndex >= $totalQuestions): ?>
    <div class="card">
      <div class="card-body text-center">
        <h3>Quiz Complete!</h3>
        <?php
          $score = 0;
          foreach ($_SESSION['quiz_ids'] as $qid) {
            if (isset($_SESSION['quiz_answers'][$qid])) {
              $userAns = $_SESSION['quiz_answers'][$qid];
              $correct = $_SESSION['quiz_metadata'][$qid]['correct_option'];
              if (strtolower($userAns) === strtolower($correct)) $score++;
            }
          }
          $percentage = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;

          if (!($_SESSION['quiz_score_saved'] ?? false)) {
              $meta = $_SESSION['quiz_run_meta'] ?? [];
              $categoryId = isset($meta['category_id']) ? (int)$meta['category_id'] : null;
              $categoryLabel = $meta['category_label'] ?? ($categoryId && isset($categoryMap[$categoryId]) ? $categoryMap[$categoryId] : 'Mixed');
              $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

              $stmt = $conn->prepare("INSERT INTO quiz_scores (user_id, category_id, category_label, total_questions, correct_answers, percentage) VALUES (?, ?, ?, ?, ?, ?)");
              if ($stmt) {
                  $stmt->bind_param('iisiid', $userId, $categoryId, $categoryLabel, $totalQuestions, $score, $percentage);
                  $stmt->execute();
                  $stmt->close();
              }

              $_SESSION['quiz_score_saved'] = true;
          }
        ?>
        <h1 class="display-4 my-4"><?= $score ?>/<?= $totalQuestions ?></h1>
        <p class="lead">You scored <?= number_format($percentage, 2) ?>%</p>
        <a href="auto_quiz.php?new=1" class="btn btn-primary mt-3">Take Another Quiz</a>
      </div>
    </div>

  <!-- STAGE 3: Current Question -->
  <?php elseif ($currentQuestion): ?>
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <!-- Question Progress -->
        <div class="mb-4">
          <div class="d-flex justify-content-between mb-2">
            <span>Question <?= $currentIndex + 1 ?> of <?= $totalQuestions ?></span>
            <span><?= round((($currentIndex)/$totalQuestions)*100) ?>% Complete</span>
          </div>
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: <?= round((($currentIndex)/$totalQuestions)*100) ?>%" aria-valuenow="<?= $currentIndex ?>" aria-valuemin="0" aria-valuemax="<?= $totalQuestions ?>"></div>
          </div>
        </div>

        <!-- Feedback from Previous Answer (if any) -->
        <?php if ($feedback): ?>
          <div class="alert <?= $feedback['isCorrect'] ? 'alert-success' : 'alert-danger' ?> mb-4">
            <h5><?= $feedback['isCorrect'] ? '✓ Correct!' : '✗ Incorrect' ?></h5>
            <p><strong>Your answer:</strong> <?= strtoupper($feedback['userAnswer']) ?>. <?= $feedback['options'][$feedback['userAnswer']] ?></p>
            <p><strong>Correct answer:</strong> 
            <?= strtoupper($feedback['correctAnswer']) ?>. 
            <?= $feedback['options'][strtolower($feedback['correctAnswer'])] ?>
            </p>
          </div>
        <?php endif; ?>

        <!-- Current Question Card -->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($currentQuestion['question']) ?></h5>
            <form method="post" class="mt-4">
              <input type="hidden" name="action" value="answer">
              <input type="hidden" name="qid" value="<?= (int)$currentQuestion['id'] ?>">
              <div class="list-group">
                <?php foreach (['a', 'b', 'c', 'd'] as $opt): ?>
                  <?php $col = 'option_' . $opt; ?>
                  <label class="list-group-item">
                    <input type="radio" name="answer" value="<?= $opt ?>" required>
                    <span class="ms-2"><strong><?= strtoupper($opt) ?>.</strong> <?= htmlspecialchars($currentQuestion[$col]) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
              <button type="submit" class="btn btn-primary mt-4 w-100">Submit Answer</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  <?php endif; ?>

</main>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
