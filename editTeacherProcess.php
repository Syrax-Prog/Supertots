<?php

include "conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $i = $_POST['i'];
        $n = $_POST['n'];
        $q = $_POST['q'];

        // Handle image upload
        if (isset($_FILES["NewImage"]) && $_FILES["NewImage"]["error"] == 0) {
            $uploadDir = "img/staff/";
            $newImg = $_FILES["NewImage"]["name"];
            $uploadFile = $uploadDir . basename($newImg);
            if (move_uploaded_file($_FILES["NewImage"]["tmp_name"], $uploadFile)) {
                // Image uploaded successfully, update database
                $sql = "UPDATE staff SET name = '$n', qualification = '$q', images = '$newImg' WHERE staffid = '$i'";
                if ($conn->query($sql) === TRUE) {
                    $message = "Data Update Successfully";
                } else {
                    $message = "Error updating record: " . $conn->error;
                }
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        } else {
            // No new image uploaded, update other fields only
            $sql = "UPDATE staff SET name = '$n', qualification = '$q' WHERE staffid = '$i'";
            if ($conn->query($sql) === TRUE) {
                $message = "Data Update Successfully";
            } else {
                $message = "Error updating record: " . $conn->error;
            }
        }

        header("Location: editTeacher.php?id=$i&message=$message");
    } else {
        $message = "Data Update Failed";
        header("Location: editTeacher.php?id=$i&message=$message");
    }
}

if (isset($_GET['tid']) && isset($_GET['cid'])) {
    $pid = $_GET['tid'];
    $cid = $_GET['cid'];

    $sql = "DELETE FROM class_teacher WHERE staffid = '$pid' AND classid = '$cid'";
    if ($conn->query($sql) === TRUE) {
        $message = "Record Successfully Removed";
        header("Location: editteacher.php?id=$pid&message=$message");
    } else {
        $message = "Record Remove Failed";
        header("Location: editTeacher.php?id=$pid&message=$message");
    }
}

if (isset($_POST['addAC'])) {
    $cid = $_POST['classid'];
    $sid = $_POST['staffid'];

    $sql = "SELECT * FROM class_teacher WHERE classid = '$cid' AND staffid = '$sid'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $message = "Current Record Already Existed";
        header("Location: editTeacher.php?id=$sid&message=$message");
    } else {
        $sql = "INSERT INTO class_teacher (classid, staffid) VALUES ('$cid', '$sid')";
        if ($conn->query($sql) === TRUE) {
            $message = "Record Added Succesfuly";
            header("Location: editTeacher.php?id=$sid&message=$message");
        } else {
            $message = "Error occured, Cannot Add Record";
            header("Location: editTeacher.php?id=$sid&message=$message");
        }
    }
}

if (isset($_POST['tid']) && isset($_POST['cid'])) {
    $tid = $_GET['tid'];
    $cid = $_GET['cid'];

    $sql = "DELETE FROM class_teacher WHERE staffid = '$tid' AND classid = '$cid'";
    if ($conn->query($sql) === TRUE) {
        $message = "Record Successfully Removed";
        header("Location: editTeacher.php?id=$tid&message=$message");
    } else {
        $message = "Error Occured When Trying To Remove Data";
        header("Location: editTeacher.php?id=$tid&message=$message");
    }
}
