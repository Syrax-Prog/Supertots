<?php
include "conn.php";

if (isset($_POST['submit'])) {
    $activityName = $_POST['actName'];
    $venue = $_POST['actVenue'];
    $descA = $_POST['actDesc'];
    $startdate = $_POST['actStart'];
    $enddate = $_POST['actEnd'];
    $startregisterdate = $_POST['actRegOp'];
    $endregisterdate = $_POST['actRegCl'];
    $payment = $_POST['actFee'];
    $totalparticipant = $_POST['actPart'];

    $sql = "INSERT INTO activity (activityName, venue, descA, startdate, enddate, startregisterdate, endregisterdate, payment, totalParticipant) VALUES ('$activityName', '$venue', '$descA', '$startdate', '$enddate', '$startregisterdate', '$endregisterdate', '$payment', '$totalparticipant')";
    if ($conn->query($sql) === TRUE) {
        $message = "Guardian Added Successfully";
        header("Location: manageActivity.php");
    }
}
