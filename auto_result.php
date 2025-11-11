<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['auto_quiz_answers'])) {
    header('Location: auto_quiz.php');
    exit;
}

$submitted = $_POST['answers'] ?? [];
$correctMap = $_SESSION['auto_quiz_answers'];
$total = count($correctMap);
$score = 0;
$feedback = [];

foreach ($correctMap as $qid => $correct) {
    $given = isset($submitted[$qid]) ? $submitted[$qid] : null;
    $isCorrect = ($given !== null && strval($given) === strval($correct));
    if ($isCorrect) $score++;

    // fetch question text and options for feedback
    $stmt = $conn->prepare('SELECT question, option_a, option_b, option_c, option_d FROM quizzes WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $qid);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $feedback[] = [
        'id' => $qid,
        'question' => $row ? $row['question'] : '',
        'options' => $row ? [ 'a'=>$row['option_a'],'b'=>$row['option_b'],'c'=>$row['option_c'],'d'=>$row['option_d'] ] : [],
        'correct' => $correct,
        'given' => $given,
        'is_correct' => $isCorrect
    ];
}

// Clear stored answers
unset($_SESSION['auto_quiz_answers']);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>Quiz Results</title>
</head>
<body>
<?php include 'nav.php'; ?>
<main class="container mt-5 pt-4">
  <h2>Your Results</h2>
  <div class="alert alert-info">Score: <strong><?= $score ?>/<?= $total ?></strong></div>

  <?php foreach ($feedback as $i => $f): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5>Q<?= $i+1 ?>: <?= htmlspecialchars($f['question']) ?></h5>
        <ul class="list-group mt-2">
          <?php foreach ($f['options'] as $k => $opt): ?>
            <?php
              $cls = '';
              if ($k === $f['correct']) $cls = 'list-group-item-success';
              if ($f['given'] !== null && $k === $f['given'] && $k !== $f['correct']) $cls = 'list-group-item-danger';
            ?>
            <li class="list-group-item <?= $cls ?>">
              <strong><?= strtoupper($k) ?>.</strong> <?= htmlspecialchars($opt) ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <p class="mt-2">Your answer: <strong><?= $f['given'] ? strtoupper($f['given']) : 'No answer' ?></strong></p>
        <p>Status: <strong><?= $f['is_correct'] ? 'Correct' : 'Incorrect' ?></strong></p>
      </div>
    </div>
  <?php endforeach; ?>

  <a href="auto_quiz.php" class="btn btn-primary">Take another quiz</a>
</main>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
