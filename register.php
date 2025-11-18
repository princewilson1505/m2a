<?php
session_start();
include 'config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $nickname = trim($_POST['nickname'] ?? '');

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if username exists
        $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists!";
        } else {
            $profile_img = 'uploads/default.png';

            // Handle file upload if present
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $file = $_FILES['profile_image'];
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($file_ext, $allowed_exts)) {
                    $new_filename = 'user_' . uniqid() . '.' . $file_ext;
                    $file_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($file['tmp_name'], $file_path)) {
                        $profile_img = 'uploads/' . $new_filename;
                    }
                }
            }

            // Insert without hashing
            $stmt = $conn->prepare("INSERT INTO users (username, password, nickname, profile_img, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->bind_param("ssss", $username, $password, $nickname, $profile_img);
            $stmt->execute();

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => 'Account created successfully! Please log in.'
            ];

            header("Location: login.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | M3a: Programming Languages Learning Guide</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="assets/icons/font/bootstrap-icons.min.css">
    <style>
      .auth-card { border-radius: 24px; overflow: hidden; }
      .input-pill { border-radius: 999px; padding: 0.65rem 1.1rem; box-shadow: inset 0 1px 2px rgba(0,0,0,0.08); }
      .toast-glow { box-shadow: 0 10px 30px rgba(76, 175, 80, 0.3); }
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
                        <div class="text-center mb-3">
                            <img src="assets/icon.png" alt="logo" height="60">
                            <h3 class="mt-3 mb-0">Create account</h3>
                            <small class="text-muted">Join the M2a learning community</small>
                        </div>

                        <form method="POST" autocomplete="off" enctype="multipart/form-data">
                            <div class="mb-4 d-flex align-items-center border input-pill">
                                <i class="bi bi-person text-primary me-2"></i>
                                <input type="text" class="form-control border-0" name="username" placeholder="Username" maxlength="50" required value="<?= htmlspecialchars($username ?? '') ?>">
                            </div>
                            <div class="mb-4 d-flex align-items-center border input-pill">
                                <i class="bi bi-lock text-primary me-2"></i>
                                <input type="password" class="form-control border-0" name="password" placeholder="Password" required>
                            </div>
                            <div class="mb-4 d-flex align-items-center border input-pill">
                                <i class="bi bi-lock-fill text-primary me-2"></i>
                                <input type="password" class="form-control border-0" name="confirm_password" placeholder="Confirm Password" required>
                            </div>

                            <div class="text-center">
                                <button name="register" class="btn btn-primary w-100 rounded-pill py-2" type="submit">SIGN UP</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            Already have an account? <a href="login.php">Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
