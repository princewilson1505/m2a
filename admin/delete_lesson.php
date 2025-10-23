<?php
session_start();
include '../config.php';

// ✅ Only allow admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ✅ Check if 'id' exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin.php?error=no_id");
    exit;
}

$id = intval($_GET['id']);

// ✅ Delete related sections first (foreign key constraint)
$stmt1 = $conn->prepare("DELETE FROM lesson_sections WHERE lesson_id = ?");
$stmt1->bind_param("i", $id);
$stmt1->execute();

// ✅ Then delete the lesson itself
$stmt2 = $conn->prepare("DELETE FROM lessons WHERE id = ?");
$stmt2->bind_param("i", $id);

if ($stmt2->execute()) {
    // ✅ Redirect back with success flag
    header("Location: admin.php?deleted=1");
    exit;
} else {
    // ✅ Handle error (like missing foreign key setup)
    header("Location: admin.php?error=delete_failed");
    exit;
}
?>
