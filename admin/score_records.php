<?php
session_start();
require '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: ../login.php');
  exit;
}

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
$limit = max(10, min(200, $limit));

$tableCheck = $conn->query("SHOW TABLES LIKE 'quiz_scores'");
$scoreTableExists = $tableCheck && $tableCheck->num_rows > 0;

$records = [];
$categorySummary = [];
$totalAttempts = 0;

if ($scoreTableExists) {
  $recordSql = "
    SELECT qs.*, u.username
    FROM quiz_scores qs
    LEFT JOIN users u ON qs.user_id = u.id
    ORDER BY qs.taken_at DESC
    LIMIT $limit
  ";
  $recordRes = $conn->query($recordSql);
  if ($recordRes) {
    while ($row = $recordRes->fetch_assoc()) {
      $records[] = $row;
    }
  }

  $summarySql = "
    SELECT category_label, COUNT(*) AS attempts, AVG(percentage) AS avg_percent
    FROM quiz_scores
    GROUP BY category_label
    ORDER BY attempts DESC, category_label ASC
  ";
  $summaryRes = $conn->query($summarySql);
  if ($summaryRes) {
    while ($row = $summaryRes->fetch_assoc()) {
      $categorySummary[] = $row;
    }
  }

  $countRes = $conn->query("SELECT COUNT(*) AS total_attempts FROM quiz_scores");
  if ($countRes) {
    $countRow = $countRes->fetch_assoc();
    $totalAttempts = (int)$countRow['total_attempts'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Score Records</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-page">
  <div class="d-flex admin-shell">
    <?php include 'sidebar.php'; ?>

    <div class="p-4 flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
      <div class="admin-hero text-white p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h2 class="mb-1">Score Records</h2>
          <p class="mb-0">Track quiz performance across users and categories.</p>
        </div>
        <form method="get" class="d-flex align-items-center gap-2">
          <label for="limit" class="form-label m-0">Show</label>
          <select id="limit" name="limit" class="form-select admin-pill-input" onchange="this.form.submit()">
            <?php foreach ([25, 50, 100, 150, 200] as $opt): ?>
              <option value="<?= $opt ?>" <?= $limit === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
          <span class="text-white-50 small">latest entries</span>
        </form>
      </div>

      <?php if (!$scoreTableExists): ?>
        <div class="alert alert-warning">
          <h5 class="alert-heading"><i class="bi bi-info-circle"></i> Score tracking not initialized</h5>
          <p class="mb-0">
            The <code>quiz_scores</code> table is missing. Import the updated schema from <code>m3a_db.sql</code>
            or run the table creation statement to start recording quiz results.
          </p>
        </div>
      <?php else: ?>

        <div class="row g-4 mb-4">
          <div class="col-md-4">
            <div class="bg-light card h-100">
              <div class="card-body">
                <h6 class="text-uppercase text-muted fw-bold mb-2">Total Attempts</h6>
                <p class="display-6 mb-0"><?= number_format($totalAttempts) ?></p>
                <small class="text-muted">All time</small>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="bg-light card h-100">
              <div class="card-body">
                <h6 class="text-uppercase text-muted fw-bold mb-3">Top Categories</h6>
                <?php if (empty($categorySummary)): ?>
                  <p class="mb-0 text-muted">No score data yet.</p>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                      <thead>
                        <tr>
                          <th>Category</th>
                          <th>Attempts</th>
                          <th>Avg. Score</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach (array_slice($categorySummary, 0, 5) as $summary): ?>
                          <tr>
                            <td><?= htmlspecialchars($summary['category_label']) ?></td>
                            <td><?= (int)$summary['attempts'] ?></td>
                            <td><?= number_format((float)$summary['avg_percent'], 2) ?>%</td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-light card admin-card-hover">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Recent Attempts</h5>
              <span class="text-muted small">Showing latest <?= $limit ?> entries</span>
            </div>

            <?php if (empty($records)): ?>
              <p class="text-muted mb-0">No quiz attempts recorded yet.</p>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>#</th>
                      <th>User</th>
                      <th>Category</th>
                      <th>Score</th>
                      <th>Percentage</th>
                      <th>Taken At</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($records as $index => $record): ?>
                      <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $record['username'] ? htmlspecialchars($record['username']) : 'Guest' ?></td>
                        <td><?= htmlspecialchars($record['category_label']) ?></td>
                        <td><?= (int)$record['correct_answers'] ?>/<?= (int)$record['total_questions'] ?></td>
                        <td><?= number_format((float)$record['percentage'], 2) ?>%</td>
                        <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($record['taken_at']))) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>

      <?php endif; ?>

    </div>
  </div>

  <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>

