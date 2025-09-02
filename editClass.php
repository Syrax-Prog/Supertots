<!DOCTYPE html>
<html>

<head>
    <title>Homepage admin</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Popup container */
        .popup1 {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Popup content */
        .popup-content1 {
            background-color: #fefefe;
            margin: 15% auto;
            margin-left: 45%;
            /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            /* Could be more or less, depending on screen size */
            box-shadow: 0 0 10px rgba(0, 0, 0, 1);

        }

        /* Close button */
        .close1 {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close1:hover,
        .close1:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
            margin-left: 1260px;
            /* Adjust as per your layout */
            margin-top: 130px;
            /* Adjust as per your layout */
        }

        /* Style for the timer bar container */
        .timer-bar-container {
            position: relative;
            width: 100%;
            height: 10px;
            background-color: #f0c36d;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }

        /* Style for the timer bar */
        .timer-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #4caf50;
            animation: countdown 5s linear;
        }

        /* Keyframes for the timer bar animation */
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
        include "navigation/sideAD.php";
        include "conn.php";


        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        ?>

            <div id="popup5s" class="popup5s">
                <div class="popup-content">
                    <?php echo $message; ?>
                    <div class="timer-bar-container">
                        <div class="timer-bar"></div>
                    </div>
                </div>
            </div>

        <?php } ?>

        <div class="general" style="margin-left: 300px; ">
            <h1>Edit Class Details</h1>

            <div class="flex-box" style="margin-left: 700px;">
                <div class="sided">
                    <?php

                    $id = $_GET['id'];

                    $query = "SELECT * FROM class WHERE classid = '$id'";
                    $result = mysqli_query($conn, $query);

                    if ($result->num_rows > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $name = $row['classname'];
                    }
                    ?>

                    <form action="editTeacherProcess.php" method="POST" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">
                        <div>Class ID: <input type="text" value="<?php echo $id; ?>" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                        <div>Class Name: <input type="text" value="<?php echo $name; ?>" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                        <input type="submit" value="Update" name="submit">
                    </form>
                </div>
            </div>

            <br><br><br>
            <h1>Teachers Assigned</h1>
            <div class="GGG">
                <a id="openPopup1" href="#"><button>Assign Teacher</button></a>
            </div>
            <div id="popup1" class="popup1">
                <div class="popup-content1" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <span class="close" onclick="closePopup1()" style="cursor: pointer; margin-left: 400px; scale: 2;">&times;</span>
                    <h2>Select Teacher</h2>
                    <form action="editTeacherProcess.php" method="POST" style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding-top: 30px; padding-bottom: 20px;">
                        Teacher ID:
                        <select name="classid" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 300px; ">
                            <?php
                            $sql = "SELECT * FROM staff WHERE role = 'Teacher'";
                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $staffID = $row["staffid"];
                                $name = $row["name"]; ?>
                                <option value="<?php echo $staffID; ?>"><?php echo $staffID . " - " . $name; ?></option>
                            <?php } ?>
                        </select>
                        <input type="text" name="classid" value="<?php echo $id; ?>" style="display: none;">
                        <input type="submit" name="addCT" value="Add">
                    </form>
                </div>
            </div>

            <?php
            $query = "SELECT * FROM class_teacher WHERE classid = '$id'";
            $result_class_teacher = mysqli_query($conn, $query);
            $count = 1;
            while ($row_class_teacher = mysqli_fetch_assoc($result_class_teacher)) {
                $tid = $row_class_teacher['staffid'];

                $query_class = "SELECT * FROM staff WHERE staffid = '$tid'";
                $result_class = mysqli_query($conn, $query_class);

                if ($result_class->num_rows > 0) {
                    $row_class = mysqli_fetch_assoc($result_class);

                    $nameT = $row_class['name'];
                }
            ?>
                <?php
                if ($count == 1) { ?>
                    <div class="flex-box" style="justify-content: center; align-items: center; margin-right: 300px;"> <?php
                                                                                                                    }
                                                                                                                        ?>
                    <div class="sided" style="width: 30%;">
                        <form action="editTeacherProcess.php" method="POST" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">
                            <div>Teacher ID: <input type="text" value="<?php echo $tid; ?>" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                            <div>Teacher Name: <input type="text" value="<?php echo $nameT; ?>" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                        </form>
                    </div>


                    <?php
                    if ($count == 3) { ?>
                    </div> <?php $count = 0;
                        }
                            ?>
            <?php $count++;
            } ?>
        </div>


        <div class="flex-box" style="margin-left: 370px;">
            <div style="width: 800px; padding: 20px; background-color: #f7f7f7; border-radius: 8px; border: apx solid black;">
                <br>
                <h1 style="margin-left: 270px;">Students In This Class</h1><br><br>
                <?php
                $query = "SELECT * FROM child WHERE classid = '$id'";
                $result_parent = mysqli_query($conn, $query);

                while ($row_parent = mysqli_fetch_assoc($result_parent)) {
                    $childID = $row_parent['childid'];
                    $childName = $row_parent['name']; ?>
                    <div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 15px; border-radius: 8px; background-color: #fff;">
                        <div style="font-size: 18px; font-weight: bold;"><?php echo $childName; ?></div>
                        <div style="margin-top: 5px; font-size: 14px; color: #666;"><?php echo "ID: " . $childID; ?></div>
                        <div style="margin-top: 10px; display: flex; justify-content: flex-end;">
                            <a href="editStudent.php?id=<?php echo $childID; ?>" style="text-decoration: none;"><button style="background-color: #007bff; color: #fff; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">View Details</button></a>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // Get the popup
        var popup1 = document.getElementById("popup1");

        // Get the button that opens the popup
        var btn = document.getElementById("openPopup1");

        // When the user clicks the button, open the popup
        btn.onclick = function() {
            popup1.style.display = "block";
        }

        // When the user clicks on <span> (x), close the popup
        function closePopup1() {
            popup1.style.display = "none";
        }

        // Close the popup when the user clicks anywhere outside of the popup
        window.onclick = function(event) {
            if (event.target == popup1) {
                popup1.style.display = "none";
            }
        }
    </script>
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