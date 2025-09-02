<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Details</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        .general {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            padding: 20px;
            margin-top: 20px;
            width: 100%;
            margin-top: 150px;
            margin-left: 400px;
        }

        .general h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        .activity-table,
        .participants-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .activity-table th,
        .activity-table td,
        .participants-table th,
        .participants-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .activity-table th,
        .participants-table th {
            background-color: #f9f9f9;
        }

        .activity-table tr:hover,
        .participants-table tr:hover {
            background-color: #f1f1f1;
        }

        .participants img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
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
            transform: translateX(-50%);
            top: 120px;
            right: -130px;
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

        /* Scoped styles for payment status images */
        .payment-status img {
            width: 32px;
            height: 32px;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php
        include "navigation/navbar.php";
        include "navigation/sidebar.php";
        include "conn.php"; // Include the database connection file once

        // Check if activityID is set in the URL
        if (isset($_GET['activityID'])) {
            $activityID = $_GET['activityID'];

            // Fetch activity details from the database
            $query_activity = "SELECT * FROM activity WHERE activityID = ?";
            $stmt_activity = $conn->prepare($query_activity);
            $stmt_activity->bind_param("i", $activityID);
            $stmt_activity->execute();
            $result_activity = $stmt_activity->get_result();

            // Check if activity exists
            if ($result_activity->num_rows > 0) {
                $activity = $result_activity->fetch_assoc();
            } else {
                echo "Activity not found.";
                exit();
            }

            // Fetch children participants for the activity
            $query_children = "SELECT * FROM child c
                       INNER JOIN activity_child ac ON c.childid = ac.childid
                       WHERE ac.activityid = ? ORDER BY classid ASC";
            $stmt_children = $conn->prepare($query_children);
            $stmt_children->bind_param("i", $activityID);
            $stmt_children->execute();
            $result_children = $stmt_children->get_result();

            // Store children participants in an array
            $participants = [];
            while ($row = $result_children->fetch_assoc()) {
                $participants[] = $row;
            }
        } else {
            echo "No activity ID specified.";
            exit();
        }
        ?>

        <div class="general">
            <h1><?php echo htmlspecialchars($activity['activityName']); ?></h1>

            <?php
            if (isset($_GET['message'])) {
                $message = $_GET['message'];
            ?>
                <div id="popup5s" class="popup5s">
                    <div class="popup-content">
                        <?php echo htmlspecialchars($message); ?>
                        <div class="timer-bar-container">
                            <div class="timer-bar"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <table class="activity-table">
                <tr>
                    <th style="width:200px;">Venue</th>
                    <td><?php echo htmlspecialchars($activity['venue']); ?></td>
                </tr>
                <tr>
                    <th style="width:200px;">Description</th>
                    <td><?php echo nl2br(htmlspecialchars($activity['descA'])); ?></td>
                </tr>
                <tr>
                    <th style="width:200px;">Start Date</th>
                    <td><?php echo htmlspecialchars($activity['startdate']); ?></td>
                </tr>
                <tr>
                    <th style="width:200px;">End Date</th>
                    <td><?php echo htmlspecialchars($activity['enddate']); ?></td>
                </tr>
                <tr>
                    <th style="width:200px;">Registration Start Date</th>
                    <td><?php echo htmlspecialchars($activity['startregisterdate']); ?></td>
                </tr>
                <tr>
                    <th style="width:200px;">Registration End Date</th>
                    <td><?php echo htmlspecialchars($activity['endregisterdate']); ?></td>
                </tr>
                <tr>
                    <th style="width:200px;">Payment</th>
                    <td>RM <?php echo htmlspecialchars($activity['payment']); ?></td>
                </tr>
                <tr>
                    <th style="width:200px;">Maximum Participants Allowed</th>
                    <td><?php echo htmlspecialchars($activity['totalParticipant']); ?></td>
                </tr>
                <?php if (!empty($activity['images'])) : ?>
                    <tr>
                        <th>Images</th>
                        <td><img src="uploads/<?php echo htmlspecialchars($activity['images']); ?>" alt="Activity Image"></td>
                    </tr>
                <?php endif; ?>
            </table>

            <div class="participants">
                <h2 style="text-align: center;">Participants</h2><br>
                <table class="participants-table">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Class ID</th>
                        <th>Actions</th>
                        <th>Payment Status</th>
                    </tr>
                    <?php foreach ($participants as $participant) : ?>
                        <tr>
                            <td>
                                <?php if (!empty($participant['images'])) : ?>
                                    <img src="img/student/<?php echo htmlspecialchars($participant['images']); ?>" alt="Participant Image">
                                <?php else : ?>
                                    <div class="participant-placeholder"></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($participant['name']); ?></td>
                            <td><?php echo htmlspecialchars($participant['gender']); ?></td>
                            <td><?php echo htmlspecialchars($participant['classid']); ?></td>
                            <td>
                                <a href="removeStudent.php?sID=<?php echo htmlspecialchars($participant['childid']); ?>&aID=<?php echo $activityID; ?>&act=siu">
                                    <button style="background-color: #f44336; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;">Remove Student</button>
                                </a>
                            </td>
                            <td class="payment-status">
                                <a href="removeStudent.php?sID=<?php echo htmlspecialchars($participant['childid']); ?>&aID=<?php echo $activityID; ?>&pay=no">
                                    <p><?php if ($participant['paymentStatus'] == "Paid") echo '<img src="https://cdn-icons-png.flaticon.com/128/15219/15219921.png" alt="Paid">'; ?></p>
                                </a>
                                <a href="removeStudent.php?sID=<?php echo htmlspecialchars($participant['childid']); ?>&aID=<?php echo $activityID; ?>&pay=pay">
                                    <p><?php if ($participant['paymentStatus'] == "Not Paid") echo '<img src="https://cdn-icons-png.flaticon.com/128/8924/8924271.png" alt="Not Paid">'; ?></p>
                                </a>
                                <a href="removeStudent.php">
                                    <p><?php if ($participant['paymentStatus'] == "Free") echo '<img src="https://cdn-icons-png.flaticon.com/128/3161/3161537.png" alt="Free">'; ?></p>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($participants)) : ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No participants registered yet.</td>
                        </tr>
                    <?php endif; ?>
                </table>
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
