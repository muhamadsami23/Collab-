<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}

include '../assets/db.php';

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fetch project_id from URL
if(isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
} else {
    // Redirect if project_id is not provided in the URL
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle comment submission
    if (isset($_POST['submit_comment'])) {
        $user_email = $_SESSION['user_email'];
        $comment = sanitize_input($_POST['comment']);

        // Insert comment into database
        $sql = "INSERT INTO project_detail (project_id, user_email, comments) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $project_id, $user_email, $comment);
        $stmt->execute();
    }

    // Handle file upload
    if (isset($_FILES['file'])) {
        $user_email = $_SESSION['user_email'];
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];

        // Move uploaded file to desired location
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file_name);
        move_uploaded_file($file_tmp, $target_file);

        // Insert file into database
        $sql = "INSERT INTO project_detail (project_id, user_email, files) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $project_id, $user_email, $target_file);
        $stmt->execute();
    }

    // Handle image upload
    if (isset($_FILES['image'])) {
        $user_email = $_SESSION['user_email'];
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];

        // Move uploaded image to desired location
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image_name);
        move_uploaded_file($image_tmp, $target_file);

        // Insert image into database
        $sql = "INSERT INTO project_detail (project_id, user_email, images) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $project_id, $user_email, $target_file);
        $stmt->execute();
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Detail | Collab</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <!-- Poppins Font -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
        <link rel="stylesheet" href="user.css">
        <style>
            .btn-action {
            background-color: #007bff; /* Purple */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-action:hover {
            background-color: #7C3AED; /* Darker Purple */
        }

        .comment-input {
            border-color: #4A5568; /* Gray */
        }

        .comment-input:focus {
            border-color: #6D28D9; /* Purple */
        }
        .details-container {
            background-color: #F3F4F6; /* Light Gray */
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* Style for comments */
.comment-container {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 8px;
}

.comment {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #007bff; /* Change the background color as needed */
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 10px;
}

.comment-text {
    flex: 1;
    padding: 10px;
    background-color: #f5f5f5;
    border-radius: 8px;
}

        </style>
    </head>
<body>
<nav class="bg-gray-800 text-white py-4">
    <div class="container mx-auto flex justify-between items-center">
        <span class="font-bold text-xl">Collab Dashboard</span>
        <!-- Search bar -->
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
            <!-- Settings icon -->
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
                            <i class="fas fa-tasks mr-4 text-xl"></i> <span class="text-lg">Project Detail</span>
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
          


<div class="mb-8">
    
<div class="flex justify-between items-center">
    <h3 class="text-xl font-bold mb-4">Comments</h3>

    <div class="p-4">
        <form method="post" action="complete.php">
            <?php $project_id = $_GET['project_id']; ?>
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
            <button type="submit" name="mark_completed" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">
                Mark as Completed
            </button>
        </form>
    </div>
</div>   
    <div class="comment-container">
        
        <?php
        $sql = "SELECT user_email, comments FROM project_detail WHERE project_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $project_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Display comments
            while ($row = $result->fetch_assoc()) {
                $user_email = $row['user_email'];
                $comment = $row['comments'];
                $avatar_letter = strtoupper(substr($user_email, 0, 1));
                ?>
                <div class="comment">
                    <div class="avatar"><?php echo $avatar_letter; ?></div>
                    <div class="comment-text"><?php echo $comment; ?></div>
                </div>
                
                <?php
            }
        } else {
            // Display a message if no comments are present
            echo '<p>No comments added by the admin.</p>';
        }
        ?>
    </div>
</div>



<?php
$is_admin = false;

include '../assets/db.php';

$user_email = $_SESSION['user_email'];

$sql = "SELECT role FROM projects WHERE member_email = '$user_email'";

$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    if ($row['role'] === 'admin') {
        $is_admin = true; 
    }
}

if ($is_admin) {
    echo '
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold">Project Detail</h2>
        <!-- Buttons to upload comments, files, and images -->
        <div class="flex items-center">
            <button class="btn-action mr-4"><i class="far fa-comment"></i> Add Comment</button>
            <button class="btn-action mr-4"><i class="fas fa-paperclip"></i> Upload File</button>
            <button class="btn-action"><i class="far fa-image"></i> Upload Image</button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-bold mb-4">Add Comment</h3>
        <form method="post" action="">
            <input type="hidden" name="project_id" value="' . $project_id . '">
            <textarea name="comment" class="w-full h-20 border border-gray-300 rounded-md p-4 focus:outline-none focus:ring focus:border-purple-600 comment-input"></textarea>
            <button type="submit" name="submit_comment" class="btn-action mt-4">Submit</button>
        </form>
    </div>';
}
?>
</div>

</body>
</html>