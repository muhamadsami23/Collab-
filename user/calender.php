<?php
session_start();

include '../assets/db.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['meeting_date'], $_POST['meeting_name'])) {
    try {
        $meeting_date = intval($_POST['meeting_date']); // Convert to integer
        $meeting_name = htmlspecialchars($_POST['meeting_name']);
        $user_email = $_SESSION['user_email'];

        $stmt = $conn->prepare("INSERT INTO meeting_schedule (user_email, meeting_date, meeting_name) VALUES (?, ?, ?)");
        
        $stmt->bind_param("sis", $user_email, $meeting_date, $meeting_name); // Changed "sss" to "iss"
        
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

try {
    $user_email = $_SESSION['user_email'];
    $stmt = $conn->prepare("SELECT meeting_date, meeting_name FROM meeting_schedule WHERE user_email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $meetings = $result->fetch_all(MYSQLI_ASSOC);
} catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
}

function getMeetingName($date, $meetings) {
    foreach ($meetings as $meeting) {
        if ($meeting['meeting_date'] == $date) {
            return $meeting['meeting_name'];
        }
    }
    return "";
}

function isMeetingDate($date, $meeting_dates) {
    foreach ($meeting_dates as $meeting_date) {
        if ($meeting_date['meeting_date'] == $date) {
            return true;
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
    <link rel="stylesheet" href="user.css">
</head>
<body class="bg-gray-100">
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
            <a href="#" class="text-xl text-gray-300 hover:text-gray-100 transition"><i class="fas fa-cog"></i></a>
        </div>
    </div>
</nav>

<div id="notificationPanel" class="hidden fixed top-16 right-4 bg-white p-4 rounded-md shadow-md z-10">
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
            <li class="py-6 px-8 sidebar-item transition duration-300 active">
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

    <!-- Modal -->
<div id="meetingModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Enter Meeting Details</h2>
    <p>Date: <span id="modalDate"></span></p>
    <form action="calender.php" method="post">
      <input type="hidden" id="meetingDateInput" name="meeting_date">
      <label for="meetingNameInput">Meeting Name:</label>
      <input type="text" id="meetingNameInput" name="meeting_name">
      <button type="submit">Submit</button>
    </form>
  </div>
</div>


    <div class="col-span-4 p-8">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <button id="prevYear" class="text-xl mr-2">&lt;</button>
                <h2 id="currentYear" class="text-xl font-bold">2024</h2>
                <button id="nextYear" class="text-xl ml-2">&gt;</button>
            </div>
            <div class="flex items-center">
                <button id="prevMonth" class="text-xl mr-2">&lt;</button>
                <h2 id="currentMonth" class="text-xl font-bold">April</h2>
                <button id="nextMonth" class="text-xl ml-2">&gt;</button>
            </div>
        </div>

        <div class="day-bar flex">
            <div class="day">Sun</div>
            <div class="day">Mon</div>
            <div class="day">Tue</div>
            <div class="day">Wed</div>
            <div class="day">Thu</div>
            <div class="day">Fri</div>
            <div class="day">Sat</div>
        </div>

        <div class="calendar grid grid-cols-7 gap-2">
        <?php
            for ($i = 1; $i <= 30; $i++) {
                $date = sprintf("%02d", $i); 
                $meeting_name = getMeetingName($date, $meetings); 
                $highlight = ""; 

                foreach ($meetings as $meeting) {
                    if ($meeting['meeting_date'] == $date) {
                        $highlight = "meeting"; 
                        $meeting_name = $meeting['meeting_name'];
                        break; 
                    }
                }

                echo "<div class='$highlight'>" . $date;
                if (!empty($meeting_name)) {
                    echo "<br><span>$meeting_name</span>";
                }
                echo "</div>";
            }
        ?>
     <?php
            for ($i = 1; $i <= 30; $i++) {
                $date = sprintf("%02d", $i); 
                $is_meeting_date = isMeetingDate($date, $meeting_dates);
                $highlight_class = $is_meeting_date ? "meeting" : ""; 
                echo "<div class='day $highlight_class'>" . $date . "</div>";
            }
        ?>    
    </div>
    </div>
</div>

<script>
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    var currentMonth = currentDate.getMonth();
    var today = currentDate.getDate();

    var monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    function updateDisplay() {
        document.getElementById("currentYear").textContent = currentYear;
        document.getElementById("currentMonth").textContent = monthNames[currentMonth];
    }

    function updateCalendar() {
        document.querySelector(".calendar").innerHTML = "";

        var firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
        var daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        var calendarContent = "";
        for (var i = 0; i < firstDayOfMonth; i++) {
            calendarContent += "<div></div>";
        }
        for (var i = 1; i <= daysInMonth; i++) {
            if (i === today && currentMonth === currentDate.getMonth() && currentYear === currentDate.getFullYear()) {
                calendarContent += "<div class='today'>" + i + "</div>";
            } else {
                calendarContent += "<div>" + i + "</div>";
            }
        }
        document.querySelector(".calendar").innerHTML = calendarContent;
    }

    updateDisplay();
    updateCalendar();

    document.getElementById("prevYear").addEventListener("click", function() {
        currentYear--;
        updateDisplay();
        updateCalendar();
    });
    document.getElementById("nextYear").addEventListener("click", function() {
        currentYear++;
        updateDisplay();
        updateCalendar();
    });
    document.getElementById("prevMonth").addEventListener("click", function() {
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        updateDisplay();
        updateCalendar();
    });
    document.getElementById("nextMonth").addEventListener("click", function() {
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        updateDisplay();
        updateCalendar();
    });

    function handleDateClick(event) {
        var selectedDate = event.target.textContent; 
        var meetingName = prompt("Enter meeting name for " + selectedDate); 
        if (meetingName !== null) { 
            openModal(selectedDate, meetingName);
        }
    }

    function openModal(date, defaultMeetingName) {
        var modal = document.getElementById("meetingModal");
        modal.style.display = "block";
        document.getElementById("modalDate").textContent = date;
        document.getElementById("meetingDateInput").value = date; 
        document.getElementById("meetingNameInput").value = defaultMeetingName || "";
    }

    function closeModal() {
        var modal = document.getElementById("meetingModal");
        modal.style.display = "none";
    }

    document.querySelectorAll('.calendar div').forEach(function(date) {
        date.addEventListener('click', handleDateClick);
    });

</script>

</body>
</html>
