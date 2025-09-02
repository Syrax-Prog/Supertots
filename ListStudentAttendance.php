<!DOCTYPE html>
<html>

<head>
    <title>Attendance Management</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table {
            width: 1000px;
        }

        th,
        td {
            text-align: center;
            padding: 8px;
        }

        .inline-form {
            display: inline;
        }

        .btn-container {
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            text-decoration: none;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
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
        include "navigation/sidebar.php";
        include "conn.php"; ?>

        <?php
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

        <div class="attendance">
            <?php
            if (isset($_GET['id'])) {
                $classid = $_GET['id'];
            }

            // Handle "All Present" button click
            if (isset($_GET['button']) && $_GET['button'] == "Yes") {
                $today = date("Y-m-d");
                $query = "SELECT childid FROM child WHERE classid='$classid'";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                    echo "Error: " . mysqli_error($conn);
                    exit;
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    $child_id = $row['childid'];

                    // Check if an attendance record already exists for today
                    $check_query = "SELECT a.attendanceid FROM childattendance ca 
                                    INNER JOIN attendance a ON ca.attendanceid = a.attendanceid 
                                    WHERE ca.childid='$child_id' AND a.Adate='$today'";
                    $check_result = mysqli_query($conn, $check_query);

                    if (!$check_result) {
                        echo "Error: " . mysqli_error($conn);
                        exit;
                    }

                    if (mysqli_num_rows($check_result) == 0) {
                        // Insert new attendance record if not exists
                        $sql = "INSERT INTO attendance (Adate, Astatus) VALUES ('$today', 'Present')";
                        if (mysqli_query($conn, $sql)) {
                            $attendance_id = mysqli_insert_id($conn);
                            $sql = "INSERT INTO childattendance (childid, attendanceid) VALUES ('$child_id', '$attendance_id')";
                            if (!mysqli_query($conn, $sql)) {
                                echo "Error: " . mysqli_error($conn);
                                exit;
                            }
                        } else {
                            echo "Error: " . mysqli_error($conn);
                            exit;
                        }
                    } else {
                        // Update existing attendance record to "Present"
                        $attendance_row = mysqli_fetch_assoc($check_result);
                        $attendance_id = $attendance_row['attendanceid'];
                        $update_query = "UPDATE attendance SET Astatus='Present' WHERE attendanceid='$attendance_id'";
                        if (!mysqli_query($conn, $update_query)) {
                            echo "Error: " . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            }
            ?>

            <h1>Student List</h1>

            <div class="btn-container">
                <a href="ListStudentAttendance.php?id=<?php echo $classid ?>&button=Yes&message=Attendance Marked" class="btn">All Present</a>
                <a href="attHistory.php" class="btn">View History</a>
            </div>
            <table>
                <tr>
                    <th>Student Name</th>
                    <th>Attendance Status</th>
                    <th>Action</th>
                </tr>
                <?php
                // Update Record If Null Yesterday
                $today = date("Y-m-d");
                $update_query = "UPDATE attendance SET Astatus='Absent' WHERE Astatus IS NULL AND Adate < '$today'";
                if (!mysqli_query($conn, $update_query)) {
                    echo "Error: " . mysqli_error($conn);
                    exit;
                }
                // End Update Record

                if (isset($_POST['status'])) {
                    $id = $_POST['idChild'];
                    $status = $_POST['status'];
                    $update_query = "UPDATE attendance SET Astatus='$status' WHERE attendanceid IN (SELECT attendanceid FROM childattendance WHERE childid='$id' AND Adate='$today')";
                    if (!mysqli_query($conn, $update_query)) {
                        echo "Error: " . mysqli_error($conn);
                        exit;
                    }
                }

                if (isset($_GET['id'])) {
                    $classid = $_GET['id'];
                    $today = date("Y-m-d");
                    $query = "SELECT * FROM child WHERE classid ='$classid'";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        echo "Error: " . mysqli_error($conn);
                        exit;
                    }

                    while ($row = mysqli_fetch_assoc($result)) {
                        $child_id = $row['childid'];
                        $child_name = $row['name'];

                        $query = "SELECT * FROM childattendance ca 
                                  INNER JOIN attendance a ON ca.attendanceid = a.attendanceid 
                                  WHERE ca.childid = '$child_id' AND a.Adate = '$today'";
                        $result_child_attendance = mysqli_query($conn, $query);

                        if (!$result_child_attendance) {
                            echo "Error: " . mysqli_error($conn);
                            exit;
                        }

                        if (mysqli_num_rows($result_child_attendance) == 0) {
                            $sql = "INSERT INTO attendance (Adate) VALUES ('$today')";
                            if (mysqli_query($conn, $sql)) {
                                $result1 = mysqli_query($conn, "SELECT * FROM attendance ORDER BY attendanceid DESC LIMIT 1");
                                if (!$result1) {
                                    echo "Error: " . mysqli_error($conn);
                                    exit;
                                }
                                $row1 = mysqli_fetch_assoc($result1);
                                $attendance_id = $row1['attendanceid'];

                                $sql = "INSERT INTO childattendance (childid, attendanceid) VALUES ('$child_id', '$attendance_id')";
                                if (mysqli_query($conn, $sql)) {
                ?>
                                    <tr>
                                        <td><?php echo $child_name; ?></td>
                                        <td>Not marked</td>
                                        <td>
                                            <form method="POST" action="ListStudentAttendance.php?id=<?php echo $classid ?>" class="inline-form">
                                                <input type="hidden" value="<?php echo $child_id; ?>" name="idChild">
                                                <select name="status" onchange="this.form.submit()">
                                                    <option value="">Select</option>
                                                    <option value="Present">Present</option>
                                                    <option value="Absent">Absent</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                } else {
                                    echo "Error: " . mysqli_error($conn);
                                    exit;
                                }
                            } else {
                                echo "Error: " . mysqli_error($conn);
                                exit;
                            }
                        } else {
                            $attendance_row = mysqli_fetch_assoc($result_child_attendance);
                            $attendance_status = $attendance_row['Astatus'];
                            ?>
                            <tr>
                                <td><?php echo $child_name; ?></td>
                                <td><?php
                                    if ($attendance_status == '') {
                                        echo "Not Marked";
                                    } else {
                                        echo $attendance_status;
                                    }
                                    ?></td>
                                <td>
                                    <form method="POST" action="ListStudentAttendance.php?id=<?php echo $classid ?>&message=Changes Saved" class="inline-form">
                                        <input type="hidden" value="<?php echo $child_id; ?>" name="idChild">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="">Select</option>
                                            <option value="Present" <?php if ($attendance_status == 'Present') echo 'selected'; ?>>Present</option>
                                            <option value="Absent" <?php if ($attendance_status == 'Absent') echo 'selected'; ?>>Absent</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                <?php
                        }
                    }
                }
                ?>
            </table><br><br><br>
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