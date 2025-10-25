<?php
session_start();

// Only allow admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../config.php';

// Read and validate parameters
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$lessonId = isset($_GET['lesson']) ? (int)$_GET['lesson'] : 0;

if ($id <= 0) {
    // invalid id — redirect back to edit page (if lesson id provided) or to manage page
    if ($lessonId > 0) {
        header("Location: edit_lesson.php?id={$lessonId}&error=invalid_id");
    } else {
        header('Location: manage_lesson.php');
    }
    exit;
}

// Delete the section using a prepared statement to avoid injection
if ($stmt = $conn->prepare('DELETE FROM lesson_sections WHERE id = ? LIMIT 1')) {
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Optionally check affected rows
    $deleted = ($stmt->affected_rows > 0);
    $stmt->close();

    if ($lessonId > 0) {
        header('Location: edit_lesson.php?id=' . $lessonId . '&section_deleted=' . ($deleted ? '1' : '0'));
    } else {
        header('Location: manage_lesson.php?section_deleted=' . ($deleted ? '1' : '0'));
    }
    exit;
} else {
    // Prepare failed — log and redirect
    error_log('delete_section.php: prepare failed - ' . $conn->error);
    if ($lessonId > 0) {
        header('Location: edit_lesson.php?id=' . $lessonId . '&error=prepare_failed');
    } else {
        header('Location: manage_lesson.php?error=prepare_failed');
    }
    exit;
}

?>