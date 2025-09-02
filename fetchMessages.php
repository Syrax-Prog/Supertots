<?php
session_start();
include 'conn.php'; 

$teacherid = $_GET['teacherid'];
$parentid = $_SESSION['id'];

$sql = "SELECT * FROM messages WHERE (sender_id = '$teacherid' AND receiver_id = '$parentid') OR (sender_id = '$parentid' AND receiver_id = '$teacherid') ORDER BY timestamp ASC";
$result = $conn->query($sql);

$messages = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

echo json_encode($messages);
?>
