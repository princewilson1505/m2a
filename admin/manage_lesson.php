<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../config.php';

// detect success query params
$added = isset($_GET['added']) && $_GET['added'] == 1;
$deleted = isset($_GET['deleted']) && $_GET['deleted'] == 1;

$result = $conn->query("SELECT * FROM lessons");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Lessons | M2A Admin</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-page">
  <div class="d-flex admin-shell">
    <?php include 'sidebar.php'; ?>
    <div class="p-4 flex-grow-1" style="max-height:100vh;overflow-y:auto;">
      <div class="admin-hero text-white p-4 mb-4">
        <div class="d-flex justify-content-between flex-wrap gap-3 align-items-center">
          <div>
            <h2 class="fw-bold mb-1">Lesson Management</h2>
            <p class="mb-0">Create, edit, and curate every lesson that appears in the catalog.</p>
          </div>
          <a class="btn btn-light admin-quick-action" href="add_lesson.php">
            <i class="bi bi-plus-circle me-2"></i> Add Lesson
          </a>
        </div>
      </div>

      <div class="admin-card admin-card-hover p-3">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Date Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= $row['date_created'] ?></td>
            <td>
              <a class="btn btn-primary btn-sm text-light" href="edit_lesson.php?id=<?= $row['id'] ?>"><i class="bi bi-clipboard"></i> Edit</a>
              <button class="btn btn-danger btn-sm" 
                      data-bs-toggle="modal" 
                      data-bs-target="#deleteModal"
                      data-id="<?= $row['id'] ?>"
                      data-title="<?= htmlspecialchars($row['title']) ?>">
                <i class="bi bi-trash"></i> Delete
              </button>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      </div>
    </div>
    </div>
  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill"></i> Warning!</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this content?</p>
          <p class="fw-bold" id="deleteTitle"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
          <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Yes</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Notifications -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="addToast" class="toast admin-toast text-bg-success border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi bi-check-circle-fill"></i> Lesson added successfully!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
    <div id="deleteToast" class="toast admin-toast text-bg-danger border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi bi-trash3-fill"></i> Lesson deleted successfully!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <script src="../js/bootstrap.bundle.min.js"></script>
  <script>
    // Handle passing lesson data to modal
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget;
      const lessonId = button.getAttribute('data-id');
      const lessonTitle = button.getAttribute('data-title');

      const titleEl = deleteModal.querySelector('#deleteTitle');
      const confirmBtn = deleteModal.querySelector('#confirmDeleteBtn');

      titleEl.textContent = lessonTitle;
      confirmBtn.href = `delete_lesson.php?id=${lessonId}`;
    });

    // Toast logic (PHP controlled)
    document.addEventListener("DOMContentLoaded", function() {
      <?php if ($deleted): ?>
        var toastElId = 'deleteToast';
      <?php elseif ($added): ?>
        var toastElId = 'addToast';
      <?php else: ?>
        var toastElId = '';
      <?php endif; ?>

      if (toastElId) {
        var tEl = document.getElementById(toastElId);
        if (tEl) {
          var toastInstance = new bootstrap.Toast(tEl);
          toastInstance.show();
        }
      }
    });
  </script>
</body>
</html>
