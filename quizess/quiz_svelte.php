<?php
session_start();
include '../config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

$category_id = 5; // Svelte
$category_name = "Svelte";
include 'quiz_template.php';
