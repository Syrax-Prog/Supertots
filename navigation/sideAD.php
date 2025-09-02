<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Sidebar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #eef2f7;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #2e3a4e;
            padding: 20px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1000;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            margin-top: 50px;
        }

        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .profile img:hover {
            transform: scale(1.1);
        }

        .user-info p {
            margin: 5px 0;
            font-size: 14px;
            font-weight: 400;
            color: #d1d1d1;
        }

        .side-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 50px;
        }

        .item {
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease-in-out;
            cursor: pointer;
            text-decoration: none;
            color: #ffffff;
            background-color: #3c4a63;
        }

        .item:hover {
            background-color: #506487;
        }

        footer p {
            margin: 0;
            font-weight: bold;
            cursor: pointer;
            color: #ff5a5f;
            margin-top: 120px;
        }

        footer p:hover {
            text-decoration: underline;
        }

        /* Popup styles */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 9999;
            transition: all 0.3s ease-in-out;
        }

        .popup-content {
            text-align: center;
        }

        .popup-content img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #ff5a5f;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            border: 1px solid black;
        }
    </style>
</head>

<body>

    <?php
    $name = $_SESSION['name'];
    $role = $_SESSION['role'];

    include "conn.php";

    if (isset($_SESSION['name'])) {
        $staffid = $_SESSION['name'];
        $images = $_SESSION['images'];
        $sql = "SELECT name FROM staff WHERE staffid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->bind_result($staffName);
        $stmt->fetch();
        $stmt->close();
    }
    $conn->close();
    ?>

    <div class="sidebar">
        <header class="profile">
            <div class="gambar-user">
                <img src="img/staff/<?php echo $images; ?>" alt="Profile Picture">
            </div>
            <div class="user-info">
                <p><?php echo $role; ?></p>
                <p><?php echo $staffName; ?></p>
                <p><?php echo $name; ?></p>
            </div>
        </header>
        <div class="side-content">
            <a href="home_admin.php" class="item">Homepage</a>
            <a href="manageStudent.php" class="item">Student Management</a>
            <a href="manageTeacher.php" class="item">Teacher Management</a>
            <a href="manageParents.php" class="item">Parent Management</a>
            <a href="manageClass.php" class="item">Class Management</a>
            <a href="manageActivity.php" class="item">Activity Management</a>
            <a href="manageExam.php" class="item">Exam Management</a>
        </div>
        <footer>
            <p id="logoutBtn">Logout</p>
        </footer>
    </div>

    <!-- Popup Confirmation -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <img src="img/Alert.png" alt="Logout Image">
            <p>You Will Be Logged Out From <br>
                The Current Session. <br>Are Sure To Continue?</p><br>
            <button id="confirmBtn" class="btn">Yes</button>
            <button id="cancelBtn" class="btn">Cancel</button>
        </div>
    </div>

    <script>
        // Get the logout button
        var logoutBtn = document.getElementById('logoutBtn');

        // Get the popup confirmation
        var popup = document.getElementById('popup');

        // Get the confirm and cancel buttons
        var confirmBtn = document.getElementById('confirmBtn');
        var cancelBtn = document.getElementById('cancelBtn');

        // Show the popup when the logout button is clicked
        logoutBtn.addEventListener('click', function() {
            popup.style.display = 'block';
        });

        // Hide the popup when the cancel button is clicked
        cancelBtn.addEventListener('click', function() {
            popup.style.display = 'none';
        });

        // Redirect to login.php when "Yes" is clicked
        confirmBtn.addEventListener('click', function() {
            // Redirect to login.php
            window.location.href = 'login.php';
        });
    </script>
</body>

</html>
