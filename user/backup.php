<?php
session_start();

    include '../assets/db.php'; 

    if (isset($_POST['auto_backup'])) {
        $user_email = $_SESSION['user_email'];
        $sql = "INSERT INTO project_detail_backup SELECT * FROM project_detail WHERE user_email = ? AND project_id NOT IN (SELECT project_id FROM project_detail_backup)";
                $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("s", $user_email);
                if ($stmt->execute()) {
            header("location: backup.php?backup=success");
        } else {
            header("location: backup.php?backup=error");
        }
        
        $stmt->close();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup | Collab</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <!-- Poppins Font -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
        <link rel="stylesheet" href="user.css">
        <style>
        .backup-card {
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        .backup-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .backup-card h3 {
            color: #1a202c;
        }
        .backup-card p {
            color: #4a5568;
        }
        .backup-card button {
            background-color: #4c51bf;
        }
        .backup-card button:hover {
            background-color: #4338ca;
        }
        .backup-card .icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
    </style>
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
            <a href="#" class="relative mr-4 text-xl text-gray-300 hover:text-gray-100 transition" id="notificationIcon">
                <i class="fas fa-bell"></i>
                <span class="absolute top-0 right-0 bg-red-500 text-white px-1 py-0.5 rounded-full text-xs" id="notificationCount">3</span>
            </a>
            <!-- Settings icon -->
            <a href="#" class="text-xl text-gray-300 hover:text-gray-100 transition"><i class="fas fa-cog"></i></a>
        </div>
    </div>
</nav>

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
                    <li class="py-6 px-8 sidebar-item transition duration-300">
                        <a href="task.php" class="flex items-center">
                            <i class="fas fa-tasks mr-4 text-xl"></i> <span class="text-lg">Project Detail</span>
                        </a>
                    </li>
                    <li class="py-6 px-8 sidebar-item transition duration-300">
                        <a href="calender.php" class="flex items-center">
                            <i class="far fa-calendar-alt mr-4 text-xl"></i> <span class="text-lg">Calendar</span>
                        </a>
                    </li>
                    <li class="py-6 px-8 sidebar-item transition duration-300 active">
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
        
<h2 class="text-3xl font-bold py-5">Add a Backup Option </h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-md p-6 backup-card">
                    <i class="fas fa-cloud-upload-alt icon text-blue-500"></i>
                    <h3 class="text-lg font-semibold mb-4">Automatic Backup</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <p class="text-gray-600">Automatically backup your data periodically.</p>
                    <button class="text-white px-4 py-2 mt-4 rounded-md hover:bg-blue-600 backup-btn" type="submit" name="auto_backup" data-message="Automatic backup enabled!">Enable</button>
                    </form>
                </div>

            <div class="bg-white rounded-lg shadow-md p-6 backup-card">
                <i class="fas fa-hdd icon text-green-500"></i>
                <h3 class="text-lg font-semibold mb-4">Manual Backup</h3>
                <p class="text-gray-600">Backup your data manually whenever you need.</p>
                <button class="text-white px-4 py-2 mt-4 rounded-md hover:bg-green-600">Backup Now</button>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 backup-card">
                <i class="fas fa-calendar-alt icon text-yellow-500"></i>
                <h3 class="text-lg font-semibold mb-4">Scheduled Backup</h3>
                <p class="text-gray-600">Schedule backups to run at specific times.</p>
                <button class="text-white px-4 py-2 mt-4 rounded-md hover:bg-yellow-600">Schedule</button>
            </div>
        </div>
    </div>
    </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
      const urlParams = new URLSearchParams(window.location.search);
     const backupStatus = urlParams.get('backup');
        
    if (backupStatus === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Backup Successful!',
            text: 'Your data has been successfully backed up.'
        });
    } else if (backupStatus === 'error') {
        Swal.fire({
            icon: 'error',
            title: 'Backup Error',
            text: 'There was an error while backing up your data. Please try again later.'
        });
    }
</script>

</body>
</html>