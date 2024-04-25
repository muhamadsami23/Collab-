<?php
include '../assets/db.php';

if (isset($_POST['mark_completed'])) {
    $project_id = $_POST['project_id'];
    $completion_status = 1; 

    $sql_update = "UPDATE projects SET completion_status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ii", $completion_status, $project_id); 

    if ($stmt_update->execute()) {
        header("Location: task.php");
        exit(); 
    } else {
        echo '<script>alert("Error marking project as completed: ' . $stmt_update->error . '");</script>';
        echo "SQL Query: " . $sql_update;
    }

    $stmt_update->close();
}

?>
