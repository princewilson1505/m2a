<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "m3a_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>