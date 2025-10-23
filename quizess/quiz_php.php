<?php
session_start();
include '../config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

$category_id = 4; // PHP
$category_name = "PHP";
include 'quiz_template.php';
