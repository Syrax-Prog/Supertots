<?php
include 'conn.php'; // Include your database connection file

if (isset($_GET['id'])) {
    $activityID = $_GET['id'];

    // Prepare the delete statement
    $query = "DELETE FROM activity WHERE activityID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $activityID);

    if ($stmt->execute()) {

        $query = "DELETE FROM activity_child WHERE activityID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $activityID);

        if ($stmt->execute()) {

            $query = "DELETE FROM activity_teacher WHERE activityID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $activityID);

            if ($stmt->execute()) {
                header("Location: manageActivity.php?message=Activity successfully deleted.");
            }
        }
    }
}
