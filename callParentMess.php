<?php
session_start();
include 'conn.php';
$parentid = $_SESSION['id']; // Assuming parent id is stored in session
$staffid = $_GET['staffid'];

$sql = "SELECT * FROM messages WHERE (sender_id = '$parentid' AND receiver_id = '$staffid') OR (sender_id = '$staffid' AND receiver_id = '$parentid') ORDER BY timestamp ASC";
$result = $conn->query($sql);

$messages = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

echo json_encode($messages);
