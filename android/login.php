<?php
$servername = "127.0.0.1:3307";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "supertots"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve username and password from the request
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT parentid FROM parent WHERE parentid = '$username' AND password = '$password'";
$result = $conn->query($sql);

$response = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['status'] = 'success';
    $response['parent_id'] = $row['parentid'];
} else {
    $response['status'] = 'fail';
    $response['message'] = 'Invalid username or password';
}

echo json_encode($response);

$conn->close();
?>
