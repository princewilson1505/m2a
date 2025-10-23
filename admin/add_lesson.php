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
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">
  <div class="d-flex">
    <?php include 'sidebar.php';?>
  <div class="flex-grow-1 p-4">
    <h2>Add New Lesson</h2>
    <hr>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label fw-bold">Title:</label>
        <input type="text" class="form-control" name="title" placeholder="Enter lesson title" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Category:</label>
        <select class="form-select" name="category" required>
          <option value="">--Select Category--</option>
          <option value="HTML">HTML</option>
          <option value="CSS">CSS</option>
          <option value="JavaScript">JavaScript</option>
          <option value="PHP">PHP</option>
          <option value="Svelte">Svelte</option>
        </select>
      </div>

      <h4>Lesson Sections:</h4>
      <div id="sections" class="mb-3"></div>
      <button type="button" class="btn btn-outline-primary mb-3" onclick="addSection()">+ Add Section</button><br>

      <button type="submit" class="btn btn-success"><i class="bi bi-bookmark"></i> Save</button>
      <a href="manage_lesson.php" class="btn btn-secondary">Back to Manage</a>
    </form>
  </div>
  </div>

  <script>
    function addSection() {
      const container = document.getElementById('sections');
      const div = document.createElement('div');
      div.classList.add('border', 'p-3', 'rounded', 'mb-3', 'bg-white');
      div.innerHTML = `
        <label class="form-label fw-bold">Section Heading:</label>
        <input type="text" name="heading[]" class="form-control mb-3" placeholder="Section title">
        <label class="form-label fw-bold">Content:</label>
        <textarea name="content[]" class="form-control mb-3" rows="4" required></textarea>
        <label class="form-label fw-bold">Code Block (optional):</label>
        <textarea name="code_block[]" class="form-control mb-3" rows="4"></textarea>
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove()">❌ Remove Section</button>
      `;
      container.appendChild(div);
    }
  </script>
</body>
</html>
