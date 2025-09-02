<?php
session_start();
include 'conn.php';
$staffid = $_SESSION['name'];
$parentid = $_GET['parentid'];

$sql = "SELECT * FROM messages WHERE (sender_id = '$staffid' AND receiver_id = '$parentid') OR (sender_id = '$parentid' AND receiver_id = '$staffid') ORDER BY timestamp ASC";
$result = $conn->query($sql);

$messages = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

echo json_encode($messages);