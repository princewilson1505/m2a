<?php
session_start();
include 'config.php';

$firstname = htmlspecialchars($_POST['firstname']);
$lastname = htmlspecialchars($_POST['lastname']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$comment = htmlspecialchars($_POST['comment']);

$sql = "INSERT INTO contact_form (firstname, lastname, email, phone, comment)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $comment);

if ($stmt->execute()) {
    $_SESSION['toast'] = [
        "type" => "success",
        "message" => "Thank you! Your message has been received."
    ];
} else {
    $_SESSION['toast'] = [
        "type" => "danger",
        "message" => "Error: " . $stmt->error
    ];
}

$stmt->close();
$conn->close();

// Redirect back to index page
header("Location: index.php");
exit;
?>
