<?php
include "conn.php";
session_start();

// Fetch parent data
$parentid = $_SESSION['id']; // Replace with dynamic ID based on logged-in user
$sql = "SELECT * FROM parent WHERE parentid = '$parentid'";
$result = mysqli_query($conn, $sql);
$parent = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .general {
            margin-left: 250px;
            margin-top: 0px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .general h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: bold;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        .profile-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .profile-container::before,
        .profile-container::after {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, rgba(0, 123, 255, 0.15), rgba(255, 255, 255, 0.15));
            top: -50%;
            left: -50%;
            transform: rotate(30deg);
            z-index: -1;
            animation: float 6s infinite;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(30deg);
            }

            50% {
                transform: translate(10px, 10px) rotate(30deg);
            }

            100% {
                transform: translate(0, 0) rotate(30deg);
            }
        }

        .profile-table {
            width: 100%;
            border-collapse: collapse;
            animation: fadeIn 1s ease-in-out;
            margin-bottom: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-table th,
        .profile-table td {
            padding: 15px;
            text-align: left;
            vertical-align: top;
        }

        .profile-table th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
            border-bottom: 2px solid #ccc;
        }

        .profile-table td {
            background-color: #ffffff;
            border-bottom: 1px solid #ccc;
        }

        .profile-table input[type="text"],
        .profile-table input[type="password"],
        .profile-table input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-table input[type="text"]:focus,
        .profile-table input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .profile-table button {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        .profile-table button:hover {
            background: #0056b3;
        }

        .profile-table img {
            display: block;
            margin: 20px auto;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            transition: transform 0.3s ease;
            border: 2px solid #ccc;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .profile-table img:hover {
            transform: scale(1.05);
        }

        .profile-table td input[type="file"] {
            padding: 3px;
        }

        .popup5s {
            display: none;
            position: fixed;
            background-color: #f9edbe;
            color: #222;
            padding: 15px;
            border: 1px solid #f0c36d;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            top: 20px;
            right: 20px;
        }

        .timer-bar-container {
            position: relative;
            width: 100%;
            height: 10px;
            background-color: #f0c36d;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }

        .timer-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #4caf50;
            animation: countdown 5s linear;
        }

        @keyframes countdown {
            from {
                width: 100%;
            }

            to {
                width: 0;
            }
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sideParent.php";

        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            echo "<div id='popup5s' class='popup5s'>
                    <div class='popup-content'>
                        {$message}
                        <div class='timer-bar-container'>
                            <div class='timer-bar'></div>
                        </div>
                    </div>
                </div>";
        }
        ?>
        <div class="general">
            <h1>Parent Profile</h1>
            <div class="profile-container">
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="parentid" value="<?php echo $parent['parentid']; ?>">

                    <table class="profile-table">
                        <tr>
                            <?php if ($parent['images']) : ?>
                                <img src="img/parent/<?php echo $parent['images']; ?>" alt="Profile Image" style="height:150px; width:150px; margin-left:275px;">
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <th><label for="parent_name">Name:</label></th>
                            <td><input type="text" id="parent_name" name="parent_name" value="<?php echo $parent['parent_name']; ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="emergencycontact">Emergency Contact:</label></th>
                            <td><input type="text" id="emergencycontact" name="emergencycontact" value="<?php echo $parent['emergencycontact']; ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="workphone">Work Phone:</label></th>
                            <td><input type="text" id="workphone" name="workphone" value="<?php echo $parent['workphone']; ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="password">Password:</label></th>
                            <td><input type="password" id="password" name="password" value="<?php echo $parent['password']; ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="images">Profile Image:</label></th>
                            <td><input type="file" id="images" name="images" accept="image/*"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <button type="submit">Update Profile</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showPopup5s() {
            var popup5s = document.getElementById('popup5s');
            popup5s.style.display = 'block'; // Show the popup

            // Automatically hide the popup after 5 seconds
            setTimeout(function() {
                popup5s.style.display = 'none';
            }, 5000); // 5000 milliseconds = 5 seconds
        }

        // Show the popup when the page loads
        window.onload = showPopup5s;
    </script>
</body>

</html>