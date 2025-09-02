<?php
include 'conn.php';

function generateChildId($conn)
{
    $prefix = "TE";
    $query = "SELECT MAX(staffid) AS max_id FROM staff";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    if ($row['max_id']) {
        $maxId = (int) substr($row['max_id'], 2); // Remove prefix and convert to integer
        $newId = $maxId + 1;
    } else {
        $newId = 1; // Start from 1 if no IDs are found
    }

    $tid = $prefix . str_pad($newId, 4, '0', STR_PAD_LEFT);
    return $tid;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $q = $_POST['qualification'];
    $images = $_FILES['images'];

    // Validate file upload
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($images['type'], $allowedTypes)) {
        echo "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
        exit;
    }

    if ($images['size'] > $maxFileSize) {
        echo "File size exceeds the 2MB limit.";
        exit;
    }

    $gambar = basename($images['name']);

    // Generate child ID
    $pid = generateChildId($conn);

    // Generate a random password
    $randomPassword = 'TE';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $length = 8; // Adjust length as needed

    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $chars[rand(0, strlen($chars) - 1)];
    }

    // Hash the generated password
    $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);

    // Ensure the img/student/ directory exists
    $targetDirectory = 'img/student/';
    if (!is_dir($targetDirectory)) {
        mkdir($targetDirectory, 0755, true);
    }

    // Move the uploaded file to the target directory
    $targetFile = $targetDirectory . $gambar;
    if (move_uploaded_file($images['tmp_name'], $targetFile)) {
        // Insert into database using prepared statements
        $sql = $conn->prepare("INSERT INTO staff (staffid, qualification, password, role, name, images) VALUES (?, ?, ?, 'Teacher', ?, ?)");
        $sql->bind_param('sssss', $pid, $q, $hashedPassword, $name, $gambar);

        if ($sql->execute()) {
            header("Location: add.php?message=New Teacher Add Successfully&o=Teacher");
        } else {
            echo "Error: " . $sql->error;
        }

        $sql->close();
    } else {
        echo "Error uploading the file.";
    }

    $conn->close();
}
?>
