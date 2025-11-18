<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}
include '../config.php';

// --- Delete Quiz ---
$toast_message = '';
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $conn->query("DELETE FROM quizzes WHERE id = $id");
  $toast_message = "Quiz deleted successfully!";
}

// --- Fetch Data ---
$quizzes = $conn->query("
  SELECT q.*, c.name AS category
  FROM quizzes q
  JOIN quiz_categories c ON q.category_id = c.id
  ORDER BY q.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Quizzes</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-page d-flex">

<?php include 'sidebar.php'; ?>

<div class="container-fluid p-4" style="max-height:100vh;overflow-y:auto;">
  <div class="admin-hero text-white p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <h2 class="mb-1 fw-bold">Quiz Bank</h2>
      <p class="mb-0">Oversee every quiz question and keep assessments fresh.</p>
    </div>
    <a href="add_quiz.php" class="btn btn-light admin-quick-action"><i class="bi bi-plus-circle me-1"></i>Add Quiz</a>
  </div>

  <div class="admin-card admin-card-hover">
    <div class="card-body table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Question</th>
            <th>Correct Option</th>
            <th width="120">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($quizzes->num_rows > 0): ?>
            <?php while($q = $quizzes->fetch_assoc()): ?>
              <tr>
                <td><?= $q['id'] ?></td>
                <td><?= htmlspecialchars($q['category']) ?></td>
                <td><?= htmlspecialchars($q['question']) ?></td>
                <td><?= $q['correct_option'] ?></td>
                <td>
                  <a href="?delete=<?= $q['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this quiz?')">
                    <i class="bi bi-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center">No quizzes found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
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
