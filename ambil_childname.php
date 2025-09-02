<?php
include "conn.php";

if (isset($_GET['childid'])) {
    $childid = $_GET['childid'];
    $query = "SELECT name FROM child WHERE childid = '$childid'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['name' => $row['name']]);
    } else {
        echo json_encode(['name' => 'Unknown']);
    }
} else {
    echo json_encode(['name' => 'Unknown']);
}
?>
