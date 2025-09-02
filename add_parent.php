<?php
include 'conn.php';

function generateChildId($conn)
{
    $prefix = "PA";
    $query = "SELECT MAX(parentid) AS max_id FROM parent";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    if ($row['max_id']) {
        $maxId = (int) substr($row['max_id'], 2); // Remove prefix and convert to integer
        $newId = $maxId + 1;
    } else {
        $newId = 1; // Start from 1 if no IDs are found
    }

    $pid = $prefix . str_pad($newId, 4, '0', STR_PAD_LEFT);
    return $pid;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $wp = $_POST['workphone'];
    $images = $_FILES['images']['name'];

    // Generate child ID
    $pid = generateChildId($conn);


    //-----------------------------------------------------------------random pass--------------------------------------------------------
    $randomPassword = 'PA';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $length = 8; // Adjust length as needed

    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $chars[rand(0, strlen($chars) - 1)];
    }

    // Assign the generated password to a variable for later use
    $generatedPassword = $randomPassword;
    //-----------------------------------------------------------------random pass--------------------------------------------------------



    // Only save the name of the uploaded file
    $targetFile = basename($images);

    // Insert into database
    $sql = "INSERT INTO parent (parentid, parent_name, emergencycontact, workphone, password, images) VALUES ('$pid', '$name', '$contact', '$wp', '$generatedPassword', '$targetFile')";

    if ($conn->query($sql) === TRUE) {
        header("Location: add.php?message=New Parent Add Successfully&o=Parent");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
