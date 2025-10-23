<?php
include 'config.php';
$category = $_GET['cat'] ?? '';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$orderParam = $_GET['order'] ?? 'id_asc';
$orderMap = [
  'id_asc' => 'id ASC',
  'id_desc' => 'id DESC',
  'date_desc' => 'date_created DESC'
];
$orderSql = isset($orderMap[$orderParam]) ? $orderMap[$orderParam] : $orderMap['id_asc'];

$sql = "SELECT * FROM lessons WHERE category=? ORDER BY $orderSql";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($category) ?> Lessons</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/icons/font/bootstrap-icons.min.css">
</head>
<body>
  <?php include 'nav.php'; ?>
  <main class="container pt-5 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0"><?= htmlspecialchars($category) ?> Lessons</h1>
      <a href="index.php" class="btn btn-outline-secondary">Go Back</a>
    </div>
    <hr>

    <div class="card shadow mb-4">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h5 class="pb-0">Showing lessons in <strong><?= htmlspecialchars($category) ?></strong></h5>
        </div>
      </div>
    </div>

    <?php if ($result->num_rows > 0): ?>
      <ul class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center shadow">
            <div>
              <a href="lesson.php?id=<?= $row['id'] ?>" class="text-decoration-none fw-semibold"><?= htmlspecialchars($row['title']) ?></a>
              <div class="small">Created: <?= htmlspecialchars($row['date_created'] ?? '') ?></div>
            </div>
            <a href="lesson.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Open</a>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <div class="alert alert-info">No lessons available for this category yet.</div>
    <?php endif; ?>

  </main>

  <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
