<?php

session_start();
include 'config.php';

$firstname = htmlspecialchars($_POST['firstname']);
$lastname = htmlspecialchars($_POST['lastname']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$comment = htmlspecialchars($_POST['comment']);

// Prepare SQL query
$sql = "INSERT INTO contact_form (firstname, lastname, email, phone, comment)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $comment);

// Execute and confirm
if ($stmt->execute()) {
    echo "Thank you! Your message has been received.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>