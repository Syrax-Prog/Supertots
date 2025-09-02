<?php
// Include database connection
include "conn.php";

// Check if childid is provided in the URL
if (isset($_GET['id']) && !isset($_GET['r'])) {
    $child_id = $_GET['id'];

    // SQL query to delete student from child table
    $delete_query = "DELETE FROM child WHERE childid = '$child_id'";

    // Execute the delete query
    if (mysqli_query($conn, $delete_query)) {
        // Redirect back to the page where the deletion was initiated
        header("Location: manageStudent.php?message=Student Successfully Removed");
        exit;
    } else {
        // Handle error if delete query fails
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else if (isset($_GET['r'])) {
    $id = $_GET['id'];
    $role = $_GET['r'];

    if ($role == 'T') {
        $delete_query = "DELETE FROM staff WHERE staffid = '$id'";
        if (mysqli_query($conn, $delete_query)) {
            // Redirect back to the page where the deletion was initiated
            header("Location: manageTeacher.php?message=Teacher Successfully Removed");
            exit;
        }
    } else if ($role == 'P') {
        $delete_query = "DELETE FROM parent WHERE parentid = '$id'";
        if (mysqli_query($conn, $delete_query)) {
            // Redirect back to the page where the deletion was initiated
            header("Location: manageParents.php?message=Parent Successfully Removed");
            exit;
        }
    } else if ($role == 'E') {
        // Delete associated records in the progress table
        $delete_progress_query = "DELETE FROM progress WHERE examID = '$id'";
        mysqli_query($conn, $delete_progress_query);

        // Delete the exam
        $delete_query = "DELETE FROM exam WHERE examID = '$id'";
        if (mysqli_query($conn, $delete_query)) {
            // Redirect back to the page where the deletion was initiated
            header("Location: manageExam.php?message=Exam Successfully Removed");
            exit;
        }
    }
}
