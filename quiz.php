<?php
// use your existing DB connection
require_once 'config.php';

session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


// if config.php didn't provide $conn, create a local fallback (optional)
if (!isset($conn) || !($conn instanceof mysqli)) {
    $conn = new mysqli('localhost', 'root', '', 'm2a_prog_lang_learn_guide');
}

if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed: " . htmlspecialchars($conn->connect_error);
    exit;
}

// fetch questions + options
$sql = "SELECT q.id AS qid, q.question, o.id AS oid, o.option_text
        FROM questions q
        JOIN options o ON q.id = o.question_id
        ORDER BY q.id, o.id";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo "Query error: " . htmlspecialchars($conn->error);
    exit;
}

$questions = [];
while ($row = $result->fetch_assoc()) {
    $qid = (int)$row['qid'];
    if (!isset($questions[$qid])) {
        $questions[$qid] = [
            'question' => $row['question'],
            'options'  => []
        ];
    }
    $questions[$qid]['options'][] = [
        'oid' => (int)$row['oid'],
        'text' => $row['option_text']
    ];
}
$result->free();
$total = count($questions);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title> Home | M2a: Programming Languages Learning Guide</title>
    <style>
        .dropdown-toggle::after {
            content: none;
        }
    </style>
</head>
<body>

<nav id="navbar-example2" class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container-fluid">
      <div class="navbar-brand h1 mb-0" style="font-family: 'Courier New', Courier, monospace; font-weight: bold;">
        <i class="bi bi-braces-asterisk"></i> <span class="text-danger">M3a</span>
        </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="#scrollspyHome">
              <strong>HOME</strong>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#scrollspyContact">
              <strong>CONTACT</strong>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <strong>QUIZESS</strong>
            </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">HTML</a></li>
            <li><a class="dropdown-item" href="#">CSS</a></li>
            <li><a class="dropdown-item" href="#">JavaScript</a></li>
            <li><a class="dropdown-item" href="#">PHP</a></li>
            <li><a class="dropdown-item" href="#">Svelte <span class="badge bg-success rounded-pill">New</span> </a></li>
          </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <strong>COMPILERS</strong>
            </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">HTML</a></li>
            <li><a class="dropdown-item" href="#">CSS</a></li>
            <li><a class="dropdown-item" href="#">JavaScript</a></li>
            <li><a class="dropdown-item" href="#">PHP</a></li>
            <li><a class="dropdown-item" href="#">Svelte <span class="badge bg-success rounded-pill">New</span> </a></li>
          </ul>
          </li>
        </ul>
        <form action="" class="form-inline">
          <button type="button" class="btn btn btn-outline-white border-bottom text-white pe-5" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">
            <i class="bi bi-search"></i> Search...
         </button>
        </form>
        <ul class="navbar-nav mx-auto mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="tutorials/html_intro.php">
              <img src="asset/img/html5-icon-13.jpg" alt="" width="24" height="24" class="d-inline-block align-text-top">
              <strong>HTML</strong>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="css_tutorial.php">
              <img src="asset/img/css3.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
              <strong>CSS</strong>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="js_tutorial.php">
              <img src="asset/img/js.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
              <strong>JS</strong>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="php_tutorial.php">
              <img src="asset/img/php.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
              <strong>PHP</strong>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="svelte_tutorial.php">
              <img src="asset/img/Svelte_logo_by_gengns.svg.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
              <strong>SVELTE</strong>
            </a>
          </li>
        </ul>
      <form action="" class="form-inline">
        <div class="dropdown">
          <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
             <p class="mb-0"><i class="bi bi-person-circle"></i> Profile</p>
          </button>
          <ul class="dropdown-menu dropdown-menu-light dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton1">
            <div class="ps-2 py-2 mb-2 bg-light header">
                 <a href="profile.php" class="mb-0 text-danger pt-2"><strong> <i class="bi bi-person-circle"></i>
                  <?php echo htmlspecialchars($_SESSION['username']); ?>
                  </strong></a>
                  <small class="text-muted">
                  <?php echo htmlspecialchars($_SESSION['email']); ?>
                </small>

                </div>
            <li><hr class="dropdown-divider"></li>
            <li><a type="button" class="dropdown-item btn btn-outline-danger h-100" href="logout.php">Logout</a></li>
          </ul>
        </div>
        </form>


      </div>
    </div>
  </nav>

  <main class="container-fluid card w-50 bg-light shadow-sm py-3 mt-5">
    <h1 class="mb-2 ms-3">Quiz</h1>

    <?php if (empty($questions)): ?>
        <div class="alert alert-secondary">No questions available.</div>
    <?php else: ?>
        <form id="quizForm" method="post" action="result.php" novalidate>
            <input type="hidden" id="currentIndex" name="currentIndex" value="0">
            <div id="progress" class="mb-3 ms-4">Question 1 of <?php echo $total; ?></div>

            <?php $idx = 0; foreach ($questions as $qid => $q): ?>
                <div class="question-block mb-4 <?php echo $idx === 0 ? '' : 'd-none'; ?>" data-index="<?php echo $idx; ?>" data-qid="<?php echo $qid; ?>">
                    <div class="card mx-3 border-secondary">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($q['question']); ?></h5>
                            <div class="list-group list-group-flush mt-2">
                                <?php foreach ($q['options'] as $opt): ?>
                                    <label class="list-group-item border-0 p-2">
                                        <input
                                          type="radio"
                                          name="answers[<?php echo $qid; ?>]"
                                          value="<?php echo $opt['oid']; ?>"
                                          class="me-2 option-input"
                                          >
                                        <span class="ms-1"><?php echo htmlspecialchars($opt['text']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $idx++; endforeach; ?>

            <div id="quizAlert" class="alert alert-warning d-none" role="alert"></div>

            <div class="d-flex justify-content-between container mt-3">
                <button type="button" id="prevBtn" class="btn btn-outline-danger" disabled>Previous</button>
                <div>
                  <button type="button" id="nextBtn" class="btn btn-danger">Next</button>
                  <button type="submit" id="submitBtn" class="btn btn-success d-none">Submit</button>
                </div>
            </div>
        </form>
    <?php endif; ?>
  </main>

  <script>
    (function(){
      const total = <?php echo $total; ?>;
      if (total === 0) return;

      const blocks = Array.from(document.querySelectorAll('.question-block'));
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const submitBtn = document.getElementById('submitBtn');
      const progress = document.getElementById('progress');
      const alertEl = document.getElementById('quizAlert');
      let current = 0;

      function show(index) {
        blocks.forEach((b, i) => b.classList.toggle('d-none', i !== index));
        prevBtn.disabled = index === 0;
        nextBtn.classList.toggle('d-none', index === total - 1);
        submitBtn.classList.toggle('d-none', index !== total - 1);
        progress.textContent = 'Question ' + (index + 1) + ' of ' + total;
        alertEl.classList.add('d-none');
        // focus first option if exists
        const firstOption = blocks[index].querySelector('.option-input');
        if (firstOption) firstOption.focus();
      }

      function validateCurrent() {
        const qid = blocks[current].dataset.qid;
        const checked = document.querySelector('input[name="answers['+qid+']"]:checked');
        return !!checked;
      }

      nextBtn.addEventListener('click', () => {
        if (!validateCurrent()) {
          alertEl.textContent = 'Please select an answer before proceeding.';
          alertEl.classList.remove('d-none');
          return;
        }
        if (current < total - 1) {
          current++;
          show(current);
        }
      });

      prevBtn.addEventListener('click', () => {
        if (current > 0) {
          current--;
          show(current);
        }
      });

      // optional: intercept submit to ensure last question answered
      document.getElementById('quizForm').addEventListener('submit', function(e){
        if (!validateCurrent()) {
          e.preventDefault();
          alertEl.textContent = 'Please select an answer before submitting.';
          alertEl.classList.remove('d-none');
          return false;
        }
        return true;
      });

      // initialize
      show(0);
    })();
  </script>
</body>
</html>