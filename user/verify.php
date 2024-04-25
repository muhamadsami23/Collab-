<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['entered_otp'])) {
    $enteredOTP = $_POST['entered_otp'];

    include '../assets/db.php';

    $stmt = $conn->prepare('SELECT otp FROM users WHERE email = ?');
    $stmt->bind_param('s', $_SESSION['user_email']); 

    $stmt->execute();

    $stmt->bind_result($storedOTP);
    $stmt->fetch();
    $stmt->close();
    if ($enteredOTP == $storedOTP) {

        $to = $_SESSION['user_email']; 
        $subject = 'OTP Verification Success'; 
        $message = 'Hello, your OTP verification was successful. You are now verified!'; 
        $headers = 'From: mohdtaha9901@gmail.com'; 

        // Send email
        if (mail($to, $subject, $message, $headers)) {
            header("Location: secuirety.php?verify=true");
            exit();
        } else {
            echo "Error: Unable to send email. Please try again later.";
        }
    } else {
        echo "Invalid OTP. Please try again.";
    }

    $conn->close();
} else {
    header("Location: secuirety.php?verify=false");
    exit();
}
?>
