<?php
include "conn.php"; // Include the database connection file

// Check if both sID and aID parameters are set in the URL
if (isset($_GET['act'])) {
    // Sanitize and store the parameters
    $studentID = $_GET['sID'];
    $activityID = $_GET['aID'];

    // Perform database operations to remove student from activity
    $query = "DELETE FROM activity_child WHERE childid = (
                  SELECT childid FROM child WHERE childid = ?
              ) AND activityid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $studentID, $activityID);

    if ($stmt->execute()) {
        $mes = "Student removed successfully from the activity";
        header("Location: activityDetail.php?message=$mes&activityID=$activityID");
    }
}

if (isset($_GET['pay']) && isset($_GET['sID']) && isset($_GET['aID'])) {
    // Sanitize and store the parameters
    $studentID = $_GET['sID'];
    $activityID = $_GET['aID'];
    $status = $_GET['pay'];

    if ($status == "pay") {
        // Perform database operations to update paymentStatus
        $query = "UPDATE activity_child SET paymentStatus = 'Payed' WHERE childid = ? AND activityid = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $studentID, $activityID);

        if ($stmt->execute()) {
            $mes = "Payment status updated successfully for the student.";
            header("Location: activityDetail.php?message=$mes&activityID=$activityID");
        }
    } else {
        // Perform database operations to update paymentStatus
        $query = "UPDATE activity_child SET paymentStatus = 'Not Payed' WHERE childid = ? AND activityid = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $studentID, $activityID);

        if ($stmt->execute()) {
            $mes = "Payment status updated successfully for the student.";
            header("Location: activityDetail.php?message=$mes&activityID=$activityID");
        }
    }
}
