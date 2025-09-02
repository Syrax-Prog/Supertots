<?php
session_start();
include "conn.php";

// Check if the activity ID is set in the query parameter
if (isset($_GET['activityid']) && isset($_SESSION['id'])) {
    $activityID = $_GET['activityid'];
    $parentid = $_SESSION['id'];

    // Fetch the activity details
    $sql_activity = "SELECT a.activityName, a.venue, a.startdate, a.enddate, a.descA, ac.paymentStatus
                     FROM activity a
                     INNER JOIN activity_child ac ON a.activityID = ac.activityid
                     WHERE a.activityID = '$activityID' AND ac.childid IN (
                         SELECT pc.childid FROM parent_child pc WHERE pc.parentid = '$parentid'
                     )";
    $result_activity = mysqli_query($conn, $sql_activity);

    if (mysqli_num_rows($result_activity) > 0) {
        $activity = mysqli_fetch_assoc($result_activity);

        // Handle the payment process
        if (isset($_POST['pay'])) {
            $sql_update = "UPDATE activity_child SET paymentStatus='Paid' WHERE activityid='$activityID'";
            if (mysqli_query($conn, $sql_update)) {
                $message = "Payment successful!";
                $activity['paymentStatus'] = 'Paid';
            } else {
                $message = "Payment failed. Please try again.";
            }
        }
    } else {
        $message = "Invalid activity ID or you do not have permission to access this activity.";
    }
} else {
    $message = "No activity ID provided.";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay for Activity</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .content {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .details-table th {
            background-color: #f4f4f4;
        }

        .btn {
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .btn-return {
            background-color: #007BFF;
            color: white;
        }

        .btn-return:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
            font-size: 18px;
            color: #d9534f;
        }
    </style>
</head>

<body>

    <div class="cHome">
        <?php include "navigation/navbar.php"; ?>
        <?php include "navigation/sideParent.php"; ?>

        <div class="general" style="margin-left: 325px; margin-right: 30px; margin-top: 0px;">
            <div class="content">
                <?php if (isset($activity)) : ?>
                    <h2>Pay for Activity: <?php echo htmlspecialchars($activity['activityName']); ?></h2>
                    <table class="details-table">
                        <tr>
                            <th>Activity Name</th>
                            <td><?php echo htmlspecialchars($activity['activityName']); ?></td>
                        </tr>
                        <tr>
                            <th>Venue</th>
                            <td><?php echo htmlspecialchars($activity['venue']); ?></td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td><?php echo htmlspecialchars($activity['startdate']); ?></td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td><?php echo htmlspecialchars($activity['enddate']); ?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?php echo nl2br(htmlspecialchars($activity['descA'])); ?></td>
                        </tr>
                        <tr>
                            <th>Payment Status</th>
                            <td><?php echo htmlspecialchars($activity['paymentStatus']); ?></td>
                        </tr>
                    </table>
                    <?php if ($activity['paymentStatus'] !== 'Paid') : ?>
                        <form method="post">
                            <button type="submit" name="pay" class="btn btn-primary">Pay Now</button>
                        </form>
                    <?php else : ?>
                        <p>The activity has already been paid for.</p>
                    <?php endif; ?>
                    <?php if (isset($message)) : ?>
                        <p class="message"><?php echo htmlspecialchars($message); ?></p>
                        <button class="btn btn-return" onclick="window.location.href='viewActivity.php';">Return</button>
                    <?php endif; ?>
                <?php else : ?>
                    <p class="message"><?php echo htmlspecialchars($message); ?></p>
                    <button class="btn btn-return" onclick="window.location.href='viewActivity.php';">Return</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
