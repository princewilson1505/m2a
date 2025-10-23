<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}
include '../config.php';

// --- Add Quiz ---
$toast_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_quiz'])) {
  $category_id   = $_POST['category_id'];
  $question      = $_POST['question'];
  $option_a      = $_POST['option_a'];
  $option_b      = $_POST['option_b'];
  $option_c      = $_POST['option_c'];
  $option_d      = $_POST['option_d'];
  $correct_option= $_POST['correct_option'];

  $stmt = $conn->prepare(
    "INSERT INTO quizzes (category_id, question, option_a, option_b, option_c, option_d, correct_option)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
  );
  $stmt->bind_param("issssss", $category_id, $question, $option_a, $option_b, $option_c, $option_d, $correct_option);
  $stmt->execute();

  $toast_message = "Quiz added successfully!";
}

// --- Fetch Categories ---
$categories = $conn->query("SELECT * FROM quiz_categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Quiz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex">

<?php include 'sidebar.php'; ?>

<div class="container p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Quiz</h2>
    <a href="manage_quiz.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
  </div>

  <div class="card">
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select" required>
            <option value="">-- Select Category --</option>
            <?php while($c = $categories->fetch_assoc()): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Question</label>
          <textarea name="question" class="form-control" rows="3" required></textarea>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Option A</label>
            <input type="text" name="option_a" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Option B</label>
            <input type="text" name="option_b" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Option C</label>
            <input type="text" name="option_c" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Option D</label>
            <input type="text" name="option_d" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Correct Answer (A/B/C/D)</label>
          <input type="text" name="correct_option" maxlength="1" class="form-control" required>
        </div>

        <button type="submit" name="add_quiz" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Quiz</button>
      </form>
    </div>
  </div>
</div>

<!-- Toast -->
<?php if ($toast_message): ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
  <div id="liveToast" class="toast align-items-center text-bg-success border-0 show" role="alert">
    <div class="d-flex">
      <div class="toast-body"><?= htmlspecialchars($toast_message) ?></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
