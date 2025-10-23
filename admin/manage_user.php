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
    header("Location: manage_users.php");
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
  <style>
    body {
      background-color: #f8f9fa;
      overflow: hidden; /* prevent double scrollbars */
    }

    .main-container {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 250px;
      background-color: #111;
      color: white;
      overflow-y: auto;
    }

    .content-area {
      flex-grow: 1;
      overflow-y: auto;
      padding: 20px;
    }

    table th, table td {
      vertical-align: middle !important;
    }

    .table-responsive {
      max-height: 400px;
      overflow-y: auto;
    }

    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .card {
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="main-container">
  <div class="sidebar">
    <?php include 'sidebar.php'; ?>
  </div>

  <div class="content-area">
    <div class="container-fluid">
      <h4 class="mb-3"><i class="bi bi-people"></i> Manage Users</h4>
      <?php if (isset($msg)) echo $msg; ?>

      <!-- ✅ Add User -->
      <div class="card mb-4">
        <div class="card-header bg-dark text-white">Add New User</div>
        <div class="card-body">
          <form method="POST">
            <div class="row g-2">
              <div class="col-md-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
              </div>
              <div class="col-md-3">
                <input type="text" name="password" class="form-control" placeholder="Password" required>
              </div>
              <div class="col-md-3">
                <select name="role" class="form-select">
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <div class="col-md-3">
                <button name="add_user" class="btn btn-danger w-100">Add User</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- ✅ Search -->
      <form method="GET" class="mb-3">
        <div class="input-group" style="max-width:400px;">
          <input type="text" name="search" class="form-control" placeholder="Search user or role..." value="<?= htmlspecialchars($search) ?>">
          <button class="btn btn-outline-danger" type="submit"><i class="bi bi-search"></i></button>
        </div>
      </form>

      <!-- ✅ User List -->
      <div class="card mb-3">
        <div class="card-header bg-dark text-white">User List</div>
        <div class="card-body table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Role</th>
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
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id'] ?>"><i class="bi bi-pencil"></i></button>
                      <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')"><i class="bi bi-trash"></i></a>
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
                              <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" required>
                            </div>
                            <div class="mb-3">
                              <label>Password</label>
                              <input type="text" name="password" class="form-control" value="<?= htmlspecialchars($row['password']) ?>" required>
                            </div>
                            <div class="mb-3">
                              <label>Role</label>
                              <select name="role" class="form-select">
                                <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                              </select>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" name="update_user" class="btn btn-success">Save Changes</button>
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
