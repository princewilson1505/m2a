<?php

session_start();

// 🔹 Check if logged in and is a user
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if (strtolower($_SESSION['role']) !== 'user') {
    // redirect admins to admin panel
    header("Location: ../admin/admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Programming Quizzes</title>
  <meta charset="UTF-8">
</head>
<body>

<h2 style="text-align:center;">Choose a Quiz Category</h2>
<a href="../index.php">← Back to Home</a> |
<a href="../logout.php">Logout</a>
<hr>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; max-width:800px; margin:auto;">
  <a href="quiz_html.php" style="border:1px solid #ccc; padding:20px; text-align:center; text-decoration:none;">🧱 HTML Quiz</a>
  <a href="quiz_css.php" style="border:1px solid #ccc; padding:20px; text-align:center; text-decoration:none;">🎨 CSS Quiz</a>
  <a href="quiz_js.php" style="border:1px solid #ccc; padding:20px; text-align:center; text-decoration:none;">⚡ JavaScript Quiz</a>
  <a href="quiz_php.php" style="border:1px solid #ccc; padding:20px; text-align:center; text-decoration:none;">🐘 PHP Quiz</a>
  <a href="quiz_svelte.php" style="border:1px solid #ccc; padding:20px; text-align:center; text-decoration:none;">🔥 Svelte Quiz</a>
</div>

</body>
</html>
