<?php
session_start();
include '../config.php';

// ✅ Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ✅ Handle Create
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $check = $conn->prepare("SELECT * FROM users WHERE username=?");
    $check->bind_param("s", $username);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $msg = "<div class='alert alert-warning mt-2'>⚠️ Username already exists!</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);
        $stmt->execute();
        $msg = "<div class='alert alert-success mt-2'>✅ User added successfully!</div>";
    }
}

// ✅ Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: manage_user.php");
    exit;
}

// ✅ Handle Update
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, password=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $password, $role, $id);
    $stmt->execute();
    $msg = "<div class='alert alert-info mt-2'>✅ User updated successfully!</div>";
}

// ✅ Search & Pagination
$search = $_GET['search'] ?? '';
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$countSql = "SELECT COUNT(*) AS total FROM users WHERE username LIKE ? OR role LIKE ?";
$countStmt = $conn->prepare($countSql);
$searchTerm = "%$search%";
$countStmt->bind_param("ss", $searchTerm, $searchTerm);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$sql = "SELECT * FROM users WHERE username LIKE ? OR role LIKE ? ORDER BY id ASC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $searchTerm, $searchTerm, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-page">

<div class="d-flex admin-shell">
  <?php include 'sidebar.php'; ?>

  <div class="flex-grow-1 p-4" style="max-height:100vh;overflow-y:auto;">
    <div class="admin-hero text-white p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h2 class="mb-1 fw-bold"><i class="bi bi-people me-2"></i>User Directory</h2>
        <p class="mb-0">Manage credentials and roles for every learner and admin.</p>
      </div>
      <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control admin-pill-input" placeholder="Search user or role..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-light admin-quick-action" type="submit"><i class="bi bi-search me-1"></i>Search</button>
      </form>
    </div>
      <?php if (isset($msg)) echo "<div class='admin-card p-3 mb-3'>$msg</div>"; ?>

      <!-- ✅ Add User -->
      <div class="admin-card admin-card-hover p-4 mb-4">
        <h5 class="mb-3 text-primary">Add New User</h5>
        <form method="POST">
          <div class="row g-2">
            <div class="col-md-3">
              <input type="text" name="username" class="form-control admin-pill-input" placeholder="Username" required>
            </div>
            <div class="col-md-3">
              <input type="text" name="password" class="form-control admin-pill-input" placeholder="Password" required>
            </div>
            <div class="col-md-3">
              <select name="role" class="form-select admin-pill-input">
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="col-md-3">
              <button name="add_user" class="btn admin-gradient-btn w-100">Add User</button>
            </div>
          </div>
        </form>
      </div>

      <!-- ✅ User List -->
      <div class="admin-card p-3 mb-3">
        <div class="table-responsive">
          <table class="table table-hover align-middle text-center">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th width="120">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['password']) ?></td>
                    <td>
                      <button class="btn btn-sm btn-outline-primary admin-quick-action" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id'] ?>"><i class="bi bi-pencil"></i></button>
                      <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger admin-quick-action" onclick="return confirm('Delete this user?')"><i class="bi bi-trash"></i></a>
                    </td>
                  </tr>

                  <!-- ✅ Edit Modal -->
                  <div class="modal fade" id="edit<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form method="POST">
                          <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="mb-3">
                              <label>Username</label>
                              <input type="text" name="username" class="form-control admin-pill-input" value="<?= htmlspecialchars($row['username']) ?>" required>
                            </div>
                            <div class="mb-3">
                              <label>Password</label>
                              <input type="text" name="password" class="form-control admin-pill-input" value="<?= htmlspecialchars($row['password']) ?>" required>
                            </div>
                            <div class="mb-3">
                              <label>Role</label>
                              <select name="role" class="form-select admin-pill-input">
                                <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                              </select>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" name="update_user" class="btn admin-gradient-btn">Save Changes</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="5" class="text-center text-muted">No users found</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ✅ Pagination -->
      <nav>
        <ul class="pagination">
          <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a></li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
              <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $totalPages): ?>
            <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
