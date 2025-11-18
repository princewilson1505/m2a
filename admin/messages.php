<?php
session_start();
include '../config.php';

// ✅ Restrict to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// 🗑 Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM contact_form WHERE id = $id");
    header("Location: messages.php?deleted=1");
    exit;
}

// Fetch messages
$result = $conn->query("SELECT * FROM contact_form ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Messages | Admin</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-page">
<div class="d-flex admin-shell">
  <?php include 'sidebar.php'; ?>

  <div class="flex-grow-1 p-4" style="max-height:100vh;overflow-y:auto;">
    <div class="admin-hero text-white p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h2 class="mb-1"><i class="bi bi-envelope-fill me-2"></i>Inbox</h2>
        <p class="mb-0">Review feedback, questions, and contact submissions in one view.</p>
      </div>
      <span class="badge bg-light text-dark px-3 py-2 rounded-pill"><?= $result->num_rows ?> messages</span>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
      <div class="toast admin-toast text-bg-success show mb-3" role="alert">
        <div class="d-flex">
          <div class="toast-body"><i class="bi bi-check-circle me-2"></i>Message deleted successfully.</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    <?php endif; ?>

    <div class="admin-card p-3">
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Submitted At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                    <td>
                      <button 
                        class="btn btn-sm btn-outline-success admin-quick-action" 
                        data-bs-toggle="modal" 
                        data-bs-target="#viewModal" 
                        data-name="<?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>"
                        data-email="<?= htmlspecialchars($row['email']) ?>"
                        data-phone="<?= htmlspecialchars($row['phone']) ?>"
                        data-comment="<?= htmlspecialchars($row['comment']) ?>"
                        data-date="<?= htmlspecialchars($row['submitted_at']) ?>"
                      >View</button>

                      <a href="?delete=<?= $row['id'] ?>" 
                         class="btn btn-sm btn-outline-danger admin-quick-action"
                         onclick="return confirm('Are you sure you want to delete this message?');">
                         Delete
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="6" class="text-center text-muted">No messages received yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 🔍 View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Message Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Name:</strong> <span id="viewName"></span></p>
        <p><strong>Email:</strong> <span id="viewEmail"></span></p>
        <p><strong>Phone:</strong> <span id="viewPhone"></span></p>
        <p><strong>Submitted:</strong> <span id="viewDate"></span></p>
        <hr>
        <p><strong>Comment:</strong></p>
        <p id="viewComment" class="text-black"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
<script>
// 🧠 Fill modal with message data
const viewModal = document.getElementById('viewModal');
viewModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  document.getElementById('viewName').textContent = button.getAttribute('data-name');
  document.getElementById('viewEmail').textContent = button.getAttribute('data-email');
  document.getElementById('viewPhone').textContent = button.getAttribute('data-phone');
  document.getElementById('viewDate').textContent = button.getAttribute('data-date');
  document.getElementById('viewComment').textContent = button.getAttribute('data-comment');
});
</script>
</body>
</html>
