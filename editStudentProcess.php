<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $id = $_POST['childid'];
    $name = $_POST['name'];
    $classid = $_POST['classid'];
    $gender = $_POST['gender'];

    // Handle image upload
    if (isset($_FILES["NewImage"]) && $_FILES["NewImage"]["error"] == 0) {
        $uploadDir = "img/student/";
        $newImg = $_FILES["NewImage"]["name"];
        $uploadFile = $uploadDir . basename($newImg);
        if (move_uploaded_file($_FILES["NewImage"]["tmp_name"], $uploadFile)) {
            // Image uploaded successfully, update database
            $sql = "UPDATE child SET name = '$name', classid = '$classid', gender = '$gender', images = '$newImg' WHERE childid = '$id'";
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
        $sql = "UPDATE child SET name = '$name', classid = '$classid', gender = '$gender' WHERE childid = '$id'";
        if ($conn->query($sql) === TRUE) {
            $message = "Data Update Successfully";
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    }

    header("Location: editStudent.php?id=$id&message=$message");
} else {
    $message = "Data Update Failed";
    header("Location: editStudent.php?id=$id&message=$message");
}

//Delete parent from child
if (isset($_GET['pid']) && isset($_GET['cid'])) {
    $pid = $_GET['pid'];
    $cid = $_GET['cid'];

    $sql = "DELETE FROM parent_child WHERE parentid = '$pid' AND childid = '$cid'";
    if ($conn->query($sql) === TRUE) {
        $message = "Data Successfully Removed";
        header("Location: editStudent.php?id=$cid&message=$message");
    } else {
        $message = "Data Update Failed";
        header("Location: editStudent.php?id=$cid&message=$message");
    }
}

if (isset($_POST['addG'])) {
    $pid = $_POST['parentid'];
    $cid = $_POST['childid'];

    $sql = "SELECT * FROM parent_child WHERE parentid = '$pid' AND childid = '$cid'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $message = "Selected Guardian Already Registered To Current Student";
        header("Location: editStudent.php?id=$cid&message=$message");
    } else {
        $sql = "INSERT INTO parent_child (parentid, childid) VALUES ('$pid', '$cid')";
        if ($conn->query($sql) === TRUE) {
            $message = "Guardian Added Successfully";
            header("Location: editStudent.php?id=$cid&message=$message");
        } else {
            $message = "Could Not Add The Guardian to The Student";
            header("Location: editStudent.php?id=$cid&message=$message");
        }
    }
}
