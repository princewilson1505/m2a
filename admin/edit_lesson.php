<?php
session_start();
include '../config.php';

// ✅ Restrict access to admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// Fetch lesson
$lesson = $conn->query("SELECT * FROM lessons WHERE id=$id")->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("UPDATE lessons SET title=?, category=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $category, $id);
    $stmt->execute();

    // Update existing sections
    if (isset($_POST['section_id'])) {
        foreach ($_POST['section_id'] as $i => $secId) {
            $heading = $_POST['heading'][$i];
            $content = $_POST['content'][$i];
            $code = $_POST['code_block'][$i];

            $stmt2 = $conn->prepare("UPDATE lesson_sections SET heading=?, content=?, code_block=? WHERE id=?");
            $stmt2->bind_param("sssi", $heading, $content, $code, $secId);
            $stmt2->execute();
        }
    }

    // Add new sections
    if (isset($_POST['new_heading'])) {
        foreach ($_POST['new_heading'] as $i => $newHeading) {
            $newContent = $_POST['new_content'][$i];
            $newCode = $_POST['new_code_block'][$i];

            if (trim($newHeading) !== '' || trim($newContent) !== '' || trim($newCode) !== '') {
                $stmt3 = $conn->prepare("INSERT INTO lesson_sections (lesson_id, heading, content, code_block) VALUES (?, ?, ?, ?)");
                $stmt3->bind_param("isss", $id, $newHeading, $newContent, $newCode);
                $stmt3->execute();
            }
        }
    }

    header("Location: edit_lesson.php?id=$id&updated=1");
    exit;
}

// Fetch sections
$sections = $conn->query("SELECT * FROM lesson_sections WHERE lesson_id=$id ORDER BY id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Lesson</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/admin.css">
  <script src="../js/bootstrap.bundle.min.js"></script>
</head>
<body class="admin-page">

<div class="d-flex admin-shell">
  <?php include 'sidebar.php'; ?>
  <div class="flex-grow-1 p-4" style="max-height:100vh;overflow-y:auto;">
    <div class="admin-hero text-white p-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h2 class="mb-1 fw-bold">Edit Lesson</h2>
          <p class="mb-0">Update lesson content, reorder sections, and manage code snippets.</p>
        </div>
        <!-- Delete lesson button -->
        <button type="button" class="btn btn-light admin-quick-action" data-bs-toggle="modal" data-bs-target="#deleteLessonModal">
          <i class="bi bi-trash"></i> Delete Lesson
        </button>
      </div>
    </div>

    <div class="admin-card p-4">
      <form method="POST" class="admin-form">
        <div class="mb-3">
          <label class="form-label">Lesson Title</label>
          <input type="text" name="title" class="form-control admin-pill-input" value="<?= htmlspecialchars($lesson['title']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="category" class="form-select admin-pill-input" required>
            <?php
            $categories = ['HTML', 'CSS', 'JavaScript', 'PHP', 'Svelte'];
            foreach ($categories as $cat) {
              $selected = ($lesson['category'] === $cat) ? "selected" : "";
              echo "<option value='$cat' $selected>$cat</option>";
            }
            ?>
          </select>
        </div>

        <h5 class="mt-4">Lesson Sections</h5>
        <hr>

        <?php while ($sec = $sections->fetch_assoc()): ?>
          <div class="border rounded-4 p-3 mb-3 bg-light shadow-sm">
            <input type="hidden" name="section_id[]" value="<?= $sec['id'] ?>">

            <div class="mb-2">
              <label class="form-label">Section Heading</label>
              <input type="text" name="heading[]" class="form-control" value="<?= htmlspecialchars($sec['heading']) ?>">
            </div>

            <div class="mb-2">
              <label class="form-label">Content</label>
              <textarea name="content[]" class="form-control" rows="4"><?= htmlspecialchars($sec['content']) ?></textarea>
            </div>

            <div class="mb-2">
              <label class="form-label">Code Block (optional)</label>
              <textarea name="code_block[]" class="form-control" rows="5"><?= htmlspecialchars($sec['code_block']) ?></textarea>
            </div>

            <a href="delete_section.php?id=<?= $sec['id'] ?>&lesson=<?= $lesson['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this section?')"><i class="bi bi-trash"></i> Delete Section</a>
          </div>
        <?php endwhile; ?>

        <div id="newSections"></div>

        <button type="button" class="btn btn-success m-3" onclick="addSection()"><i class="bi bi-plus-circle"></i> Add New Section</button>

        <div class="text-end mt-4">
        <button type="submit" class="btn admin-gradient-btn mt-3"> <i class="bi bi-floppy-fill me-2"></i>Save Changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

    <!-- Delete confirmation modal for lesson -->
    <div class="modal fade" id="deleteLessonModal" tabindex="-1" aria-labelledby="deleteLessonLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="deleteLessonLabel"><i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete the lesson?</p>
            <p class="fw-bold"><?= htmlspecialchars($lesson['title']) ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
            <a href="delete_lesson.php?id=<?= $lesson['id'] ?>" class="btn btn-danger">Yes, delete</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast container (update / delete notifications) -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
      <div id="updateToast" class="toast admin-toast align-items-center text-bg-success border-0" role="alert" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">Lesson updated successfully!</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>

      <div id="sectionDeletedToast" class="toast admin-toast align-items-center text-bg-success border-0 mt-2" role="alert" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">Section deleted successfully!</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>

      <div id="sectionDeleteFailedToast" class="toast admin-toast align-items-center text-bg-danger border-0 mt-2" role="alert" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">Failed to delete section.</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    </div>

    <script>
      // Show toasts based on query params
      document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_GET['updated'])): ?>
          var t = new bootstrap.Toast(document.getElementById('updateToast'));
          t.show();
        <?php endif; ?>

        <?php if (isset($_GET['section_deleted'])): ?>
          <?php if ($_GET['section_deleted'] == '1'): ?>
            var t2 = new bootstrap.Toast(document.getElementById('sectionDeletedToast'));
            t2.show();
          <?php else: ?>
            var t3 = new bootstrap.Toast(document.getElementById('sectionDeleteFailedToast'));
            t3.show();
          <?php endif; ?>
        <?php endif; ?>
      });
    </script>

<script>
function addSection() {
  const container = document.getElementById('newSections');
  const div = document.createElement('div');
  div.classList.add('border', 'rounded-4', 'p-3', 'mb-3', 'bg-light', 'shadow-sm');
  div.innerHTML = `
    <h6>New Section</h6>
    <div class="mb-2">
      <label class="form-label">Section Heading</label>
      <input type="text" name="new_heading[]" class="form-control" placeholder="Section title">
    </div>
    <div class="mb-2">
      <label class="form-label">Content</label>
      <textarea name="new_content[]" class="form-control" rows="4" placeholder="Section content..."></textarea>
    </div>
    <div class="mb-2">
      <label class="form-label">Code Block (optional)</label>
      <textarea name="new_code_block[]" class="form-control" rows="5" placeholder="Code here..."></textarea>
    </div>
  `;
  container.appendChild(div);
}
</script>

</body>
</html>
