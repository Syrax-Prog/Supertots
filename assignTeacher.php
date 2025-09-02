<?php
// Ensure the form was submitted properly
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize inputs
    $teacherID = isset($_POST['cikgu']) ? $_POST['cikgu'] : '';
    $activityID = isset($_POST['activity']) ? $_POST['activity'] : '';

    // Perform further validation if needed

    // Connect to your database
    include "conn.php"; // Adjust this based on your actual connection file

    // Check if the assignment already exists
    $checkQuery = "SELECT * FROM activity_teacher WHERE staffid = '$teacherID' AND activityid = '$activityID'";
    $resultCheck = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($resultCheck) > 0) {
        // Assignment already exists
        $mess = "Assignment already exists!";
        header("Location: manageActivity.php?message=$mess");
        exit; // Stop further execution
    } else {
        // Insert the assignment into the database
        $insertQuery = "INSERT INTO activity_teacher (staffid, activityid) VALUES ('$teacherID', '$activityID')";

        if (mysqli_query($conn, $insertQuery)) {
            $mess = "Assignment successful!";
            header("Location: manageActivity.php?message=$mess");
            exit; // Stop further execution
        } else {
            echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
