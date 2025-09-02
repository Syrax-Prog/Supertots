<?php
session_start();
include "conn.php";

// Fetch parent's children from the database
if (isset($_SESSION['id'])) {
    $parentid = $_SESSION['id'];

    // Query to fetch children associated with the parent
    $sql_children = "SELECT c.childid, c.name FROM child c
                     INNER JOIN parent_child pc ON c.childid = pc.childid
                     WHERE pc.parentid = '$parentid'";
    $result_children = mysqli_query($conn, $sql_children);

    // Fetch activities associated with each child, including all teachers
    $children_activities = [];
    while ($child = mysqli_fetch_assoc($result_children)) {
        $childid = $child['childid'];
        $child_name = $child['name'];

        // Query to fetch activities for each child, including all teachers and payment status
        $sql_activities = "SELECT a.activityID, a.activityName, a.venue, a.descA, a.startdate, a.enddate, a.images, 
                                  GROUP_CONCAT(s.name SEPARATOR ', ') AS staffnames, ac.paymentStatus
                           FROM activity a
                           INNER JOIN activity_child ac ON a.activityID = ac.activityid
                           LEFT JOIN activity_teacher at ON a.activityID = at.activityid
                           LEFT JOIN staff s ON at.staffid = s.staffid
                           WHERE ac.childid = '$childid'
                           GROUP BY a.activityID";
        $result_activities = mysqli_query($conn, $sql_activities);

        // Store activities for the child
        $children_activities[$child_name] = [];
        while ($activity = mysqli_fetch_assoc($result_activities)) {
            $activity['staffnames'] = ($activity['staffnames']) ? $activity['staffnames'] : "No teacher assigned";
            $children_activities[$child_name][] = $activity;
        }
    }
}
mysqli_close($conn);

// Function to sort activities by start date
function sort_activities_by_startdate($a, $b) {
    return strtotime($a['startdate']) - strtotime($b['startdate']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent View Activities</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .general {
            margin-left: 325px;
            margin-right: 30px;
            margin-top: 0px;
            font-family: Arial, sans-serif;
        }

        .general h2 {
            font-size: 28px;
            color: #4CAF50;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        .general h3 {
            font-size: 24px;
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .general table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }

        .general th, .general td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        .general th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        .general tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .general tr:hover {
            background-color: #f1f1f1;
        }

        .general .activity img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
        }

        .general .filter-form {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .general .filter-form .child-button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            transition: background-color 0.3s;
        }

        .general .filter-form .child-button:hover {
            background-color: #45a049;
        }

        .general .passed-table {
            margin-top: 30px;
        }

        .general .payment-link a {
            color: #007bff;
            text-decoration: none;
        }

        .general .payment-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="cHome">
        <?php include "navigation/navbar.php"; ?>
        <?php include "navigation/sideParent.php"; ?>
        <div class="general">
            <h2>Activities for Your Children</h2>
            <form action="#" method="post" class="filter-form">
                <div>
                    <?php foreach ($children_activities as $child_name => $activities) : ?>
                        <button type="submit" name="child_filter" value="<?php echo htmlspecialchars($child_name); ?>" class="child-button">
                            <?php echo htmlspecialchars($child_name); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </form>

            <?php
            $today = date('Y-m-d');
            $months = ["01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", 
                       "07" => "July", "08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December"];

            // Display activities in table format based on selected child
            if (isset($_POST['child_filter'])) {
                $selected_child = $_POST['child_filter'];
                if (array_key_exists($selected_child, $children_activities)) {
                    echo "<h3>Activities for " . htmlspecialchars($selected_child) . "</h3>";

                    $ongoing_activities = array_filter($children_activities[$selected_child], function($activity) use ($today) {
                        return $activity['startdate'] <= $today && $activity['enddate'] >= $today;
                    });

                    $upcoming_activities = array_filter($children_activities[$selected_child], function($activity) use ($today) {
                        return $activity['startdate'] > $today;
                    });

                    $passed_activities = array_filter($children_activities[$selected_child], function($activity) use ($today) {
                        return $activity['enddate'] < $today;
                    });

                    // Sort activities by start date
                    usort($ongoing_activities, 'sort_activities_by_startdate');
                    usort($upcoming_activities, 'sort_activities_by_startdate');
                    usort($passed_activities, 'sort_activities_by_startdate');

                    $activity_sections = [
                        "Ongoing Activities" => $ongoing_activities,
                        "Upcoming Activities" => $upcoming_activities,
                        "Passed Activities" => $passed_activities
                    ];

                    foreach ($activity_sections as $section_title => $activities) {
                        if (!empty($activities)) {
                            echo "<h3>$section_title</h3>";
                            echo "<table>";
                            echo "<tr>
                                      <th>Activity Name</th>
                                      <th>Venue</th>
                                      <th>Start Date</th>
                                      <th>End Date</th>
                                      <th>Teachers in Charge</th>
                                      <th>Payment Status</th>
                                  </tr>";
                            foreach ($activities as $activity) {
                                $payment_status = '';
                                switch ($activity['paymentStatus']) {
                                    case 'Paid':
                                        $payment_status = 'Paid';
                                        break;
                                    case 'Not Paid':
                                        $payment_status = "<a href='pay.php?activityid=" . $activity['activityID'] . "'>Not Paid</a>";
                                        break;
                                    case 'Free':
                                        $payment_status = 'Free';
                                        break;
                                }
                                $start_month = $months[date("m", strtotime($activity['startdate']))];
                                $end_month = $months[date("m", strtotime($activity['enddate']))];
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($activity['activityName']) . "</td>";
                                echo "<td>" . htmlspecialchars($activity['venue']) . "</td>";
                                echo "<td>" . htmlspecialchars($start_month . " " . date("d, Y", strtotime($activity['startdate']))) . "</td>";
                                echo "<td>" . htmlspecialchars($end_month . " " . date("d, Y", strtotime($activity['enddate']))) . "</td>";
                                echo "<td>" . htmlspecialchars($activity['staffnames']) . "</td>";
                                echo "<td class='payment-link'>" . $payment_status . "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        }
                    }
                } else {
                    echo "<p>No activities found for the selected child.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>
