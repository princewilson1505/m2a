<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $category = $_POST['category'];
  
  $stmt = $conn->prepare("INSERT INTO lessons (title, category) VALUES (?, ?)");
  $stmt->bind_param("ss", $title, $category);
  $stmt->execute();
  $lesson_id = $stmt->insert_id;

  // Loop through section arrays
  foreach ($_POST['content'] as $i => $text) {
    $heading = $_POST['heading'][$i];
    $code = $_POST['code_block'][$i];
    $stmt2 = $conn->prepare("INSERT INTO lesson_sections (lesson_id, heading, content, code_block) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("isss", $lesson_id, $heading, $text, $code);
    $stmt2->execute();
  }

  // Redirect back to admin page with success flag
  header("Location: admin.php?added=1");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Lesson | M2A Admin</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-page">
  <div class="d-flex admin-shell">
    <?php include 'sidebar.php';?>
    
    <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
      <div class="admin-hero text-white p-4 mb-4">
        <h2 class="fw-bold mb-1">Add New Lesson</h2>
        <p class="mb-0">Design a structured learning module with multiple sections and code examples.</p>
      </div>
      <form method="POST" class="admin-card p-4 admin-form">
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" class="form-control admin-pill-input" name="title" placeholder="Enter lesson title" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Category</label>
          <select class="form-select admin-pill-input" name="category" required>
            <option value="">--Select Category--</option>
            <option value="HTML">HTML</option>
            <option value="CSS">CSS</option>
            <option value="JavaScript">JavaScript</option>
            <option value="PHP">PHP</option>
            <option value="Svelte">Svelte</option>
          </select>
        </div>

        <h4 class="mt-4">Lesson Sections</h4>
        <div id="sections" class="mb-3"></div>
        <button type="button" class="btn btn-outline-primary mb-3 admin-quick-action" onclick="addSection()"><i class="bi bi-plus-circle me-1"></i>Add Section</button><br>

        <div class="d-flex flex-wrap gap-2">
          <button type="submit" class="btn admin-gradient-btn"><i class="bi bi-bookmark me-2"></i>Save Lesson</button>
          <a href="manage_lesson.php" class="btn btn-outline-secondary admin-quick-action">Back to Manage</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    function addSection() {
      const container = document.getElementById('sections');
      const div = document.createElement('div');
      div.classList.add('border', 'p-3', 'rounded-4', 'mb-3', 'bg-white', 'shadow-sm');
      div.innerHTML = `
        <label class="form-label fw-bold">Section Heading:</label>
        <input type="text" name="heading[]" class="form-control admin-pill-input mb-3" placeholder="Section title">
        <label class="form-label fw-bold">Content:</label>
        <textarea name="content[]" class="form-control mb-3" rows="4" required></textarea>
        <label class="form-label fw-bold">Code Block (optional):</label>
        <textarea name="code_block[]" class="form-control mb-3" rows="4"></textarea>
        <button type="button" class="btn btn-outline-danger btn-sm admin-quick-action" onclick="this.parentElement.remove()"><i class="bi bi-x-circle me-1"></i>Remove Section</button>
      `;
      container.appendChild(div);
    }
  </script>
</body>
</html>
