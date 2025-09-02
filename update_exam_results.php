<?php
include "conn.php";

if (isset($_POST['examid']) && !empty($_POST['marks'])) {
    $examid = $_POST['examid'];
    $marks = $_POST['marks'];

    foreach ($marks as $childid => $mark) {
        // Check if a record already exists
        $query = "SELECT * FROM progress WHERE childid = '$childid' AND examID = '$examid'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Update existing record
            $query = "UPDATE progress SET examMarks = '$mark' WHERE childid = '$childid' AND examID = '$examid'";
        } else {
            // Insert new record
            $query = "INSERT INTO progress (childid, examID, examMarks) VALUES ('$childid', '$examid', '$mark')";
        }

        mysqli_query($conn, $query);
        $mes = "Update Success";
    }

    header("Location: apTeacher.php?message=$mes");
}
?>
