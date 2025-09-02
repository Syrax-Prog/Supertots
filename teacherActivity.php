<?php
session_start();
?>

<html>

<head>
    <title>Homepage admin</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .general h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .general table {
            width: 90%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .general th,
        td {
            border: none;
            padding: 12px 15px;
            margin-right: 10px;
            text-align: left;
        }

        .general th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
        }

        .general td {
            font-size: 14px;
            border-bottom: 1px solid #ddd;
        }

        .general tr:hover {
            background-color: #f1f1f1;
        }

        .popup {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: auto;
            height: auto;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            margin-left: 120px;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 300px;
            text-align: center;
        }

        .popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #888;
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
            top: 120px;
            right: 10px;
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

        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            gap: 10px;
        }

        .assign-button,
        .unassign-button {
            background-color: #4caf50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .assign-button {
            background-color: #4caf50;
        }

        .unassign-button {
            background-color: #ff6347;
        }

        .popup h2 {
            margin: 0 0 10px;
        }

        .popup form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .popup input,
        .popup select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 280px;
            box-sizing: border-box;
        }

        .popup input[type="submit"],
        .popup input[type="button"] {
            border: 1px solid;
            cursor: pointer;
        }

        .popup input[type="submit"] {
            background-color: #28a745;
            color: #fff;
        }

        .popup input[type="button"] {
            background-color: #dc3545;
            color: #fff;
        }

        .action-button {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="cHome" style="height: auto;">
        <?php
        include "conn.php";
        include "navigation/navbar.php";
        include "navigation/sidebar.php";
        
        include "conn.php";
        $ongoingQuery = "SELECT * FROM activity WHERE startdate <= CURDATE() AND enddate >= CURDATE()";
        $upcomingQuery = "SELECT * FROM activity WHERE startdate > CURDATE()";
        $passedQuery = "SELECT * FROM activity WHERE enddate < CURDATE()";

        $ongoingResult = mysqli_query($conn, $ongoingQuery);
        $upcomingResult = mysqli_query($conn, $upcomingQuery);
        $passedResult = mysqli_query($conn, $passedQuery);
        ?>

        <div class="general" style="margin-top: 0; margin-left:260px;">
            <h1>Available Activity</h1>

            <?php
            if (isset($_GET['message'])) {
                $message = $_GET['message']; ?>

                <div id="popup5s" class="popup5s">
                    <?php echo $message; ?>
                    <div class="timer-bar-container">
                        <div class="timer-bar"></div>
                    </div>
                </div>

            <?php } ?>

            <div class="button-group">
                <button class="assign-button">Assign Student</button>

                <div id="assignPopup" class="popup">
                    <div class="popup-content">
                        <h2>Select Student To Assign</h2>
                        <?php
                        $a = "select * from child";
                        $b = mysqli_query($conn, $a);

                        $aa = "select * from activity";
                        $bb = mysqli_query($conn, $aa);
                        ?>

                        <form action="activity_student_proses.php" method="POST">
                            <input type="text" id="searchInput" placeholder="Search students...">
                            <select id="studentSelect" name="student" required>
                                <option value="">Select Student</option>
                                <?php
                                while ($c = mysqli_fetch_assoc($b)) {
                                    $ii = $c['childid'];
                                    $nn = $c['name'];
                                ?>
                                    <option value="<?php echo $ii; ?>"><?php echo $ii . " - " . $nn; ?></option>
                                <?php
                                }
                                ?>
                            </select>

                            <select name="activity" required>
                                <option value="">Select Activity</option>
                                <?php
                                while ($cc = mysqli_fetch_assoc($bb)) {
                                    $iii = $cc['activityID'];
                                    $nnn = $cc['activityName'];
                                ?>
                                    <option value="<?php echo $iii; ?>"><?php echo $iii . " - " . $nnn; ?></option>
                                <?php
                                }
                                ?>
                            </select>

                            <select name="payment" required>
                                <option value="">Payment Status</option>
                                <option value="Payed">Payed</option>
                                <option value="Not Payed">Not Payed</option>
                            </select>

                            <input type="submit" value="Add" name="submit">
                            <input type="button" value="Close" id="closeAssignPopup">
                        </form>
                    </div>
                </div>
            </div>

            <h2>Ongoing Activity</h2>
            <table>
                <thead>
                    <tr>
                        <th>Activity Name</th>
                        <th>Activity Date</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Total Payment</th>
                        <th>Total Participants</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($ongoingResult)) {
                        $name = $row['activityName'];
                        $id = $row['activityID'];
                        $venue = $row['venue'];
                        $descA = $row['descA'];
                        $startDate = date('j F Y', strtotime($row['startdate']));
                        $endDate = date('j F Y', strtotime($row['enddate']));
                        $payment = $row['payment'];

                        $queryParticipant = "SELECT COUNT(*) as count FROM activity_child WHERE activityid = '$id'";
                        $resultParticipant = mysqli_query($conn, $queryParticipant);
                        $total = ($resultParticipant->num_rows > 0) ? mysqli_fetch_assoc($resultParticipant)['count'] : 0;
                    ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $startDate; ?><br><strong>To</strong><br><?php echo $endDate; ?></td>
                            <td><?php echo $venue; ?></td>
                            <td><?php echo $descA; ?></td>
                            <td><?php echo "<strong>RM</strong> " . $payment; ?></td>
                            <td><?php echo "<strong>" . $total . "</strong>/" . $row['totalParticipant']; ?></td>
                            <td><a href="activityDetail.php?activityID=<?php echo $id; ?>" class="action-button">View</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h2>Upcoming Activity</h2>
            <table>
                <thead>
                    <tr>
                        <th>Activity Name</th>
                        <th>Activity Date</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Total Payment</th>
                        <th>Total Participants</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($upcomingResult)) {
                        $name = $row['activityName'];
                        $id = $row['activityID'];
                        $venue = $row['venue'];
                        $descA = $row['descA'];
                        $startDate = date('j F Y', strtotime($row['startdate']));
                        $endDate = date('j F Y', strtotime($row['enddate']));
                        $payment = $row['payment'];

                        $queryParticipant = "SELECT COUNT(*) as count FROM activity_child WHERE activityid = '$id'";
                        $resultParticipant = mysqli_query($conn, $queryParticipant);
                        $total = ($resultParticipant->num_rows > 0) ? mysqli_fetch_assoc($resultParticipant)['count'] : 0;
                    ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $startDate; ?><br><strong>To</strong><br><?php echo $endDate; ?></td>
                            <td><?php echo $venue; ?></td>
                            <td><?php echo $descA; ?></td>
                            <td><?php echo "<strong>RM</strong> " . $payment; ?></td>
                            <td><?php echo "<strong>" . $total . "</strong>/" . $row['totalParticipant']; ?></td>
                            <td><a href="activityDetail.php?activityID=<?php echo $id; ?>" class="action-button">View</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h2>Passed Activity</h2>
            <table>
                <thead>
                    <tr>
                        <th>Activity Name</th>
                        <th>Activity Date</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Total Payment</th>
                        <th>Total Participants</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($passedResult)) {
                        $name = $row['activityName'];
                        $id = $row['activityID'];
                        $venue = $row['venue'];
                        $descA = $row['descA'];
                        $startDate = date('j F Y', strtotime($row['startdate']));
                        $endDate = date('j F Y', strtotime($row['enddate']));
                        $payment = $row['payment'];

                        $queryParticipant = "SELECT COUNT(*) as count FROM activity_child WHERE activityid = '$id'";
                        $resultParticipant = mysqli_query($conn, $queryParticipant);
                        $total = ($resultParticipant->num_rows > 0) ? mysqli_fetch_assoc($resultParticipant)['count'] : 0;
                    ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $startDate; ?><br><strong>To</strong><br><?php echo $endDate; ?></td>
                            <td><?php echo $venue; ?></td>
                            <td><?php echo $descA; ?></td>
                            <td><?php echo "<strong>RM</strong> " . $payment; ?></td>
                            <td><?php echo "<strong>" . $total . "</strong>/" . $row['totalParticipant']; ?></td>
                            <td><a href="activityDetail.php?activityID=<?php echo $id; ?>" class="action-button">View</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const assignButton = document.querySelector('.assign-button');
        const assignPopup = document.getElementById('assignPopup');
        const closeAssignPopup = document.getElementById('closeAssignPopup');

        assignButton.addEventListener('click', function(event) {
            event.preventDefault();
            assignPopup.style.display = 'flex';
        });

        closeAssignPopup.addEventListener('click', function() {
            assignPopup.style.display = 'none';
        });

        function filterOptions(inputId, selectId) {
            let input, filter, select, options, option, i, txtValue;
            input = document.getElementById(inputId);
            filter = input.value.toUpperCase();
            select = document.getElementById(selectId);
            options = select.getElementsByTagName('option');

            for (i = 0; i < options.length; i++) {
                option = options[i];
                txtValue = option.textContent || option.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    option.style.display = "";
                } else {
                    option.style.display = "none";
                }
            }
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            filterOptions('searchInput', 'studentSelect');
        });

        function showPopup5s() {
            var popup5s = document.getElementById('popup5s');
            popup5s.style.display = 'block';
            setTimeout(function() {
                popup5s.style.display = 'none';
            }, 5000);
        }

        window.onload = showPopup5s;
    </script>
</body>

</html>
