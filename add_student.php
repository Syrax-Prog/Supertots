<?php
include 'conn.php';

function generateChildId($conn) {
    $prefix = "CH";
    $query = "SELECT MAX(childid) AS max_id FROM child";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    if ($row['max_id']) {
        $maxId = (int) substr($row['max_id'], 2); // Remove prefix and convert to integer
        $newId = $maxId + 1;
    } else {
        $newId = 1; // Start from 1 if no IDs are found
    }

    $childid = $prefix . str_pad($newId, 4, '0', STR_PAD_LEFT);
    return $childid;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $classid = $_POST['classid'];
    $images = $_FILES['images']['name'];

    // Generate child ID
    $childid = generateChildId($conn);

    // Calculate age using only the year
    $birthYear = (new DateTime($dob))->format('Y');
    $currentYear = (new DateTime())->format('Y');
    $age = $currentYear - $birthYear;

    // Validate age
    if ($age < 4 || $age > 6) {
        header("Location: add.php?message=Age Must Be Between Age 4 To 6&o=Student");
        die;
    }
    // Only save the name of the uploaded file
    $targetFile = basename($images);

    // Insert into database
    $sql = "INSERT INTO child (childid, name, gender, classid, dob, images) VALUES ('$childid', '$name', '$gender', '$classid', '$dob', '$targetFile')";

    if ($conn->query($sql) === TRUE) {
        header("Location: add.php?message=New Student Add Successfully&o=Student");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
