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
            <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333;">Edit Teacher's Details</h1>

            <?php

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            $query = "SELECT * FROM staff WHERE staffid = '$id'";
            $result = mysqli_query($conn, $query);

            if ($result->num_rows > 0) {
                $row = mysqli_fetch_assoc($result);

                $id = $row['staffid'];
                $name = $row['name'];
                $q = $row['qualification'];
                $imgSrc = "img/staff/" . $row['images'];
            }
            ?>
            <div class="sided">
                <form action="editTeacherProcess.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">

                    <div class="change-img" style="margin-left: 110px;">
                        <img class="pic-edit-AD" src="<?php echo $imgSrc; ?>" alt="">
                        <label for="photo" class="file-upload">Upload Photo
                            <input type="file" name="NewImage" accept="image/*">
                        </label>
                    </div>

                    <div>Staff ID (Teacher): <input type="text" value="<?php echo $id; ?>" name="i" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                    <div>Staff Name: <input type="text" value="<?php echo $name; ?>" name="n" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                    <div>Qualification: <input type="text" value="<?php echo $q; ?>" name="q" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                    <input type="submit" value="Update" name="submit">
                </form>
            </div><br>

            <div class="GGG">
                <a id="openPopup1" href="#"><button>Assign New Class</button></a>
            </div>

            <div class="flex-box" style="margin-right: 90px;">
                <?php
                $query = "SELECT * FROM class_teacher WHERE staffid = '$id'";
                $result_class_teacher = mysqli_query($conn, $query);

                $count = 1;
                while ($row_class_teacher = mysqli_fetch_assoc($result_class_teacher)) {
                    $classid = $row_class_teacher['classid'];

                    $query_class = "SELECT * FROM class WHERE classid = '$classid'";
                    $result_class = mysqli_query($conn, $query_class);

                    if ($result_class->num_rows > 0) {
                        $row_class = mysqli_fetch_assoc($result_class);

                        $classname = $row_class['classname'];
                    } ?>

                    <div class="sided" style="border: 1px solid black; padding-left: 20px; width: 300px;">
                        <h2 style="padding-left: 20px; padding-bottom: 50px;">Class Details</h2>
                        <form action="" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">
                            <div>Class ID: <br><input type="text" value="<?php echo $classid; ?>" readonly style="width: 200px; margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                            <div>Class Name: <br><input type="text" value="<?php echo $classname; ?>" readonly style="width: 200px; margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                        </form>
                        <br>
                        <h3>Total Student: </h3><br>

                        <?php
                        $sql_total_students = "SELECT COUNT(*) AS total_count FROM child WHERE classid = '$classid'";
                        $result_total_students = mysqli_query($conn, $sql_total_students);

                        if ($result_total_students->num_rows > 0) {
                            $row_total_students = mysqli_fetch_assoc($result_total_students);

                            $total = $row_total_students['total_count'];
                        }
                        ?>

                        <h4><?php echo $total; ?></h4>
                        <div class="GGG" style="margin-top: 20px;">
                            <a href="manageStudent.php?id=<?php echo $classid ?>"><button>View Student</button></a>
                            <a href="editTeacherProcess.php?tid=<?php echo $id; ?>&cid=<?php echo $classid; ?>"><button>Remove Assigned Class</button></a>
                        </div>
                    </div>
                <?php
                } ?>
            </div>
        </div>
    </div>

    <div id="popup1" class="popup1">
        <div class="popup-content1" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <span class="close" onclick="closePopup1()" style="cursor: pointer; margin-left: 400px; scale: 2;">&times;</span>
            <h2>Select Class</h2>
            <form action="editTeacherProcess.php" method="POST" style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding-top: 30px; padding-bottom: 20px;">
                Class ID:
                <select name="classid" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 300px; ">
                    <?php
                    $sql = "SELECT * FROM class";
                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $idc = $row["classid"];
                        $name = $row["classname"]; ?>
                        <option value="<?php echo $idc; ?>"><?php echo $idc . " - " . $name; ?></option>
                    <?php } ?>
                </select>
                <input type="text" name="staffid" value="<?php echo $id; ?>" style="display: none;">
                <input type="submit" name="addAC" value="Add">
            </form>
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