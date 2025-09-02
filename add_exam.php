<?php
include "conn.php";

// Initialize variables for form processing
$examType = $classid = $examDate = "";
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $examType = mysqli_real_escape_string($conn, $_POST['examType']);
    $classid = mysqli_real_escape_string($conn, $_POST['classid']);
    $examDate = mysqli_real_escape_string($conn, $_POST['examDate']);

    // Insert new exam into the database
    $sql_insert = "INSERT INTO exam (examType, classid, examDate) VALUES ('$examType', '$classid', '$examDate')";
    if (mysqli_query($conn, $sql_insert)) {
        $message = "New exam added successfully!";
        header("Location: manageExam.php?message=" . urlencode($message));
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
