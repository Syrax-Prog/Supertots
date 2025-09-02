<?php
$servername = "127.0.0.1:3307"; // Update with your MySQL server details
$username = "root"; // Replace with your actual DB username
$password = ""; // Replace with your actual DB password
$dbname = "supertots";

$parentId = $_GET['parent_id'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT parent_name, emergencycontact, workphone, images FROM parent WHERE parentid = '$parentId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode([]);
}

$conn->close();
?>
