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
            <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333;">Edit Parent's Details</h1>

            <?php
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            $query = "SELECT * FROM parent WHERE parentid = '$id'";
            $result = mysqli_query($conn, $query);

            if ($result->num_rows > 0) {
                $row = mysqli_fetch_assoc($result);

                $id = $row['parentid'];
                $name = $row['parent_name'];
                $num1 = $row['emergencycontact'];
                $num2 = $row['workphone'];
                $imgSrc = "img/parent/" . $row['images'];
            }
            ?>
            <div class="flex-box">
                <div class="sided" style="margin-left: 200px;">
                    <form action="editParentProcess.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; max-width: 300px; margin: 0 auto; gap: 20px;">

                        <div class="change-img" style="margin-left: 90px;">
                            <img class="pic-edit-AD" src="<?php echo $imgSrc; ?>" alt="">
                            <label for="photo" class="file-upload">Upload Photo
                                <input type="file" name="NewImage" accept="image/*">
                            </label>
                        </div>

                        <div>Parent ID: <input type="text" value="<?php echo $id; ?>" name="id" readonly style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                        <div>Parent Name: <input type="text" value="<?php echo $name; ?>" name="name" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                        <div>Emergency Contact: <input type="text" value="<?php echo $num1; ?>" name="num1" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                        <div>Workphone Number: <input type="text" value="<?php echo $num2; ?>" name="num2" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;"></div>
                        <input type="submit" value="Update" name="submit">
                    </form>
                </div>
            </div>

            <div class="GGG" style="margin-top: 20px; display: flex; flex-direction: column; justify-content:center; align-items: center;">
                <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333; text-shadow: -1px -1px 0 #fff,  1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
                    List Student Under <?php echo $name; ?></h2>
                <a id="openPopup1" href="#" style="margin-top: 20px;"><button>Add Student</button></a>
            </div>

            <div id="popup1" class="popup1">
                <div class="popup-content1" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <span class="close" onclick="closePopup1()" style="cursor: pointer; margin-left: 400px; scale: 2;">&times;</span>
                    <h2>Select Student</h2>
                    <form action="editParentProcess.php" method="POST" style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding-top: 30px; padding-bottom: 20px;">
                        Student ID:
                        <select name="childid" style="margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 300px; ">
                            <?php
                            $sql = "SELECT * FROM child";
                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $idc = $row["childid"];
                                $name = $row["name"]; ?>
                                <option value="<?php echo $idc; ?>"><?php echo $idc . " - " . $name; ?></option>
                            <?php } ?>
                        </select>
                        <input type="text" name="parentid" value="<?php echo $id; ?>" style="display: none;">
                        <input type="submit" name="addCP" value="Add">
                    </form>
                </div>
            </div>

            <div class="flex-box">
                <div style="width: 800px; margin-left: 70px; padding: 20px; background-color: #f7f7f7; border-radius: 8px; border: apx solid black;">
                    <?php
                    $query = "SELECT * FROM parent_child WHERE parentid = '$id'";
                    $result_parent = mysqli_query($conn, $query);

                    while ($row_parent = mysqli_fetch_assoc($result_parent)) {
                        $childID = $row_parent['childid'];

                        $query_child = "SELECT * FROM child WHERE childid = '$childID'";
                        $result_child = mysqli_query($conn, $query_child);

                        if ($result_child->num_rows > 0) {
                            $row_child = mysqli_fetch_assoc($result_child);
                            $childName = $row_child['name'];
                    ?>
                            <div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 15px; border-radius: 8px; background-color: #fff;">
                                <div style="font-size: 18px; font-weight: bold;"><?php echo $childName; ?></div>
                                <div style="margin-top: 5px; font-size: 14px; color: #666;"><?php echo "ID: " . $childID; ?></div>
                                <div style="margin-top: 10px; display: flex; justify-content: flex-end;">
                                    <a href="editStudent.php?id=<?php echo $childID; ?>" style="text-decoration: none;"><button style="background-color: #007bff; color: #fff; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">View Details</button></a>
                                    <a href="editParentProcess.php?pid=<?php echo $id; ?>&cid=<?php echo $childID; ?>" style="text-decoration: none; margin-left: 10px;"><button style="background-color: #dc3545; color: #fff; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Remove</button></a>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
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