<?php
$servername = "127.0.0.1:3307";
$username = "root";
$password = "";
$dbname = "supertots";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$parentId = $_GET['parent_id'];

$sql = "SELECT DISTINCT s.staffid, s.name 
        FROM staff s
        JOIN class_teacher ct ON s.staffid = ct.staffid
        JOIN class c ON ct.classid = c.classid
        JOIN child ch ON c.classid = ch.classid
        JOIN parent_child pc ON ch.childid = pc.childid
        WHERE pc.parentid = '$parentId'";

$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

echo json_encode($data);
?>
