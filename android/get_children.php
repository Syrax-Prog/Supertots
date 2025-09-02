<?php
$servername = "127.0.0.1:3307"; // Update with your MySQL server details
$username = "root"; // Replace with your actual DB username
$password = ""; // Replace with your actual DB password
$dbname = "supertots";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$parentId = $_GET['parent_id']; // Assume you pass parent_id as a query parameter

$sql = "SELECT child.childid, child.name 
        FROM parent_child 
        JOIN child ON parent_child.childid = child.childid 
        WHERE parent_child.parentid = '$parentId'";

$result = $conn->query($sql);

$children = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $children[] = $row;
    }
} 

echo json_encode($children);

$conn->close();
?>
