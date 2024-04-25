<?php
session_start();

// Check if user is logged in
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
        <title>User Dashboard</title>
        <!-- Tailwind CSS -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <!-- Poppins Font -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
        <link rel="stylesheet" href="user.css">
        <style>
.project-card.completed {
    border-left: 6px solid #34D399; 
    background-color: #F3F4F6; 
}

.project-card.completed:hover {
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.project-card.completed .add-member-btn {
    background-color: #E5E7EB; 
    color: #6B7280; 
}

.project-card.completed .add-member-btn:hover {
    background-color: #D1D5DB; 
}

        </style>
    </head>
    <body class="bg-gray-100">
        <!-- Navbar -->
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

        <div class="grid grid-cols-5 gap-4">
            <!-- Sidebar -->
            <div class="bg-gray-900 text-white col-span-1 h-screen flex flex-col justify-start items-start">
                
                <ul class="w-full pt-10">
                    <li class="py-6 px-8 sidebar-item transition duration-300 active">
                        <a href="#" class="flex items-center">
                            <i class="fas fa-home mr-4 text-xl"></i> <span class="text-lg">Home</span>
                        </a>
                    </li>
                    <li class="py-6 px-8 sidebar-item transition duration-300">
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
    <h1 class="text-3xl mb-4">Welcome to your Dashboard</h1>
    <!-- Add a project card -->
    <div class="bg-white rounded-md shadow-md cursor-pointer hover:bg-gray-200 transition flex items-center justify-between p-6 mb-4">
        <div>
            <h2 class="text-xl font-bold mb-2">Add a Project</h2>
            <p class="text-gray-600">Click here to add a new project</p>
        </div>
        <div class="ml-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-purple-600 hover:text-purple-800 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        </div>
    </div>

    <!-- Join a project card -->
    <div class="bg-white rounded-md shadow-md cursor-pointer hover:bg-gray-200 transition flex items-center justify-between p-6 mb-4">
        <div>
            <h2 class="text-xl font-bold mb-2">Join a Project</h2>
            <p class="text-gray-600">Click here to start working on a project using an invitation code</p>
        </div>
        <div class="ml-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 hover:text-blue-800 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        </div>
    </div>


 <!-- Project cards row -->
<h2 class="text-3xl py-3 font-semibold">Your Projects</h2>
<div class="grid grid-cols-4 gap-4">
    <?php
    include '../assets/db.php';
    
    // Check if the user is logged in
    if(isset($_SESSION['user_email'])) {
        $ad_email = $_SESSION['user_email'];
        
        $sql = "SELECT id, project_name, org_name, DATE_FORMAT(date, '%M %d, %Y') AS formatted_date, completion_status FROM projects WHERE admin_email = ? LIMIT 3";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ad_email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $project_id = $row['id'];
                $projectName = $row["project_name"];
                $orgName = $row["org_name"];
                $date = $row["formatted_date"]; 
                $completionStatus = $row["completion_status"]; 
                
                $completedClass = ($completionStatus == 1) ? 'completed' : '';
                
                ?>
                <!-- Project card -->
                <div class="project-card bg-white rounded-lg shadow-lg overflow-hidden flex items-center justify-between <?php echo $completedClass; ?>">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-2"><?php echo $projectName; ?></h2>
                        <p class="text-gray-600 mb-2"><?php echo $orgName; ?></p>
                        <p class="text-gray-500 text-sm">Date: <?php echo $date; ?></p>
                    </div>
                    <div class="p-4 flex justify-end">
                        <!-- Add Member button -->
                        <button class="add-member-btn flex items-center justify-center bg-blue-500 text-white rounded-full w-10 h-10 hover:bg-blue-600 transition" data-project-id="<?php echo $project_id; ?>" <?php echo ($completionStatus == 1) ? 'disabled' : ''; ?>>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "No projects found";
        }
    } else {
        echo "Please log in to view your projects";
    }
    ?>
</div>





<!-- Project Modal -->
<div id="myModal" class="modal">
        <div class="modal-content bg-white rounded-lg shadow-lg">
            <span class="close">&times;</span>
            <h2 class="text-xl font-bold mb-4 text-center">Add a Project</h2>
            <form class="px-6 pb-8" method="post" action="submit.php">
                <div class="mb-4">
                    <label for="project-name" class="block text-sm font-medium text-gray-700">Project Name</label>
                    <input type="text" id="project-name" name="project-name" class="mt-1 p-3 border border-gray-300 rounded-md w-full" placeholder="Enter project name">
                </div>
                <div class="mb-4">
                    <label for="org-name" class="block text-sm font-medium text-gray-700">Organization</label>
                    <input type="text" id="org-name" name="org-name" class="mt-1 p-3 border border-gray-300 rounded-md w-full" placeholder="Enter organization">
                </div>
                <p class="text-gray-600 mb-4">You are an administrator.</p>
                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md" name="submit-project">Add Project</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Project Modal -->
<div id="myModal2" class="modal">
        <div class="modal-content bg-white rounded-lg shadow-lg">
            <span class="close">&times;</span>
            <h2 class="text-xl font-bold mb-4 text-center">Join a Project</h2>
            <form class="px-6 pb-8" method="post" action="submit.php">
                <div class="mb-4">
                    <label for="project-name" class="block text-sm font-medium text-gray-700">Invitation code</label>
                    <input type="text" id="project-name" name="project-name" class="mt-1 p-3 border border-gray-300 rounded-md w-full" placeholder="Enter code" required>
                </div>
                <div class="flex justify-center">
                    <button type="submit" name="join-project"class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md" name="join-project">Join Project</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for adding a member -->
    <div id="addMemberModal" class="modal">
        <div class="modal-content bg-white rounded-lg shadow-lg">
            <span class="close2">&times;</span>
            <h2 class="text-xl font-bold mb-4 text-center">Add a Member</h2>
            <form class="px-6 pb-8" method="post" action="submit.php">
                <div class="mb-4">
                    <label for="project-name" class="block text-sm font-medium text-gray-700">Member Email</label>
                    <input type="email" id="member-email" name="member-email" class="mt-1 p-3 border border-gray-300 rounded-md w-full" placeholder="Enter project name" required>
                </div>
                <input type="hidden" id="project-id" name="project-id" value="">

                <p class="text-gray-600 mb-4">You are an administrator.</p>
                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md" name="submit-member">Add Project</button>
                </div>
            </form>
        </div>
    </div>



        <!-- Font Awesome JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

        <script>
    var modal = document.getElementById("myModal");
    var modal2 = document.getElementById("myModal2");
    var span = modal.getElementsByClassName("close")[0];
    var span2 = modal2.getElementsByClassName("close")[0];

    var card = document.querySelector(".col-span-4 .bg-white");
    var card2 = document.querySelector(".join");

    card.onclick = function() {
        modal.style.display = "block";
    }

    card2.onclick = function() {
        modal2.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    span2.onclick = function() {
        modal2.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        } else if (event.target == modal2) {
            modal2.style.display = "none";
        }
    }
</script>

        <!-- Font Awesome JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<script>
const driver = window.driver.js.driver;

const driverObj = driver({
  showButtons: [
    'next',
    'previous',
    'skip', 
    'close'
  ],
  color: 'rgb(119, 35, 215)', 
  steps: [
    {
      element: ".search-bar",
      popover: {
        title: "Search Bar",
        description: "Search anything using our search bar."
      }
    },
    {
      element: '.sidebar-item.active', 
      popover: {
        title: 'Sidebar',
        description: 'This is the sidebar. You can navigate through different sections of the dashboard using these links.',
        position: 'right'
      }
    },
    {
      element: '.bg-white.rounded-md.shadow-md', 
      popover: {
        title: 'Add a Project',
        description: 'Click here to add a new project.',
        position: 'top'
      }
    },
    {
      element: '.sidebar-item:nth-child(1) a', 
      popover: {
        title: 'Home',
        description: 'This is the Home page. Click here to navigate to the Home section.',
        position: 'right'
      }
    },
    {
      element: '.sidebar-item:nth-child(2) a', 
      popover: {
        title: 'Manage Your Task',
        description: 'This is the Manage Your Task page. Click here to navigate to the Task Management section.',
        position: 'right'
      }
    },
    {
      element: '.sidebar-item:nth-child(3) a', 
      popover: {
        title: 'Calendar',
        description: 'This is the Calendar page. Click here to navigate to the Calendar section.',
        position: 'right'
      }
    },
    {
      element: '.sidebar-item:nth-child(4) a', 
      popover: {
        title: 'Backup',
        description: 'This is the Backup page. Click here to navigate to the Backup section.',
        position: 'right'
      }
    },
    {
      element: '.sidebar-item:nth-child(5) a', 
      popover: {
        title: 'Security Trails',
        description: 'This is the Security Trails page. Click here to navigate to the Security Trails section.',
        position: 'right'
      }
    },
    {
      element: '.sidebar-item:nth-child(6) a', 
      popover: {
        title: 'Logout',
        description: 'Click here to log out from your account.',
        position: 'right'
      }
    },
  ]
});

driverObj.drive();

document.addEventListener('DOMContentLoaded', function() {
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationPanel = document.getElementById('notificationPanel');

    notificationIcon.addEventListener('click', function(event) {
        event.stopPropagation(); 
        notificationPanel.classList.toggle('hidden');
    });

    window.addEventListener('click', function(event) {
        const target = event.target;
        const isNotificationIcon = target === notificationIcon || notificationIcon.contains(target);
        const isNotificationPanel = target === notificationPanel || notificationPanel.contains(target);

        if (!isNotificationIcon && !isNotificationPanel) {
            notificationPanel.classList.add('hidden');
        }
    });
});
</script>
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("addMemberModal");

    const addMemberButtons = document.querySelectorAll('.add-member-btn');
    const addMemberModal = document.getElementById('addMemberModal');

    addMemberButtons.forEach(button => {
        button.addEventListener('click', function() {
            addMemberModal.style.display = "block"; // Show the modal using pure JavaScript

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
