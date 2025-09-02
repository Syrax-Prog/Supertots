<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $id = $_POST['id'];

    $sql = "INSERT INTO class (classid, classname) VALUES ('$id', '$name')";

    if ($conn->query($sql) === TRUE) {
        header("Location: add.php?message=New Class Add Successfully&o=Class");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
