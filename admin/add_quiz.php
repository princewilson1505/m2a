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
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-page d-flex">

<?php include 'sidebar.php'; ?>

<div class="container p-4" style="max-height:100vh;overflow-y:auto;">
  <div class="admin-hero text-white p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <h2 class="mb-1 fw-bold">Create Quiz Item</h2>
      <p class="mb-0">Craft balanced multiple-choice questions aligned with categories.</p>
    </div>
    <a href="manage_quiz.php" class="btn btn-light admin-quick-action"><i class="bi bi-arrow-left me-1"></i>Back to List</a>
  </div>

  <div class="admin-card p-4">
      <form method="POST" class="admin-form">
        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select admin-pill-input" required>
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
            <input type="text" name="option_a" class="form-control admin-pill-input" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Option B</label>
            <input type="text" name="option_b" class="form-control admin-pill-input" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Option C</label>
            <input type="text" name="option_c" class="form-control admin-pill-input" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Option D</label>
            <input type="text" name="option_d" class="form-control admin-pill-input" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Correct Answer (A/B/C/D)</label>
          <input type="text" name="correct_option" maxlength="1" class="form-control admin-pill-input" required>
        </div>

        <button type="submit" name="add_quiz" class="btn admin-gradient-btn"><i class="bi bi-plus-circle me-1"></i>Add Quiz</button>
      </form>
    </div>
  </div>
</div>

<!-- Toast -->
<?php if ($toast_message): ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
  <div id="liveToast" class="toast admin-toast align-items-center text-bg-success border-0 show" role="alert">
    <div class="d-flex">
      <div class="toast-body"><?= htmlspecialchars($toast_message) ?></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<?php endif; ?>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
