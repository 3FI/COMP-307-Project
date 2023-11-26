<?php
session_start();

#TODO : VERIFY TICKET

if(!isset($_POST['name']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

$name = $_POST['name'];
$description = $_POST['description'];
$userId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

$sql = "INSERT INTO boards (name,description,admin_id) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ssi',$name ,$description ,$userId);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_query($conn, 'SELECT LAST_INSERT_ID()');
    $row = mysqli_fetch_array($result);
    $boardId = $row[0];
} else {
    $conn->close();
    die("Failed to Execute the Querry");
}

$sql = "INSERT INTO board_access (user_id,board_id) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii' ,$userId ,$boardId);

if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    die("TRUE");
} else {
    $conn->close();
    die("Failed to Execute the Querry");
}

?>