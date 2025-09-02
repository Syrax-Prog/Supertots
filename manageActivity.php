<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Admin</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        .general h1,
        h2 {
            color: #4CAF50;
        }

        button {
            margin: 20px;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        #contentPanel {
            margin: 20px;
            padding: 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            display: none;
            transition: max-height 0.5s ease-out, opacity 0.5s ease-out;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
        }

        #contentPanel.show {
            display: block;
            max-height: 200px;
            opacity: 1;
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
            left: 50%;
            transform: translateX(-50%);
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

        table {
            width: 90%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }

        table th,
        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        a {
            text-decoration: none;
            color: #4CAF50;
        }

        a:hover {
            color: #45a049;
        }

        select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin: 5px 0;
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php
        include "conn.php";
        include "navigation/navbar.php";
        include "navigation/sideAD.php";
        include "conn.php";

        $currentDate = date('Y-m-d');

        // Query for ongoing activities
        $queryOngoing = "SELECT * FROM activity WHERE startdate <= '$currentDate' AND enddate >= '$currentDate'";
        $resultOngoing = mysqli_query($conn, $queryOngoing);

        // Query for upcoming activities
        $queryUpcoming = "SELECT * FROM activity WHERE startdate > '$currentDate'";
        $resultUpcoming = mysqli_query($conn, $queryUpcoming);

        // Query for passed activities
        $queryPassed = "SELECT * FROM activity WHERE enddate < '$currentDate'";
        $resultPassed = mysqli_query($conn, $queryPassed);
        ?>

        <div class="general" style="margin-left: 270px;">
            <h1>Manage Activity</h1>
            <?php
            if (isset($_GET['message'])) {
                $message = $_GET['message'];
            ?>

                <div id="popup5s" class="popup5s" style="margin-left: 500px; margin-top: 100px;">
                    <div class="popup-content">
                        <?php echo $message; ?>
                        <div class="timer-bar-container">
                            <div class="timer-bar"></div>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <div class="GGG" style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
                    <a href="addNewActivity.php"><button>Add Upcoming Activity</button></a>
                    <button id="toggleButton">Assign Teacher</button>
                </div>
                <div id="contentPanel" class="hidden" style="text-align: center; margin-top: 20px;">
                    <p>Assign Teacher</p><br>
                    <form action="assignTeacher.php" method="POST">
                        <select name="cikgu">
                            <option value="">-- Select Teacher --</option>
                            <?php
                            $qCikgu = "SELECT * FROM staff";
                            $resultCikgu = mysqli_query($conn, $qCikgu);
                            while ($rCikgu = mysqli_fetch_assoc($resultCikgu)) {
                                $cikguName = $rCikgu['name'];
                                $cikguID = $rCikgu['staffid'];
                            ?>
                                <option value="<?php echo $cikguID; ?>">
                                    <?php echo $cikguID . " | " . $cikguName; ?>
                                </option>
                            <?php } ?>
                        </select>

                        <select name="activity">
                            <option value="">-- Select Activity --</option>
                            <?php
                            $qAct = "SELECT * FROM activity";
                            $resultAct = mysqli_query($conn, $qAct);
                            while ($rAct = mysqli_fetch_assoc($resultAct)) {
                                $actName = $rAct['activityName'];
                                $actID = $rAct['activityID'];
                            ?>
                                <option value="<?php echo $actID; ?>">
                                    <?php echo $actID . " | " . $actName; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <br><br>
                        <input type="submit" value="Assign">
                    </form>
                </div>
            </div>

            <h2>Ongoing Activities</h2>
            <table>
                <thead>
                    <tr>
                        <th>Activity Name</th>
                        <th>Venue</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>Payment</th>
                        <th>Total Participants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($resultOngoing)) {
                        $name = $row['activityName'];
                        $id = $row['activityID'];
                        $venue = $row['venue'];
                        $descA = $row['descA'];
                        $startDate = $row['startdate'];
                        $payment = $row['payment'];
                        $totalParticipant = $row['totalParticipant'];

                        $queryParticipant = "SELECT COUNT(*) as count FROM activity_child WHERE activityid = '$id'";
                        $resultParticipant = mysqli_query($conn, $queryParticipant);
                        $total = 0;
                        if ($resultParticipant->num_rows > 0) {
                            $rowP = mysqli_fetch_assoc($resultParticipant);
                            $total = $rowP['count'];
                        }
                    ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $venue; ?></td>
                            <td><?php echo $descA; ?></td>
                            <td><?php echo $startDate; ?></td>
                            <td><?php echo $payment; ?></td>
                            <td><?php echo $totalParticipant; ?></td>
                            <td><a href="deleteActivity.php?id=<?php echo $id; ?>" style="color:red;">Delete</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h2>Upcoming Activities</h2>
            <table>
                <thead>
                    <tr>
                        <th>Activity Name</th>
                        <th>Venue</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>Payment</th>
                        <th>Total Participants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($resultUpcoming)) {
                        $name = $row['activityName'];
                        $id = $row['activityID'];
                        $venue = $row['venue'];
                        $descA = $row['descA'];
                        $startDate = $row['startdate'];
                        $payment = $row['payment'];
                        $totalParticipant = $row['totalParticipant'];

                        $queryParticipant = "SELECT COUNT(*) as count FROM activity_child WHERE activityid = '$id'";
                        $resultParticipant = mysqli_query($conn, $queryParticipant);
                        $total = 0;
                        if ($resultParticipant->num_rows > 0) {
                            $rowP = mysqli_fetch_assoc($resultParticipant);
                            $total = $rowP['count'];
                        }
                    ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $venue; ?></td>
                            <td><?php echo $descA; ?></td>
                            <td><?php echo $startDate; ?></td>
                            <td><?php echo $payment; ?></td>
                            <td><?php echo $totalParticipant; ?></td>
                            <td><a href="deleteActivity.php?id=<?php echo $id; ?>" style="color:red;">Delete</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h2>Passed Activities</h2>
            <table>
                <thead>
                    <tr>
                        <th>Activity Name</th>
                        <th>Venue</th>
                        <th>Description</th>
                        <th>End Date</th>
                        <th>Payment</th>
                        <th>Total Participants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($resultPassed)) {
                        $name = $row['activityName'];
                        $id = $row['activityID'];
                        $venue = $row['venue'];
                        $descA = $row['descA'];
                        $endDate = $row['enddate'];
                        $payment = $row['payment'];
                        $totalParticipant = $row['totalParticipant'];

                        $queryParticipant = "SELECT COUNT(*) as count FROM activity_child WHERE activityid = '$id'";
                        $resultParticipant = mysqli_query($conn, $queryParticipant);
                        $total = 0;
                        if ($resultParticipant->num_rows > 0) {
                            $rowP = mysqli_fetch_assoc($resultParticipant);
                            $total = $rowP['count'];
                        }
                    ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $venue; ?></td>
                            <td><?php echo $descA; ?></td>
                            <td><?php echo $endDate; ?></td>
                            <td><?php echo $payment; ?></td>
                            <td><?php echo $totalParticipant; ?></td>
                            <td><a href="deleteActivity.php?id=<?php echo $id; ?>" style="color:red;">Delete</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const popup = document.getElementById('popup5s');
            if (popup) {
                popup.style.display = 'block';
                setTimeout(() => {
                    popup.style.display = 'none';
                }, 5000);
            }
        });

        document.getElementById('toggleButton').addEventListener('click', function() {
            const contentPanel = document.getElementById('contentPanel');
            contentPanel.classList.toggle('show');
        });
    </script>
</body>

</html>