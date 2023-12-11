<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: ./404'); 
    die();
}

//ISSET CHECK
if(!isset($_POST['name']) || !isset($_SESSION['user_id']) || !isset($_SESSION['SESSION_ticket'])) {die("Invalid Request");}

//SET VARIABLES
$name = $_POST['name'];
$description = $_POST['description'];
$userId = $_SESSION['user_id'];
$color = $_POST['color'];

//TICKET CHECK
require 'validate-ticket-include.php';

if(!$is_valid || !isset($is_valid)){
    die('TICKET NOT VALID');
}

$conn = new mysqli("mysql.cs.mcgill.ca", "ecadot", "n#K#p6CEVw2USkJVFNDyetUb", "2023fall-comp307-ecadot");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

//CREATE BOARD INTO "BOARDS" TABLE
$sql = "INSERT INTO boards (name,description,admin_id,color) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ssis',$name ,$description ,$userId ,$color);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_query($conn, 'SELECT LAST_INSERT_ID()');
    $row = mysqli_fetch_array($result);
    $boardId = $row[0];
} else {
    $conn->close();
    die("Failed to Execute the Querry");
}

//CREATE USER ACCESS INTO "BOARD_ACCESS" TABLE
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
$conn->close();
?>