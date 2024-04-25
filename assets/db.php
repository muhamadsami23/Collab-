
<?php
$servername = "localhost"; // Assuming the server is localhost
$username = "root";
$password = "";
$database = "collab";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>