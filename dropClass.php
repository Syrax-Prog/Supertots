<?php
include "conn.php";
if (isset($_GET['id'])) {
    // Sanitize and store the parameters
    $id = $_GET['id'];

    // Perform database operations to remove student from activity
    $query = "DELETE FROM class WHERE classid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        $mes = "Class removed successfully from the activity";
        header("Location: manageClass.php?message=$mes");
    }
}
