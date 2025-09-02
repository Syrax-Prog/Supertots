<?php

include 'conn.php';

if (isset($_POST['submit'])) {
    $student = $_POST['student'] ?? null;
    $activity = $_POST['activity'] ?? null;
    $payment = $_POST['payment'] ?? null;

    if ($student && $activity && $payment) {
        // Prepare and execute the first query
        $stmt = $conn->prepare("SELECT totalParticipant FROM activity WHERE activityid = ?");
        $stmt->bind_param("i", $activity);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            $total = $row['totalParticipant'];
        } else {
            // Handle error if activity not found
            $mes = "Activity not found";
            header("Location: teacherActivity.php?message=$mes");
            exit();
        }
        $stmt->close();

        // Prepare and execute the second query
        $stmt = $conn->prepare("SELECT COUNT(activityid) AS activity_count FROM activity_child WHERE activityid = ?");
        $stmt->bind_param("i", $activity);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            $count = $row['activity_count'];
        } else {
            // Handle error if count fails
            $mes = "Failed to retrieve activity count";
            header("Location: teacherActivity.php?message=$mes");
            exit();
        }
        $stmt->close();

        // Prepare and execute the third query
        $stmt = $conn->prepare("SELECT * FROM activity_child WHERE activityid = ? AND childid = ?");
        $stmt->bind_param("ss", $activity, $student);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $mes = "Student Already assigned to the current activity";
            header("Location: teacherActivity.php?message=$mes");
            exit();
        }
        $stmt->close();

        // Check if the count is less than the total
        if ($count >= $total) {
            $mes = "Maximum number of participants reached";
            header("Location: teacherActivity.php?message=$mes");
            exit();
        }

        // Prepare and execute the insert query
        $stmt = $conn->prepare("INSERT INTO activity_child (childid, activityid, paymentStatus) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $student, $activity, $payment);
        if ($stmt->execute()) {
            $mes = "Student assigned successfully to the activity";
        } else {
            $mes = "Failed to assign student to the activity";
        }
        $stmt->close();

        header("Location: teacherActivity.php?message=$mes");
    } else {
        $mes = "Missing student, activity, or payment information";
        header("Location: teacherActivity.php?message=$mes");
    }
}

?>
