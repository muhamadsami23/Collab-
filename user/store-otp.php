<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}

$otp = rand(100000, 999999);

include '../assets/db.php';

$stmt = $conn->prepare('UPDATE users SET otp = ?, verified = 1 WHERE email = ?');
$stmt->bind_param('ss', $otp, $_SESSION['user_email']); 

if ($stmt->execute()) {
    $to = $_SESSION['user_email']; // User's email
    $subject = "Your OTP for Two-Factor Authentication";
    $message = "Hello,\n\nYour OTP for two-factor authentication is: $otp\n\nPlease use this OTP to verify your identity.\n\nThank you,\nYour Company Name";
    $headers = "From: mohdtaha9901@gmail.com";

    if (mail($to, $subject, $message, $headers)) {
        header("Location: secuirety.php?otp=true");
    } else {
        // Error in sending email
        echo "Error: Unable to send email.";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
