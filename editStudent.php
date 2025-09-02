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

        <?php }

        ?>
        <div class="general" style="margin-left: 250px;">
            <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333;">Edit Student's Details</h1>
            <div class="flex-box">
                <div class="sided2" style="padding-left: 20px;">
                    <div class="sided">

                        <?php
                        if (isset($_GET['id'])) {
                            $child_id = $_GET['id'];
                        }

                        $query = "SELECT * FROM child WHERE childid = '$child_id'";
                        $result = mysqli_query($conn, $query);

                        if ($result->num_rows > 0) {
                            $row = mysqli_fetch_assoc($result);

                            $child_id = $row['childid'];
                            $child_name = $row['name'];
                            $gender = $row['gender'];
                            $class = $row['classid'];
                            $dob = $row['dob'];

                            $dobObj = new DateTime($dob);
                            $now = new DateTime();
                            $age = $now->diff($dobObj);
                            $ageYears = $age->y;

                            $imgSrc = "img/student/" . $row['images'];
                        }

                        $query = "SELECT * FROM class WHERE classid = '$class'";
                        $result = mysqli_query($conn, $query);

                        if ($result->num_rows > 0) {
                            $row = mysqli_fetch_assoc($result);

                            $classname = $row['classname'];
                        }

                        ?>
                        <form action="editStudentProcess.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">
                            <div class="change-img" style="margin-left: 110px;">
                                <img class="pic-edit-AD" src="<?php echo $imgSrc; ?>" alt="">
                                <label for="photo" class="file-upload">Upload Photo
                                    <input type="file" name="NewImage" accept="image/*">
                                </label>
                            </div>

                            <div>Student ID: <input type="text" value="<?php echo $child_id; ?>" name="childid" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                            <div>Student Name: <input type="text" value="<?php echo $child_name; ?>" name="name" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                            <div>Student Class: <br><select name="classid" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 300px;">
                                    <option value="M" <?php if ($class == 'M') echo 'selected'; ?>>Mutiara</option>
                                    <option value="Z" <?php if ($class == 'Z') echo 'selected'; ?>>Zamrud</option>
                                    <option value="N" <?php if ($class == 'N') echo 'selected'; ?>>Nilam</option>
                                </select></div>
                            <div>Student Age: <input type="text" value="<?php echo $ageYears; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                            <div>Student Gender: <br><select name="gender" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 300px; ">
                                    <option value="M" <?php if ($gender == 'M') echo 'selected'; ?>>Male</option>
                                    <option value="F" <?php if ($gender == 'F') echo 'selected'; ?>>Female</option>
                                </select></div>
                            <input type="submit" value="Update" name="submit" style="width: 300px;">
                        </form>
                    </div>
                    <div class="sided" style="padding-left: 20px;">
                        <h2 style="padding-left: 20px; padding-bottom: 50px;">Class Details</h2>
                        <form action="" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">
                            <div>Class ID: <input type="text" value="<?php echo $class; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                            <div>Class Name: <input type="text" value="<?php echo $classname; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                        </form>
                        <br>
                        <h3>Total Student: </h3><br>

                        <?php
                        $sql = "SELECT COUNT(*) AS total_count FROM child WHERE classid = '$class'";
                        $result = mysqli_query($conn, $sql);

                        if ($result->num_rows > 0) {
                            $row = mysqli_fetch_assoc($result);

                            $total = $row['total_count'];
                        }
                        ?>

                        <h4><?php echo $total; ?></h4>
                    </div>
                </div>

                <div class="sided2" style="padding-left: 20px;">
                    <div class="GGG">
                        <a id="openPopup1" href="#"><button>Add Guardian</button></a>
                    </div>

                    <div id="popup1" class="popup1">
                        <div class="popup-content1" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <span class="close" onclick="closePopup1()" style="cursor: pointer; margin-left: 400px; scale: 2;">&times;</span>
                            <h2>Select Guardian</h2>
                            <form action="editStudentProcess.php" method="POST" style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding-top: 30px; padding-bottom: 20px;">
                                Parent ID:
                                <select name="parentid" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 300px; ">
                                    <?php
                                    $sql = "SELECT * FROM parent";
                                    $result = mysqli_query($conn, $sql);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $id = $row["parentid"];
                                        $name = $row["parent_name"]; ?>
                                        <option value="<?php echo $id; ?>"><?php echo $id . " - " . $name; ?></option>
                                    <?php } ?>
                                </select>
                                <input type="text" name="childid" value="<?php echo $child_id; ?>" style="display: none;">
                                <input type="submit" name="addG" value="Add">
                            </form>
                        </div>
                    </div>
                    <?php

                    $query = " SELECT p.* FROM parent_child pc
                            JOIN child c ON pc.childid = c.childid
                            JOIN parent p ON pc.parentid = p.parentid
                            WHERE c.childid = '$child_id'";

                    $result = mysqli_query($conn, $query);

                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pid = $row['parentid'];
                        $pName = $row['parent_name'];


                    ?>
                        <div class="sided" style="padding-left: 20px;">
                            <h2 style="padding-left: 20px; padding-bottom: 50px;">Guardian <?php echo $i; ?> Details</h2>

                            <form action="" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">
                                <div>Parent Name: <input type="text" value="<?php echo $pName; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                                <div>Parent ID: <input type="text" value="<?php echo $pid; ?>" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; background-color: #f4f4f4;"></div>
                            </form>
                            <div class="GGG">
                                <a href="editParents.php?id=<?php echo $pid; ?>"><button>View Details</button></a>
                                <a href="editStudentProcess.php?pid=<?php echo $pid; ?>&cid=<?php echo $child_id; ?>"><button>Remove</button></a>
                            </div>
                        </div>
                    <?php
                        $i = $i + 1;
                    } ?>
                </div>
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