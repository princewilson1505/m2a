<?php
session_start();
require_once '../config.php';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
  header('Location: admin.php');
  exit;
}

$error = '';
$success = '';
$redirect_url = 'admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin' LIMIT 1");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if ($password === $user['password']) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['nickname'] = $user['nickname'];
      $_SESSION['profile_img'] = $user['profile_img'];
      $_SESSION['role'] = $user['role'];
      $success = 'Welcome back, Admin! Redirecting...';
    } else {
      $error = 'Incorrect password.';
    }
  } else {
    $error = 'Admin account not found.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | M2a</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.min.css">
</head>
<body style="background: linear-gradient(120deg, #0F0C29 0%, #302B63 50%, #24243E 100%);">
  <section>
    <div class="container">
      <div class="row" style="min-height:100vh;">
        <div class="col-10 col-sm-8 col-md-5 col-lg-4 m-auto">
          <div class="card bg-light border border-primary shadow-lg rounded-4">
            <div class="card-body p-4">
              <div class="text-center mb-4">
                <img src="../assets/icon.png" alt="logo" height="80">
                <h4 class="mt-3 mb-0">Admin Console</h4>
                <small class="text-muted">Use your administrator credentials</small>
              </div>

              <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                  <i class="bi bi-exclamation-triangle-fill me-1"></i><?= htmlspecialchars($error) ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <form method="POST" autocomplete="off">
                <div class="mb-4 d-flex align-items-center border rounded-pill px-3">
                  <i class="bi bi-person-fill text-primary me-2"></i>
                  <input type="text" class="form-control border-0" name="username" placeholder="Admin username" required value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
                </div>
                <div class="mb-4 d-flex align-items-center border rounded-pill px-3">
                  <i class="bi bi-lock-fill text-primary me-2"></i>
                  <input type="password" class="form-control border-0" id="password" name="password" placeholder="Password" required>
                  <button type="button" class="btn btn-sm btn-outline-secondary border-0" id="togglePasswordBtn">
                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                  </button>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Log in</button>
              </form>

              <div class="text-center mt-3">
                <small>Need standard access? <a href="../login.php">Go to user login</a></small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <?php if (!empty($success)): ?>
      <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" id="loginToast">
        <div class="d-flex">
          <div class="toast-body">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="../js/bootstrap.bundle.min.js"></script>
  <script>
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash');
      });
    }

    <?php if (!empty($success)): ?>
      const toastEl = document.getElementById('loginToast');
      const toast = new bootstrap.Toast(toastEl, { delay: 1400 });
      toast.show();
      setTimeout(() => window.location.href = "<?= $redirect_url ?>", 1500);
    <?php endif; ?>
  </script>
</body>
</html>

