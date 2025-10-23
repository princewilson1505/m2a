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
  <link rel="stylesheet" href="../css/style.css">
  <script src="../js/bootstrap.bundle.min.js"></script>
  <style>
    textarea { resize: vertical; }
  </style>
</head>
<body class="bg-light">

<div class="d-flex">
  <?php include 'sidebar.php'; ?>
  <div class="flex-grow-1 p-4">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-black text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Edit Lesson</h4>
      <a href="admin.php" class="btn btn-light btn-sm">Back</a>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Lesson Title</label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($lesson['title']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="category" class="form-select" required>
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
          <div class="border rounded p-3 mb-3 bg-light">
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

            <a href="delete_section.php?id=<?= $sec['id'] ?>&lesson=<?= $lesson['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this section?')">🗑️ Delete Section</a>
          </div>
        <?php endwhile; ?>

        <div id="newSections"></div>

        <button type="button" class="btn btn-secondary mt-3" onclick="addSection()">➕ Add New Section</button>
        <button type="submit" class="btn btn-success mt-3">💾 Save Changes</button>
      </form>
    </div>
  </div>
</div>
</div>

<!-- ✅ Toast for Update Success -->
<?php if (isset($_GET['updated'])): ?>
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="updateToast" class="toast align-items-center text-bg-success border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          ✅ Lesson updated successfully!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>
<?php endif; ?>

<script>
function addSection() {
  const container = document.getElementById('newSections');
  const div = document.createElement('div');
  div.classList.add('border', 'rounded', 'p-3', 'mb-3', 'bg-light');
  div.innerHTML = `
    <h6>🆕 New Section</h6>
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
