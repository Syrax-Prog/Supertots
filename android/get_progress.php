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

$sql = "SELECT exam.examDate, progress.examMarks, exam.examType
        FROM progress 
        JOIN exam ON progress.examID = exam.examID 
        WHERE progress.childid = '$childId' AND YEAR(exam.examDate) = YEAR(CURDATE())";

$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

echo json_encode($data);
?>
