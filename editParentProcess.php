<?php
include "conn.php";

if (isset($_POST['addCP'])) {
    $pid = $_POST['parentid'];
    $cid = $_POST['childid'];

    $sql = "SELECT * FROM parent_child WHERE parentid = '$pid' AND childid = '$cid'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $message = "Record Already Exist, Failed To Add";
        header("Location: editParents.php?id=$pid&message=$message");
    } else {
        $sql = "INSERT INTO parent_child (parentid, childid) VALUES ('$pid', '$cid')";
        if ($conn->query($sql) === TRUE) {
            $message = "Record Updated Successfully";
            header("Location: editParents.php?id=$pid&message=$message");
        } else {
            $message = "Failed Updating Record";
            header("Location: editParents.php?id=$pid&message=$message");
        }
    }
}

if (isset($_GET['pid']) && isset($_GET['cid'])) {
    $pid = $_GET['pid'];
    $cid = $_GET['cid'];

    $sql = "DELETE FROM parent_child WHERE parentid = '$pid' AND childid = '$cid'";
    if ($conn->query($sql) === TRUE) {
        $message = "Data Successfully Removed";
        header("Location: editParents.php?id=$pid&message=$message");
    } else {
        $message = "Data Update Failed";
        header("Location: editParents.php?id=$pid&message=$message");
    }
}

if (isset($_POST['submit'])) {
    $parentid = $_POST['id'];
    $parentname = $_POST['name'];
    $emeCon = $_POST['num1'];
    $workCon = $_POST['num2'];

    // Handle image upload
    if (isset($_FILES["NewImage"]) && $_FILES["NewImage"]["error"] == 0) {
        $uploadDir = "img/parent/";
        $newImg = $_FILES["NewImage"]["name"];
        $uploadFile = $uploadDir . basename($newImg);
        if (move_uploaded_file($_FILES["NewImage"]["tmp_name"], $uploadFile)) {
            // Image uploaded successfully, update database
            $sql = "UPDATE parent SET parent_name = '$parentname', emergencycontact = '$emeCon', workphone = '$workCon' , images = '$newImg' WHERE parentid = '$parentid'";
            if ($conn->query($sql) === TRUE) {
                $message = "Data Successfully";
            } else {
                $message = "Error updating record: " . $conn->error;
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    } else {
        // No new image uploaded, update other fields only
        $sql = "UPDATE parent SET parent_name = '$parentname', emergencycontact = '$emeCon', workphone = '$workCon' WHERE parentid = '$parentid'";
        if ($conn->query($sql) === TRUE) {
            $message = "Data Update Successfully";
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    }

    header("Location: editParents.php?id=$parentid&message=$message");
}
