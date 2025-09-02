<?php
include "conn.php";

$classid = $_GET['classid'];

$query = "SELECT childid, name FROM child WHERE classid = '$classid'";
$result = mysqli_query($conn, $query);

$students = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

echo json_encode($students);
?>
