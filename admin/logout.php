<?php
session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
  session_destroy();
  header('Location: login.php');
  exit;
}

header('Location: ../login.php');
exit;

