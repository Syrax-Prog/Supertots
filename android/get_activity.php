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

$childId = $_GET['child_id'];
$currentDate = date('Y-m-d');

$sql_ongoing = "SELECT a.activityName, a.startdate, a.enddate 
                FROM activity a 
                JOIN activity_child ac ON a.activityID = ac.activityid 
                WHERE ac.childid = '$childId' AND a.startdate <= '$currentDate' AND a.enddate >= '$currentDate'";

$sql_upcoming = "SELECT a.activityName, a.startdate, a.enddate 
                 FROM activity a 
                 JOIN activity_child ac ON a.activityID = ac.activityid 
                 WHERE ac.childid = '$childId' AND a.startdate > '$currentDate'";

$sql_passed = "SELECT a.activityName, a.startdate, a.enddate 
               FROM activity a 
               JOIN activity_child ac ON a.activityID = ac.activityid 
               WHERE ac.childid = '$childId' AND a.enddate < '$currentDate'";

$result_ongoing = $conn->query($sql_ongoing);
$result_upcoming = $conn->query($sql_upcoming);
$result_passed = $conn->query($sql_passed);

$data = [
    'ongoing' => [],
    'upcoming' => [],
    'passed' => []
];

if ($result_ongoing->num_rows > 0) {
    while ($row = $result_ongoing->fetch_assoc()) {
        $data['ongoing'][] = $row;
    }
}

if ($result_upcoming->num_rows > 0) {
    while ($row = $result_upcoming->fetch_assoc()) {
        $data['upcoming'][] = $row;
    }
}

if ($result_passed->num_rows > 0) {
    while ($row = $result_passed->fetch_assoc()) {
        $data['passed'][] = $row;
    }
}

$conn->close();

echo json_encode($data);
?>
