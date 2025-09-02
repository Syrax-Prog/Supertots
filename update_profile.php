<?php
session_start();
include "conn.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $parentid = mysqli_real_escape_string($conn, $_POST['parentid']);
    $parent_name = mysqli_real_escape_string($conn, $_POST['parent_name']);
    $emergencycontact = mysqli_real_escape_string($conn, $_POST['emergencycontact']);
    $workphone = mysqli_real_escape_string($conn, $_POST['workphone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Base SQL for updating parent data
    $sql = "UPDATE parent SET parent_name='$parent_name', emergencycontact='$emergencycontact', workphone='$workphone', password='$password'";

    // Add password to the SQL only if it's not null or empty
    if (empty($password)) {
        header("Location: profile.php?message=Password field cannot be empty");
        die();
    }

    // Handle profile image upload if provided
    if ($_FILES['images']['name']) {
        $target_dir = "img/parent/";
        $target_file = $target_dir . basename($_FILES["images"]["name"]);

        // Attempt to upload file
        if (move_uploaded_file($_FILES["images"]["tmp_name"], $target_file)) {
            // Append image update to the SQL
            $sql .= ", images='" . basename($_FILES["images"]["name"]) . "'";
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }

    // Complete the SQL with the WHERE clause
    $sql .= " WHERE parentid='$parentid'";

    // Perform update query
    if (mysqli_query($conn, $sql)) {
        // Update session variables with new values
        $_SESSION['name'] = $parent_name;
        if ($_FILES['images']['name']) {
            $_SESSION['images'] = basename($_FILES["images"]["name"]);
        }

        // Redirect to profile page with success message
        header("Location: profile.php?message=Profile update successfully");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    // Redirect to profile page if accessed without form submission
    header("Location: profile.php");
    exit();
}
