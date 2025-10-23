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
  <title>Content | M2A Admin</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.min.css">
  <style>
    /* Toast container position */
    .toast-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 9999;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <?php include 'sidebar.php'; ?>
    <div class="p-4 flex-grow-1">
      <h3>Content Panel</h3>
    <hr>
    <div class="card rounded shadow">
      <table class="table table-striped mb-0 overflow-auto">
        <thead class="table-dark">
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
  <a class="btn btn-success text-light m-5 " href="add_lesson.php">Add Lesson</a>
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
  <div class="toast-container">
    <div id="addToast" class="toast align-items-center text-white bg-success border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi bi-check-circle-fill"></i> Lesson added successfully!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>

    <div id="deleteToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
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
        const toastEl = document.getElementById('deleteToast');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
      <?php elseif ($added): ?>
        const toastEl = document.getElementById('addToast');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
      <?php endif; ?>
    });
  </script>
</body>
</html>
