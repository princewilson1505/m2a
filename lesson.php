<?php
include 'config.php';

// Ensure id is provided and is an integer before querying
if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}

$id = (int)$_GET['id'];

// Get current lesson using a prepared statement
$stmt = $conn->prepare("SELECT * FROM lessons WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$lesson = $res->fetch_assoc();

if (!$lesson) {
  echo "Lesson not found.";
  exit;
}

// Get lesson sections using a prepared statement
$secStmt = $conn->prepare("SELECT * FROM lesson_sections WHERE lesson_id = ? ORDER BY id");
$secStmt->bind_param("i", $id);
$secStmt->execute();
$sections = $secStmt->get_result();

// Determine category from the lesson
$category = $lesson['category'];

// Normalize active tutorial for nav highlighting
$activeTutorial = strtolower($category);

// Get next and previous lessons in the same category
$nextStmt = $conn->prepare("SELECT id, title FROM lessons WHERE category = ? AND id > ? ORDER BY id ASC LIMIT 1");
$nextStmt->bind_param("si", $category, $id);
$nextStmt->execute();
$nextLesson = $nextStmt->get_result()->fetch_assoc();

$prevStmt = $conn->prepare("SELECT id, title FROM lessons WHERE category = ? AND id < ? ORDER BY id DESC LIMIT 1");
$prevStmt->bind_param("si", $category, $id);
$prevStmt->execute();
$prevLesson = $prevStmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($lesson['title']) ?></title>
  <meta name="description" content="<?= htmlspecialchars($lesson['title']) ?> - <?= htmlspecialchars($lesson['category']) ?> tutorial">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <style>
     html, body {
      height: 100%;
      margin: 0;
      overflow: hidden;
    }

    body { background: #f9f9f9; display: flex; flex-direction: column; }

    .main-container {
      flex: 1;
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      background: #000;
      width: 280px;
      overflow-y: auto;
      padding: 15px;
      height: 100vh;
    }

    .sidebar h5, .sidebar a { color:white; }

    .content-area {
      flex: 1;
      background: #fff;
      overflow-y: auto;
      padding: 20px;
      border-radius: 0;
    }

    pre { background:#f4f4f4; padding:10px; border-radius:6px; }
    iframe { width:100%; height:250px; border:1px solid #ccc; margin-top:10px; border-radius:6px; }
    button { margin-top:5px; }

    .toggle-btn { display:none; }

    @media (max-width:992px){
      .sidebar { 
        display:none; 
        position:fixed; 
        top:0; left:0; 
        width:75%; 
        height:100vh; 
        z-index:1050; 
        overflow-y:auto; 
      }
      .toggle-btn { 
        display:block; 
        position:fixed; 
        top:10px; left:10px; 
        z-index:1100; 
      }
      .overlay { 
        display:none; 
        position:fixed; 
        top:0; left:0; 
        width:100%; height:100%; 
        background:rgba(0,0,0,0.5); 
        z-index:1040; 
      }
    }
  </style>
</head>

<body data-theme="light">
  <?php include 'nav.php'; ?>

  <!-- Mobile Sidebar Toggle -->
  <button class="btn btn-dark toggle-btn" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
  <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

  <div class="container-fluid mt-5">
    <div class="row me-0">
      
      <!-- Sidebar -->
      <div class="col-lg-3 sidebar p-3" id="sidebar">
        <h5><?= htmlspecialchars($lesson['category']) ?> Lessons</h5>
        <hr class="text-light">

        <?php
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

        if ($result->num_rows > 0): ?>
          <ul class="list-unstyled mx-2">
             <?php while ($row = $result->fetch_assoc()): ?>
               <?php $isActive = ($row['id'] == $id); ?> 
               <li class="mb-1"> <a href="lesson.php?id=<?= $row['id'] ?>" class="btn btn-sm <?= $isActive ? 'btn-primary' : 'btn-outline-light' ?> w-100 text-start" aria-current="
               <?= $isActive ? 'page' : 'false' ?>"> <?= htmlspecialchars($row['title']) ?> 
              </a> </li> <?php endwhile; ?> 
            </ul> <?php else: ?> 
              <p>No lessons available for this category yet.</p>
               <?php endif; ?>
      </div>

      <!-- Lesson Content -->
      <div class="col-lg-8 offset-lg-1 shadow-lg p-4" style="height:93vh; overflow-y:auto; border-radius:10px;">
        <h1><?= htmlspecialchars($lesson['title']) ?></h1>
        <hr>
        <?php while ($s = $sections->fetch_assoc()): ?>
          <section>
            <?php if ($s['heading']): ?>
              <h4><?= htmlspecialchars($s['heading']) ?></h4>
            <?php endif; ?>
            <p><?= $s['content'] ?></p>

            <?php if (!empty($s['code_block'])): ?>
            <div class="card mb-3">
              <div class="card-header"><?= htmlspecialchars($lesson['category']) ?></div>
              <div class="card-body">
                <pre><code id="codeblock-<?= $s['id'] ?>" class="language-<?= strtolower($lesson['category']) ?> language-php"><?= htmlspecialchars($s['code_block']) ?></code></pre>
                <div style="display:flex; gap:8px; margin-top:6px;">
                  <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyCode(<?= $s['id'] ?>, this)">Copy</button>
                  <?php if (in_array($lesson['category'], ['HTML','CSS','JavaScript', 'PHP'])): ?>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="runCode(<?= $s['id'] ?>)">Run</button>
                  <?php endif; ?>
                </div>
                <iframe class="bg-white" id="output<?= $s['id'] ?>"></iframe>
              </div>
            </div>
            <?php endif; ?>
          </section>
          <br>
        <?php endwhile; ?>

        <div class="px-4 d-flex justify-content-between">
          <div>
            <?php if ($prevLesson): ?>
              <a class="btn btn-primary" href="lesson.php?id=<?= $prevLesson['id'] ?>">← Prev</a>
            <?php endif; ?>
          </div>
          <div>
            <?php if ($nextLesson): ?>
              <a class="btn btn-primary" href="lesson.php?id=<?= $nextLesson['id'] ?>">Next →</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Scripts -->
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js"></script>

  <script>
  // Sidebar toggle for mobile
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const isOpen = sidebar.style.display === 'block';
    sidebar.style.display = isOpen ? 'none' : 'block';
    overlay.style.display = isOpen ? 'none' : 'block';
  }

  // Run code
  function runCode(id) {
  const codeEl = document.getElementById('codeblock-' + id);
  const iframe = document.getElementById('output' + id);
  const code = codeEl.textContent;
  const category = codeEl.className.toLowerCase();

  // For HTML, CSS, JS — run locally
  if (category.includes('html') || category.includes('css') || category.includes('javascript')) {
    iframe.srcdoc = code;
    return;
  }

  // For PHP — send code to backend
  if (category.includes('php')) {
    iframe.src = 'about:blank';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'run_php.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
      iframe.srcdoc = `<pre style="padding:10px;">${this.responseText}</pre>`;
    };
    xhr.send('code=' + encodeURIComponent(code));
  }
}

  // Copy code
  function copyCode(id, btn) {
    const codeEl = document.getElementById('codeblock-' + id);
    if (!codeEl) return;
    navigator.clipboard.writeText(codeEl.textContent).then(() => {
      const original = btn.textContent;
      btn.textContent = 'Copied!';
      setTimeout(() => btn.textContent = original, 1200);
    }).catch(() => {
      btn.textContent = 'Failed';
      setTimeout(() => btn.textContent = 'Copy', 1200);
    });
  }
  </script>
</body>
</html>
