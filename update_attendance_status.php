<?php
include "conn.php";

if (isset($_POST['childattendanceid']) && isset($_POST['status'])) {
    $childattendanceid = $_POST['childattendanceid'];
    $status = $_POST['status'];

    // Update attendance status
    $query = "UPDATE attendance 
              JOIN childattendance ON attendance.attendanceid = childattendance.attendanceid
              SET Astatus = '$status' 
              WHERE childattendance.childattendanceid = '$childattendanceid'";
    if (mysqli_query($conn, $query)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid parameters.";
}
?>
