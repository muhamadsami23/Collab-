<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

include '../assets/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit-project"])) {
        // Get project form data
        $ad_email = $_SESSION['user_email'];
        $projectName = $_POST["project-name"];
        $orgName = $_POST["org-name"];
        $date = date("Y-m-d");
        $role = "admin"; 

        $sql_check = "SELECT * FROM projects WHERE project_name = ? AND admin_email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $projectName, $ad_email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            header("location: index.php?exist=true");
        } else {
            $sql = "INSERT INTO projects (project_name, org_name, date, role,admin_email) VALUES (?, ?, ?, ?,?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $projectName, $orgName, $date, $role,$ad_email);

            if ($stmt->execute() === TRUE) {
                header("location: index.php?success=true");

            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $stmt->close();
        }
    } elseif (isset($_POST["submit-member"])) { 
        $projectId = $_POST["project-id"];
        $memberEmail = $_POST["member-email"];

        $invitationCode = mt_rand(100000, 999999);

        $sql = "UPDATE projects SET member_email = ?, invitation_code = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $memberEmail, $invitationCode, $projectId);

        if ($stmt->execute()) {
            $to = $memberEmail;
            $subject = 'Invitation to join our project';
            $message = 'Dear member,<br><br>You have been invited to join our project. Please use the following code to accept the invitation: <strong>' . $invitationCode . '</strong>.<br><br>Thank you.';
            $headers = "From: your-email@example.com\r\n";
            $headers .= "Content-type: text/html\r\n";

            if (mail($to, $subject, $message, $headers)) {
                header("location: index.php?email=true");
            } else {
                header("location: index.php?email=false");
            }
        } else {
            header("location: index.php?email=false");
        }

        $stmt->close();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["join-project"])) {
    $invitationCode = $_POST["project-name"];

    $sql_check = "SELECT id, admin_email FROM projects WHERE invitation_code = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $invitationCode);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $projectId = $row["id"];
        $adminEmail = $row["admin_email"];
        $memberEmail = $_SESSION['user_email'];
        $role = "member";

        $sql_insert_member = "INSERT INTO projects (project_name, org_name, date, role, member_email, admin_email) SELECT project_name, org_name, date, ?, ?, ? FROM projects WHERE id = ?";
        $stmt_insert_member = $conn->prepare($sql_insert_member);
        $stmt_insert_member->bind_param("sssi", $role, $memberEmail, $adminEmail, $projectId);

        if ($stmt_insert_member->execute()) {
            header("location: index.php?join=true");
        } else {
            header("location: index.php?join=false");
        }

        $stmt_insert_member->close();
    } else {
        header("location: index.php?join=false");
    }

    $stmt_check->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}



// Close connection
$conn->close();
?>

