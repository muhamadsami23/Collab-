<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}

include '../assets/db.php';
$stmt = $conn->prepare('SELECT verified FROM users WHERE email = ?');
$stmt->bind_param('s', $_SESSION['user_email']); 
$stmt->execute();
$stmt->bind_result($verified);
$stmt->fetch();
$stmt->close();

$conn->close();
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">

        <style>
    .modal-content input[type="email"],
    .modal-content input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .modal-content input[type="email"]:focus,
    .modal-content input[type="text"]:focus {
        border-color: #4CAF50;
        outline: none;
    }

    .modal-content button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .modal-content button:hover {
        background-color: #45a049;
    }
.disabled {
    opacity: 0.5;
    pointer-events: none; 
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
                    <li class="py-6 px-8 sidebar-item transition duration-300 ">
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
                    <li class="py-6 px-8 sidebar-item transition duration-300 active">
                        <a href="#" class="flex items-center">
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

<!-- Main container using grid -->
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl pb-10 font-bold">Add Security Options for More Privacy</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
       
<div class="bg-white rounded-md shadow-md p-6 hover:shadow-lg transition <?php echo $verified ? 'disabled' : ''; ?>">
    <h2 class="text-xl font-semibold mb-4"><i class="fas fa-shield-alt mr-2"></i> Two-Factor Authentication</h2>
    <p class="text-gray-700 mb-4">Enable two-factor authentication to add an extra layer of security to your account.</p>
    <?php if (!$verified): ?>
    <button id="enable2FA" class="bg-blue-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-blue-600 transition">Enable Two-Factor Authentication</button>
    <?php endif; ?>
</div>
        <div class="bg-white rounded-md shadow-md p-6 hover:shadow-lg transition">
            <h2 class="text-xl font-semibold mb-4"><i class="fas fa-envelope mr-2"></i> Email Notifications</h2>
            <p class="text-gray-700 mb-4">Receive email notifications for important account activities and security alerts.</p>
            <button  id="enableEmail" class="bg-blue-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-blue-600 transition">Enable Email Notifications</button>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="modal2FA" class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-50 overflow-y-auto z-50">
    <div class="modal-content bg-white w-96 mx-auto mt-24 p-8 rounded-md shadow-lg">
        <span class="close-modal absolute top-0 right-0 m-4 text-gray-600 cursor-pointer">&times;</span>
        <h2 class="text-xl font-semibold mb-4"><i class="fas fa-shield-alt mr-2"></i> Two-Factor Authentication</h2>
        <p class="text-gray-700 mb-4">Enter your email and verify the code to enable two-factor authentication.</p>
        <form id="verificationForm" action="store-otp.php" method="post">
            <input type="email" name="email" id="emailInput" placeholder="Email" class="block w-full rounded-md border-gray-300 shadow-sm mb-4">
            <button type="submit" name="verify"id="verifyButton" class="bg-blue-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-blue-600 transition">Verify</button>
        </form>
    </div>
</div>

<script>
    var modal = document.getElementById('modal2FA');
        var btn = document.getElementById('enable2FA');

    var span = document.getElementsByClassName('close-modal')[0];

    btn.onclick = function() {
        modal.style.display = 'block';

    }
    span.onclick = function() {
        modal.style.display = 'none';

    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

    <!-- Modal with OTP -->
<div id="otpModal" class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-50 overflow-y-auto z-50">
    <div class="modal-content bg-white w-96 mx-auto mt-24 p-8 rounded-md shadow-lg">
        <span class="close-modal2 absolute top-0 right-0 m-4 text-gray-600 cursor-pointer">&times;</span>
        <h2 class="text-xl font-semibold mb-4"><i class="fas fa-shield-alt mr-2"></i> Two-Factor Authentication</h2>
        <p class="text-gray-700 mb-4">Enter the OTP to verify.</p>
        <form id="otpForm" action="verify.php" method="post">
            <input type="text" name="entered_otp" id="otpInput" placeholder="Enter OTP" class="block w-full rounded-md border-gray-300 shadow-sm mb-4">
            <button type="submit" id="verifyOTPButton" class="bg-blue-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-blue-600 transition">Verify</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
   const urlParams = new URLSearchParams(window.location.search);
    const otp = urlParams.get('otp');

    if(otp == 'true'){
      var otpModal = document.getElementById('otpModal');
        otpModal.style.display = "block";
        // Get the close button
        var closeButton = document.querySelector('.close-modal2');
        closeButton.onclick = function() {
            otpModal.classList.add('hidden');
        }
        window.onclick = function(event) {
            if (event.target == otpModal) {
                otpModal.classList.add('hidden');
            }
        }
    }
</script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('verify');
        
        if (success === 'true') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Your email is verified your 2-F authentication is enabled',
                timer: 3000, 
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        }else if (success === 'false') {
           
            Swal.fire({
                icon: 'warning',
                title: 'warning!',
                text: 'Your otp is incorrect. Try Again in few minutes',
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        }
    </script>    
    <!-- Modal -->
<div id="modalEmail" class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-50 overflow-y-auto z-50">
    <div class="modal-content bg-white w-96 mx-auto mt-24 p-8 rounded-md shadow-lg">
        <span class="close-modal absolute top-0 right-0 m-4 text-gray-600 cursor-pointer">&times;</span>
        <h2 class="text-xl font-semibold mb-4"><i class="fas fa-shield-alt mr-2"></i> Enable Email Notifications</h2>
        <p class="text-gray-700 mb-4">Enter your email so we can send you email notifications</p>
        <form id="verificationForm" action="" method="post">
            <input type="email" name="email" id="emailInput" placeholder="Email" class="block w-full rounded-md border-gray-300 shadow-sm mb-4">
            <button type="submit" id="emailnotification" class="bg-blue-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-blue-600 transition">Verify</button>

        </form>
    </div>
</div>
<script>
    var modalEmail = document.getElementById('modalEmail');
    var btn = document.getElementById('enableEmail');
    var span = document.getElementsByClassName('close-modal')[0];
    btn.onclick = function() {
        modalEmail.style.display = 'block';

    }
    span.onclick = function() {
        modalEmail.style.display = 'none';

    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modalEmail.style.display = 'none';

        }
    }
</script>
</body>
</html>