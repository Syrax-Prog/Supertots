<?php
include "conn.php";

$childid = $_GET['childid'];
$currentYear = date('Y');

$query = "
    SELECT exam.examType, progress.examMarks 
    FROM progress 
    JOIN exam ON progress.examID = exam.examID 
    WHERE progress.childid = '$childid' AND YEAR(exam.examDate) = '$currentYear'
";
$result = mysqli_query($conn, $query);

$progress = [];
while ($row = mysqli_fetch_assoc($result)) {
    $progress[] = $row;
}

echo json_encode($progress);
?>
