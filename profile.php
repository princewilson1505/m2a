<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT id, username, nickname, profile_img, role FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: login.php");
    exit;
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = trim($_POST['nickname'] ?? '');
    $profile_img = $user['profile_img'];

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
            // Delete old profile image if it's not the default
            if ($user['profile_img'] && $user['profile_img'] !== 'uploads/default.png') {
                $old_path = __DIR__ . '/' . $user['profile_img'];
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
            }

            $new_filename = 'user_' . uniqid() . '.' . $file_ext;
            $file_path = $upload_dir . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $profile_img = 'uploads/' . $new_filename;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image format. Allowed: JPG, PNG, GIF.";
        }
    }

    // Update user profile if no error
    if (empty($error)) {
        $updateStmt = $conn->prepare("UPDATE users SET nickname = ?, profile_img = ? WHERE id = ?");
        $updateStmt->bind_param("ssi", $nickname, $profile_img, $user_id);
        if ($updateStmt->execute()) {
            $success = "Profile updated successfully!";
            $user['nickname'] = $nickname;
            $user['profile_img'] = $profile_img;
        } else {
            $error = "Failed to update profile. Please try again.";
        }
        $updateStmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | M2a</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/theme.css">
</head>
<body data-theme="light">
    <?php include 'nav.php'; ?>

    <div class="container mt-5 pt-4">
        <div class="row">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h2 class="card-title mb-4">Edit Profile</h2>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill"></i>
                                <?= htmlspecialchars($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <!-- Profile Picture Preview -->
                            <div class="text-center mb-4">
                                <?php
                                    $profileImg = $user['profile_img'] ? htmlspecialchars($user['profile_img']) : 'https://via.placeholder.com/150?text=User';
                                ?>
                                <img id="profilePreview" src="<?= $profileImg ?>" class="rounded-circle border" alt="Profile Picture" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>

                            <!-- Username (read-only) -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Username</strong></label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                                <small class="text-muted">Cannot be changed</small>
                            </div>

                            <!-- Nickname -->
                            <div class="mb-3">
                                <label for="nickname" class="form-label"><i class="bi bi-chat-dots"></i> Nickname</label>
                                <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Enter your nickname" maxlength="100" value="<?= htmlspecialchars($user['nickname'] ?? '') ?>">
                                <small class="text-muted">This is how others will see you</small>
                            </div>

                            <!-- Profile Image Upload -->
                            <div class="mb-3">
                                <label for="profile_image" class="form-label"><i class="bi bi-image"></i> Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" onchange="previewImage(event)">
                                <small class="text-muted">JPG, PNG, GIF (max 2MB)</small>
                            </div>

                            <!-- User Info (read-only) -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Role</strong></label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars(ucfirst($user['role'])) ?>" readonly>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-check-lg"></i> Save Changes
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Cancel
                                </a>
                            </div>
                        </form>

                        <hr class="my-4">

                        <!-- Change Password Option -->
                        <div class="text-center">
                            <a href="#" class="text-danger text-decoration-none" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="bi bi-key"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="change_password.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="oldPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="oldPassword" name="old_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview image before upload
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
