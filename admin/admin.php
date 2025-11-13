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
</head>
<body>
  <div class="d-flex">
    <?php include 'sidebar.php'; ?>
    
    <div class="p-4 flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
      <h1 class="mb-4">Welcome, Admin!</h1>
      <p class="text-muted mb-4">Manage your website content and users easily.</p>

      <!-- Dashboard Cards -->
      <div class="row g-4 mb-5">
        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <i class="bi bi-people-fill fs-1 text-primary"></i>
              <h5 class="mt-2">Users</h5>
              <p class="display-6 mb-0"><?= $totalUsers ?></p>
              <a href="manage_user.php" class="stretched-link text-decoration-none">Manage</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <i class="bi bi-journal-text fs-1 text-success"></i>
              <h5 class="mt-2">Lessons</h5>
              <p class="display-6 mb-0"><?= $totalLessons ?></p>
              <a href="manage_lesson.php" class="stretched-link text-decoration-none">Manage</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <i class="bi bi-question-circle-fill fs-1 text-warning"></i>
              <h5 class="mt-2">Quizzes</h5>
              <p class="display-6 mb-0"><?= $totalQuizzes ?></p>
              <a href="manage_quiz.php" class="stretched-link text-decoration-none">Manage</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <i class="bi bi-envelope-fill fs-1 text-danger"></i>
              <h5 class="mt-2">Messages</h5>
              <p class="display-6 mb-0"><?= $totalMessages ?></p>
              <a href="messages.php" class="stretched-link text-decoration-none">View</a>
            </div>
          </div>
        </div>
      </div>

      <!-- ✅ Charts Section -->
      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h4 class="card-title mb-3"><i class="bi bi-bar-chart"></i> Lessons per Category</h4>
              <canvas id="lessonChart" height="100"></canvas>
            </div>
          </div>
        </div>

        <div class="col-lg-6 mb-4">
          <div class="card shadow-sm">
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
