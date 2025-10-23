<?php
session_start();
include '../config.php';

// 🔒 Correct session validation
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

// 📚 Quiz category setup
$category_id = 1; // HTML quiz category ID
$category_name = "HTML";

// ✅ Fetch quiz questions
$result = $conn->query("SELECT * FROM quizzes WHERE category_id = $category_id ORDER BY id ASC");

// 🧮 Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total = $result->num_rows;
    $answers = [];

    $result->data_seek(0);

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $correct = strtoupper(trim($row['correct_option']));
        $answer = isset($_POST['quiz_' . $id]) ? strtoupper(trim($_POST['quiz_' . $id])) : 'N/A';
        $answers[$id] = [$answer, $correct];
        if ($answer === $correct) $score++;
    }

    echo "<div class='container my-5'>";
    echo "<h2 class='text-center mb-3'>$category_name Quiz Results</h2>";
    echo "<p class='text-center fs-5'>You scored <strong>$score / $total</strong></p>";
    echo "<hr>";

    echo "<div class='row justify-content-center'>";
    foreach ($answers as $id => $ans) {
        $row = $conn->query("SELECT * FROM quizzes WHERE id=$id")->fetch_assoc();
        $isCorrect = $ans[0] === $ans[1];
        echo "<div class='card mb-3' style='max-width:700px; border-left: 6px solid ".($isCorrect ? "green" : "red").";'>";
        echo "<div class='card-body'>";
        echo "<p class='card-title'><strong>Q:</strong> ".htmlspecialchars($row['question'])."</p>";
        echo "<p class='mb-1'><strong>Your Answer:</strong> ".$ans[0]." ".($isCorrect ? "✅" : "❌")."</p>";
        if (!$isCorrect) {
            echo "<p class='mb-0'><strong>Correct Answer:</strong> ".$ans[1]."</p>";
        }
        echo "</div></div>";
    }
    echo "</div>";

    echo "<div class='text-center mt-4'>
            <a href='quiz_html.php' class='btn btn-secondary me-2'>🔁 Retake Quiz</a>
            <a href='quizzes.php' class='btn btn-primary'>🏠 Back to Categories</a>
          </div>";
    echo "</div>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($category_name) ?> Quiz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4"><?= htmlspecialchars($category_name) ?> Quiz</h2>
    <div class="mb-3 text-center">
        <a href="quizzes.php" class="btn btn-outline-primary me-2">← Back to Categories</a>
        <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
    </div>

    <form method="POST">
        <?php while ($q = $result->fetch_assoc()): ?>
        <div class="card mb-4">
            <div class="card-body">
                <p class="card-title"><strong><?= htmlspecialchars($q['question']) ?></strong></p>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="quiz_<?= $q['id'] ?>" value="A" required>
                    <label class="form-check-label"><?= htmlspecialchars($q['option_a']) ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="quiz_<?= $q['id'] ?>" value="B">
                    <label class="form-check-label"><?= htmlspecialchars($q['option_b']) ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="quiz_<?= $q['id'] ?>" value="C">
                    <label class="form-check-label"><?= htmlspecialchars($q['option_c']) ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="quiz_<?= $q['id'] ?>" value="D">
                    <label class="form-check-label"><?= htmlspecialchars($q['option_d']) ?></label>
                </div>
            </div>
        </div>
        <?php endwhile; ?>

        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg">Submit Quiz</button>
        </div>
    </form>
</div>

</body>
</html>
