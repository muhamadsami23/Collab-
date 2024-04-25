<?php
include 'assets/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_email_sql);

    if (strlen($password) < 6) {
        header("location: login.php?success=false&error=password_validation");
        exit(); 
    }

    if ($result->num_rows > 0) {
        header("location: login.php?success=false&error=email_exists");
        exit(); 
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

    if ($conn->query($insert_sql) === TRUE) {
        header("location: login.php?success=true");
    } else {
        header("location: login.php?success=false&error=db_error");
    }
}

$conn->close();
?>
