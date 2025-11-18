<?php
session_start();
include 'config.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // simple (non-hashed) password check
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nickname'] = $user['nickname'];
            $_SESSION['profile_img'] = $user['profile_img'];
            $_SESSION['role'] = $user['role'];

            // Set success message for toast
            $success = "Login successful! Redirecting...";

            // Redirect after short delay (handled in JS below)
            $redirect_url = ($user['role'] === 'admin') ? "admin/admin.php" : "index.php";
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
$_SESSION['toast'] = [
    'type' => 'success',
    'message' => 'Account created successfully! Please log in.'
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log in | M2a</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/icons/font/bootstrap-icons.min.css">
  <style>
    .auth-card { border-radius: 24px; overflow: hidden; }
    .input-pill { border-radius: 999px; padding: 0.65rem 1.1rem; box-shadow: inset 0 1px 2px rgba(0,0,0,0.08); }
    .toast-glow { box-shadow: 0 10px 30px rgba(33, 150, 243, 0.3); }
  </style>
</head>
<body style="background: #1800AD;
            background: linear-gradient(90deg, rgba(24, 0, 173, 1) 0%,
             rgba(21, 112, 255, 1) 50%, rgba(92, 225, 232, 1) 100%);">

  <section>
    <div class="container">
      <div class="row" style="height:100vh;">
        <div class="col-10 col-sm-8 col-md-5 col-lg-4 m-auto">
          <div class="card bg-light border-0 shadow-lg auth-card">
            <div class="card-body p-4">
             <div class="mb-4 text-center">
          <img src="assets/icon.png" alt="logo" height="90" width="auto">
          <h3 class="mt-3 mb-0">Welcome back</h3>
          <small class="text-muted">Sign in to continue learning</small>
        </div>

              <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                  <i class="bi bi-exclamation-triangle-fill"></i>
                  <?= htmlspecialchars($error) ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <form action="login.php" method="POST" autocomplete="off">
                <div class="mb-4 d-flex align-items-center border input-pill">
                  <i class="bi bi-person text-primary me-2"></i>
                  <input type="text" class="form-control border-0" id="username" name="username" placeholder="Username"
                         required value="<?= isset($username) ? htmlspecialchars($username) : ''; ?>">
                </div>
                <div class="mb-4 d-flex align-items-center border input-pill">
                  <i class="bi bi-lock text-primary me-2"></i>
                  <input type="password" class="form-control border-0" id="password" name="password" placeholder="Password" required>
                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" id="togglePasswordBtn">
                  <i id="togglePassword" class="bi bi-eye-slash"></i>
                  </button>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary  w-100 rounded-pill py-2">LOGIN</button>
                </div>
              </form>

              <div class="text-center mt-3">
                I don't have an account? <a href="register.php">Sign up</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Toast container -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <?php if (!empty($success)): ?>
      <div class="toast toast-glow align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" id="loginToast">
        <div class="d-flex">
          <div class="toast-body">
            <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success) ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script>
    <?php if (!empty($success)): ?>
      var toastEl = document.getElementById('loginToast');
      var toast = new bootstrap.Toast(toastEl, { delay: 1500 });
      toast.show();

      // Redirect after toast disappears
      setTimeout(function() {
        window.location.href = "<?= $redirect_url ?>";
      }, 1600);
    <?php endif; ?>
  </script>
<?php if (isset($_SESSION['toast'])): ?>
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast toast-glow align-items-center text-bg-<?= $_SESSION['toast']['type'] ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          <?= htmlspecialchars($_SESSION['toast']['message']) ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
  <script>
    var toastEl = document.getElementById('liveToast');
    if (toastEl) {
      var toast = new bootstrap.Toast(toastEl);
      toast.show();
    }
  </script>
  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");
    const toggleBtn = document.getElementById('togglePasswordBtn');

    toggleBtn.addEventListener("click", function () {
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    togglePassword.classList.toggle('bi-eye');
    togglePassword.classList.toggle('bi-eye-slash');
    });
  </script>
  <?php unset($_SESSION['toast']); ?>
<?php endif; ?>
</body>
</html>
