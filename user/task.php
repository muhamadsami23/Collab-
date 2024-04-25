<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Task</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <!-- Poppins Font -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
        <link rel="stylesheet" href="user.css">
    </head>
<body>
<nav class="bg-gray-800 text-white py-4">
    <div class="container mx-auto flex justify-between items-center">
        <span class="font-bold text-xl">Collab Dashboard</span>
        <div class="relative mx-auto">
            <input type="text" class="search-bar py-2 px-4 w-full" placeholder="Search...">
            <span class="absolute right-4 top-2 text-gray-500"><i class="fas fa-search"></i></span>
        </div>
        <div>
            <!-- Notification icon with count -->
            <a href="#" class="relative mr-4 text-xl text-gray-300 hover:text-gray-100 transition" id="notificationIcon">
                <i class="fas fa-bell"></i>
                <span class="absolute top-0 right-0 bg-red-500 text-white px-1 py-0.5 rounded-full text-xs" id="notificationCount">3</span>
            </a>
            <a href="#" class="text-xl text-gray-300 hover:text-gray-100 transition"><i class="fas fa-cog"></i></a>
        </div>
    </div>
</nav>

<!-- Notification panel -->
<div id="notificationPanel" class="hidden fixed top-16 right-4 bg-white p-4 rounded-md shadow-md z-10">
    <!-- Notifications -->
    <div class="notification flex items-center mb-2">
        <i class="fas fa-user-circle text-blue-500 mr-2"></i>
        <span>User just logged in</span>
    </div>
    <div class="notification flex items-center mb-2">
        <i class="fas fa-project-diagram text-green-500 mr-2"></i>
        <span>User added a new project</span>
    </div>
    <div class="notification flex items-center">
        <i class="fas fa-arrow-up text-yellow-500 mr-2"></i>
        <span>Collab new version is available</span>
    </div>
</div>

        <!-- Main container using grid -->
        <div class="grid grid-cols-5 gap-4">
            <!-- Sidebar -->
            <div class="bg-gray-900 text-white col-span-1 h-screen flex flex-col justify-start items-start">
                
                <ul class="w-full pt-10">
                    <li class="py-6 px-8 sidebar-item transition duration-300">
                        <a href="index.php" class="flex items-center">
                            <i class="fas fa-home mr-4 text-xl"></i> <span class="text-lg">Home</span>
                        </a>
                    </li>
                    <li class="py-6 px-8 sidebar-item transition duration-300 active">
                        <a href="task.php" class="flex items-center">
                            <i class="fas fa-tasks mr-4 text-xl"></i> <span class="text-lg">Manage Your Task</span>
                        </a>
                    </li>
                    <li class="py-6 px-8 sidebar-item transition duration-300">
                        <a href="calender.php" class="flex items-center">
                            <i class="far fa-calendar-alt mr-4 text-xl"></i> <span class="text-lg">Calendar</span>
                        </a>
                    </li>
                    <li class="py-6 px-8 sidebar-item transition duration-300">
                    <a href="backup.php" class="flex items-center">
                            <i class="fas fa-hdd mr-4 text-xl"></i> <span class="text-lg">Backup</span>
                        </a>
                    </li>
                    <li class="py-6 px-8 sidebar-item transition duration-300">
                        <a href="secuirety.php" class="flex items-center">
                            <i class="fas fa-shield-alt mr-4 text-xl"></i> <span class="text-lg">Security Trails</span>
                        </a>
                    </li>
                    <li class="py-6 px-8 sidebar-item transition duration-300">
                        <a href="logout.php" class="flex items-center">
                            <i class="fas fa-sign-out-alt mr-4 text-xl"></i> <span class="text-lg">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-span-4 p-8">
            <?php

include '../assets/db.php';

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];

    $sql_admin = "SELECT id, project_name, org_name, DATE_FORMAT(date, '%M %d, %Y') AS formatted_date FROM projects WHERE admin_email = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("s", $user_email);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    if ($result_admin->num_rows > 0) {
        ?>
        <!-- Projects where user is an admin -->
        <h2 class="text-2xl font-bold mb-4">Projects You Administer</h2>
        <?php
        while ($row_admin = $result_admin->fetch_assoc()) {
            $project_id_admin = $row_admin['id'];
            $projectName_admin = $row_admin["project_name"];
            $orgName_admin = $row_admin["org_name"];
            $date_admin = $row_admin["formatted_date"];
            ?>
            <!-- Project card for admin -->
            <a href="project_detail.php?project_id=<?php echo $project_id_admin; ?>" class="project-card bg-white rounded-lg shadow-lg overflow-hidden flex items-center justify-between">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-2"><?php echo $projectName_admin; ?></h2>
                    <p class="text-gray-600 mb-2"><?php echo $orgName_admin; ?></p>
                    <p class="text-gray-500 text-sm">Date: <?php echo $date_admin; ?></p>
                </div>
                <div class="p-4 flex justify-end">
                    <!-- Add Member button for admin -->
                    <button class="add-member-btn flex items-center justify-center bg-blue-500 text-white rounded-full w-10 h-10 hover:bg-blue-600 transition" data-project-id="<?php echo $project_id_admin; ?>">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                
            </a>

            <?php
        }
    } else {
        echo "You are not administering any projects";
    }

    $sql_member = "SELECT id, project_name, org_name, DATE_FORMAT(date, '%M %d, %Y') AS formatted_date FROM projects WHERE id IN (SELECT id FROM projects WHERE member_email = ?)";
    $stmt_member = $conn->prepare($sql_member);
    $stmt_member->bind_param("s", $user_email);
    $stmt_member->execute();
    $result_member = $stmt_member->get_result();

    // Display projects where the user is a member
    if ($result_member->num_rows > 0) {
        ?>
        <!-- Projects where user is a member -->
        <h2 class="text-2xl font-bold mb-4">Projects You Are a Member Of</h2>
        <?php
        while ($row_member = $result_member->fetch_assoc()) {
            $project_id_member = $row_member['id'];
            $projectName_member = $row_member["project_name"];
            $orgName_member = $row_member["org_name"];
            $date_member = $row_member["formatted_date"];
            ?>
            <!-- Project card for member -->
<a href="project_detail.php?project_id=<?php echo $project_id_member; ?>" class="block mb-8">
    <div class="project-card bg-white rounded-lg shadow-lg overflow-hidden flex items-center justify-between">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-2"><?php echo $projectName_member; ?></h2>
            <p class="text-gray-600 mb-2"><?php echo $orgName_member; ?></p>
            <p class="text-gray-500 text-sm">Date: <?php echo $date_member; ?></p>
        </div>
        <div class="p-4 flex justify-end">
            <!-- Member icon for non-admin users -->
            <span class="text-gray-400">
                <i class="fas fa-user"></i>
            </span>
        </div>
    </div>
</a>

            <?php
        }
    } else {
        echo "You are not a member of any projects";
    }
} else {
    echo "Please log in to view your projects";
}
?>
 <!-- Modal for adding a member -->
 <div id="addMemberModal" class="modal">
        <div class="modal-content bg-white rounded-lg shadow-lg">
            <span class="close2">&times;</span>
            <h2 class="text-xl font-bold mb-4 text-center">Add a Member</h2>
            <form class="px-6 pb-8" method="post" action="submit.php">
                <div class="mb-4">
                    <label for="project-name" class="block text-sm font-medium text-gray-700">Member Email</label>
                    <input type="email" id="member-email" name="member-email" class="mt-1 p-3 border border-gray-300 rounded-md w-full" placeholder="Enter project name">
                </div>
                <input type="hidden" id="project-id" name="project-id" value="">

                <p class="text-gray-600 mb-4">You are an administrator.</p>
                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md" name="submit-member">Add Project</button>
                </div>
            </form>
        </div>
    </div>
        </div>
       
        
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("addMemberModal");

    const addMemberButtons = document.querySelectorAll('.add-member-btn');
    const addMemberModal = document.getElementById('addMemberModal');

    addMemberButtons.forEach(button => {
        button.addEventListener('click', function() {
            addMemberModal.style.display = "block"; 

            const projectId = button.dataset.projectId;
            document.getElementById('project-id').value = projectId;
        });
    });
     window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
    
});
</script>
   

</body>
</html>