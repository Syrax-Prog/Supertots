<!DOCTYPE html>
<html>

<head>
    <title>Homepage Teacher</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        .cHome {
            height: auto;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .grid-item {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .grid-item h3 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        table th,
        table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:nth-child(odd) {
            background-color: #fff;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }

        table tr td {
            border: none;
            border-bottom: 1px solid #ddd;
        }

        table th,
        table td {
            text-align: center;
        }

        .highlight {
            background-color: #FFEB3B;
        }

        .accent {
            background-color: #FF7043;
            color: white;
        }

        table a:hover {
            color: red;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sidebar.php";
        include "conn.php"; ?>

        <div class="grid-container">
            <div class="grid-item accent">
                <h3>Total Students</h3>
                <h3><?php
                    if (isset($_SESSION['name'])) {
                        $query = "SELECT COUNT(*) AS total FROM child";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $total = $row['total'];
                            echo "$total";
                        }
                    }
                    ?></h3>
            </div>
            <div class="grid-item highlight">
                <h3>Total Teacher</h3>
                <h3><?php
                    $query = "SELECT COUNT(*) AS total FROM staff WHERE role = 'Teacher'";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $total = $row['total'];
                        echo "$total";
                    }
                    ?></h3>
            </div>
            <div class="grid-item accent">
                <h3>Total Admin</h3>
                <h3><?php
                    $query = "SELECT COUNT(*) AS total FROM staff WHERE role = 'Admin'";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $total = $row['total'];
                        echo "$total";
                    }
                    ?></h3>
            </div>
            <div class="grid-item highlight">
                <h3>
                    <div id="clock"></div>
                </h3>
                <script>
                    function updateClock() {
                        var now = new Date();
                        var hours = now.getHours();
                        var minutes = now.getMinutes();
                        var seconds = now.getSeconds();
                        var day = now.getDate();
                        var month = now.getMonth() + 1; // Months are zero-indexed
                        var year = now.getFullYear();
                        var ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12;
                        minutes = minutes < 10 ? '0' + minutes : minutes;
                        seconds = seconds < 10 ? '0' + seconds : seconds;
                        var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                        var dateString = day + '/' + month + '/' + year;
                        document.getElementById('clock').innerHTML = timeString + '<br>' + dateString;
                    }

                    setInterval(updateClock, 1000); // Update the clock every second
                    updateClock(); // Run the function immediately to prevent delay
                </script>
            </div>
        </div>

        <div style="margin-top:50px; margin-left:400px;">
            <h3>Classes Assigned</h3>
            <table>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                </tr>
                <?php
                if (isset($_SESSION['name'])) {
                    $staffid = $_SESSION['name'];
                    $query1 = "select * from class_teacher where staffid = '$staffid'";
                    $result1 = mysqli_query($conn, $query1);
                    if ($result1) {
                        while ($row1 = mysqli_fetch_assoc($result1)) {
                            $esain = $row1['classid'];

                            $query2 = "select * from class where classid = '$esain'";
                            $result2 = mysqli_query($conn, $query2);
                            if ($result2) {
                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                    $idClass = $row2['classid'];
                                    $nameClass = $row2['classname']; ?>

                                    <tr>
                                        <td>
                                            <?php echo $idClass; ?>
                                        </td>
                                        <td>
                                            <?php echo $nameClass; ?>
                                        </td>
                                    </tr>

                <?php  }
                            }
                        }
                    }
                }
                ?>
            </table>
        </div>

        <div style="margin-top:50px; margin-left:400px;">
            <h3>Students in Assigned Classes</h3>
            <table>
                <tr>
                    <th>Child ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Class ID</th>
                    <th>Date of Birth</th>
                    <th>Image</th>
                </tr>
                <?php
                if (isset($_SESSION['name'])) {
                    $staffid = $_SESSION['name'];
                    $query = "SELECT child.childid, child.name, child.gender, child.classid, child.dob, child.images 
                              FROM child 
                              JOIN class_teacher ON child.classid = class_teacher.classid 
                              WHERE class_teacher.staffid = '$staffid'";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['childid'] . "</td>"; ?>

                            <td>
                                <a href="details.php?id=<?php echo $row['childid']; ?>"><?php echo $row['name']; ?></a>
                            </td>
                <?php
                            echo "<td>" . $row['gender'] . "</td>";
                            echo "<td>" . $row['classid'] . "</td>";
                            echo "<td>" . $row['dob'] . "</td>";
                            echo "<td><img src='img/student/" . $row['images'] . "' alt='" . $row['name'] . "'></td>";
                            echo "</tr>";
                        }
                    }
                }
                ?>
            </table>
        </div>

        <div style="margin-top:50px; margin-left:400px;">
            <h3>Assigned Activities</h3>
            <table>
                <tr>
                    <th>Activity ID</th>
                    <th>Activity Name</th>
                    <th>Venue</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>Total Participants</th>
                </tr>
                <?php
                if (isset($_SESSION['name'])) {
                    $staffid = $_SESSION['name'];
                    $today = date('Y-m-d');
                    $query = "SELECT activity.activityid, activity.activityname, activity.venue, activity.descA, activity.startdate, activity.totalParticipant 
                              FROM activity 
                              JOIN activity_teacher ON activity.activityid = activity_teacher.activityid 
                              WHERE activity_teacher.staffid = '$staffid' 
                              AND activity.startdate <= '$today' AND activity.enddate >= '$today'";
                    $result = mysqli_query($conn, $query);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['activityid'] . "</td>";
                            echo "<td>" . $row['activityname'] . "</td>";
                            echo "<td>" . $row['venue'] . "</td>";
                            echo "<td>" . $row['descA'] . "</td>";
                            echo "<td>" . $row['startdate'] . "</td>";
                            echo "<td>" . $row['totalParticipant'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Normal in class lesson for today</td></tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>

</body>

</html>
