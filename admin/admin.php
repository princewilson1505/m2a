<?php
session_start();
include '../config.php';

// Restrict access to admins only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

// Fetch counts for dashboard stats
$totalUsers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$totalLessons = $conn->query("SELECT COUNT(*) AS count FROM lessons")->fetch_assoc()['count'];
$totalQuizzes = $conn->query("SELECT COUNT(*) AS count FROM quizzes")->fetch_assoc()['count'];
$totalMessages = $conn->query("SELECT COUNT(*) AS count FROM contact_form")->fetch_assoc()['count'];

$scoreTableCheck = $conn->query("SHOW TABLES LIKE 'quiz_scores'");
$hasScoreTable = $scoreTableCheck && $scoreTableCheck->num_rows > 0;
$totalScoreRecords = 0;
if ($hasScoreTable) {
  $scoreCountRes = $conn->query("SELECT COUNT(*) AS count FROM quiz_scores");
  if ($scoreCountRes) {
    $scoreRow = $scoreCountRes->fetch_assoc();
    $totalScoreRecords = (int)$scoreRow['count'];
  }
}

// ✅ Fetch lessons per category
$lessonData = [];
$lessonResult = $conn->query("SELECT category, COUNT(*) AS count FROM lessons GROUP BY category");
while ($row = $lessonResult->fetch_assoc()) {
  $lessonData[$row['category']] = $row['count'];
}

// Quizzes per category
$quizData = [];
$quizResult = $conn->query("SELECT category_id, COUNT(*) AS count FROM quizzes GROUP BY category_id");

// Map IDs to language names
$idToName = [
    1 => 'HTML',
    2 => 'CSS',
    3 => 'JavaScript',
    4 => 'PHP',
    5 => 'Svelte'
];

while ($row = $quizResult->fetch_assoc()) {
    $quizData[$idToName[$row['category_id']]] = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background: #1800AD;
            background: linear-gradient(90deg, rgb(24, 0, 173) 0%,
             rgba(21, 112, 255, 1) 50%, rgba(92, 225, 232, 1) 100%);
      min-height: 100vh;
    }
    .admin-shell {
      min-height: 100vh;
    }
    .admin-hero {
      background: linear-gradient(125deg, #000 0%, #3533cd 100%);
      border-radius: 30px;
      box-shadow: 0 20px 45px rgba(0,0,0,0.25);
      position: relative;
      overflow: hidden;
    }
    .admin-hero::after {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at top right, rgba(255,255,255,0.25), transparent 60%);
      pointer-events: none;
    }
    .stat-card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.12);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
      overflow: hidden;
    }
    .stat-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 25px 45px rgba(0,0,0,0.2);
    }
    .quick-action {
      border-radius: 999px;
    }
  </style>
</head>
<body>
  <div class="d-flex admin-shell">
    <?php include 'sidebar.php'; ?>
    
    <div class="p-4 flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
      <div class="admin-hero text-white p-4 mb-4">
        <div class="row g-3 align-items-center">
          <div class="col-lg-8">
            <h3 class="fw-bold mb-2">Admin Dashboard</h3>
            <p class="mb-0">Track lessons, quizzes, messages, and learner progress from one dynamic dashboard.</p>
          </div>
          <div class="col-lg-4 text-lg-end">
            <div class="badge bg-white text-dark px-3 py-2 rounded-pill">
              <i class="bi bi-clock-history me-1"></i> <?= date('M d, Y') ?>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 flex-wrap mb-4">
        <a href="manage_lesson.php" class="btn btn-light quick-action"><i class="bi bi-journal-text me-1"></i>Manage Lessons</a>
        <a href="manage_quiz.php" class="btn btn-light quick-action"><i class="bi bi-question-circle me-1"></i>Manage Quizzes</a>
        <a href="messages.php" class="btn btn-light quick-action"><i class="bi bi-envelope me-1"></i>Inbox</a>
        <a href="score_records.php" class="btn btn-light quick-action"><i class="bi bi-clipboard-data me-1"></i>Score Records</a>
      </div>

      <!-- Dashboard Cards -->
      <div class="row g-4 mb-5">
        <div class="col-md-3">
          <div class="card text-center stat-card h-100">
            <div class="card-body">
              <i class="bi bi-people-fill fs-1 text-primary"></i>
              <h5 class="mt-2">Users</h5>
              <p class="display-6 mb-0"><?= $totalUsers ?></p>
              <a href="manage_user.php" class="stretched-link text-decoration-none">Manage</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-center stat-card h-100">
            <div class="card-body">
              <i class="bi bi-journal-text fs-1 text-success"></i>
              <h5 class="mt-2">Lessons</h5>
              <p class="display-6 mb-0"><?= $totalLessons ?></p>
              <a href="manage_lesson.php" class="stretched-link text-decoration-none">Manage</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-center stat-card h-100">
            <div class="card-body">
              <i class="bi bi-question-circle-fill fs-1 text-warning"></i>
              <h5 class="mt-2">Quizzes</h5>
              <p class="display-6 mb-0"><?= $totalQuizzes ?></p>
              <a href="manage_quiz.php" class="stretched-link text-decoration-none">Manage</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-center stat-card h-100">
            <div class="card-body">
              <i class="bi bi-envelope-fill fs-1 text-danger"></i>
              <h5 class="mt-2">Messages</h5>
              <p class="display-6 mb-0"><?= $totalMessages ?></p>
              <a href="messages.php" class="stretched-link text-decoration-none">View</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-center stat-card h-100">
            <div class="card-body">
              <i class="bi bi-clipboard-data fs-1 text-info"></i>
              <h5 class="mt-2">Score Records</h5>
              <p class="display-6 mb-0"><?= $totalScoreRecords ?></p>
              <a href="score_records.php" class="stretched-link text-decoration-none">View</a>
            </div>
          </div>
        </div>
      </div>

      <!-- ✅ Charts Section -->
      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
              <h4 class="card-title mb-3"><i class="bi bi-bar-chart"></i> Lessons per Category</h4>
              <canvas id="lessonChart" height="100"></canvas>
            </div>
          </div>
        </div>

        <div class="col-lg-6 mb-4">
          <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
              <h4 class="card-title mb-3"><i class="bi bi-pie-chart-fill"></i> Quizzes per Category</h4>
              <canvas id="quizChart" height="20"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/bootstrap.bundle.min.js"></script>

  <script>
    // ✅ Chart Data (PHP → JS)
    const lessonCategories = <?= json_encode(array_keys($lessonData)) ?>;
    const lessonCounts = <?= json_encode(array_values($lessonData)) ?>;

    const quizCategories = <?= json_encode(array_keys($quizData)) ?>;
    const quizCounts = <?= json_encode(array_values($quizData)) ?>;

    // ✅ Color theme based on languages
    const colorMap = {
      'HTML': '#E44D26',
      'CSS': '#264DE4',
      'JavaScript': '#F7DF1E',
      'PHP': '#777BB4',
      'Svelte': '#FF3E00'
    };

    // Map colors dynamically (fallback: gray)
    const lessonColors = lessonCategories.map(cat => colorMap[cat] || '#999');
    const quizColors = quizCategories.map(cat => colorMap[cat] || '#999');

    // ✅ Lesson Bar Chart
    new Chart(document.getElementById('lessonChart'), {
      type: 'bar',
      data: {
        labels: lessonCategories,
        datasets: [{
          label: 'Number of Lessons',
          data: lessonCounts,
          backgroundColor: lessonColors,
          borderColor: '#ddd',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { enabled: true }
        },
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 } }
        }
      }
    });

    // ✅ Quiz Pie Chart
    new Chart(document.getElementById('quizChart'), {
      type: 'pie',
      data: {
        labels: quizCategories,
        datasets: [{
          data: quizCounts,
          backgroundColor: quizColors,
          borderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' },
          tooltip: { enabled: true }
        }
      }
    });
  </script>
</body>
</html>
