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
</head>
<body class="bg-primary">

<section>
    <div class="container mt-5">
        <div class="row">
            <div class="col-8 col-sm-6 col-md-4 m-auto">
                <div class="card bg-light border border-transparent shadow-lg">
                    <div class="card-body">
                        <h2 class="text-center my-3">Register</h2>
                        <p class="text-center text-muted" style="font-family:'Courier New',Courier,monospace;font-weight:bold;">
                            M2a: Programming Languages Learning Guide
                        </p>

                        <form method="POST" autocomplete="off" enctype="multipart/form-data">
                            <div class="mb-4 mx-5 d-flex align-items-center">
                                <i class="bi bi-person me-2"></i>
                                <input type="text" class="form-control" name="username" placeholder="Username" maxlength="50" required value="<?= htmlspecialchars($username ?? '') ?>">
                            </div>
                            <div class="mb-4 mx-5 d-flex align-items-center">
                                <i class="bi bi-chat-dots me-2"></i>
                                <input type="text" class="form-control" name="nickname" placeholder="Nickname (optional)" maxlength="100" value="<?= htmlspecialchars($nickname ?? '') ?>">
                            </div>
                            <div class="mb-4 mx-5">
                                <label class="form-label"><i class="bi bi-image"></i> Profile Image (optional)</label>
                                <input type="file" class="form-control" name="profile_image" accept="image/*">
                                <small class="text-muted">JPG, PNG, GIF (max 2MB)</small>
                            </div>
                            <div class="mb-4 mx-5 d-flex align-items-center">
                                <i class="bi bi-lock me-2"></i>
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                            <div class="mb-4 mx-5 d-flex align-items-center">
                                <i class="bi bi-lock-fill me-2"></i>
                                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                            </div>

                            <div class="text-center mx-5">
                                <button name="register" class="btn btn-primary w-100 rounded-pill" type="submit">SIGN UP</button>
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
