<?php
// Include your database connection
include 'db_connection.php';

if(isset($_POST['activityID'])) {
    $activityID = mysqli_real_escape_string($conn, $_POST['activityID']);
    
    $query = "SELECT c.childid, c.name FROM child c 
              INNER JOIN activity_child ac ON c.childid = ac.childid
              WHERE ac.activityid = $activityID";

    $result = mysqli_query($conn, $query);

    $output = '<option value="">Select a student</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<option value="' . $row['childid'] . '">' . $row['childid'] . ' - ' . $row['name'] . '</option>';
    }

    echo $output;
}
?>
