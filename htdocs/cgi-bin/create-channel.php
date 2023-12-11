<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

//ISSET CHECK
if(!isset($_POST['boardId']) || !isset($_POST['name']) || !isset($_SESSION['user_id']) || !isset($_SESSION['SESSION_ticket'])) {die("Invalid Request");}

//SET VARIABLES
$name = $_POST['name'];
$userId = $_SESSION['user_id'];
$boardId = $_POST['boardId'];

//TICKET CHECK
require 'validate-ticket-include.php';

if(!$is_valid || !isset($is_valid)){
    die('TICKET NOT VALID');
}

//ADMIN CHECK
require 'is-admin-include.php';

if(!$is_valid || !isset($is_valid)){
    die("Access Denied");
}

$conn = new mysqli("mysql.cs.mcgill.ca", "ecadot", "n#K#p6CEVw2USkJVFNDyetUb", "2023fall-comp307-ecadot");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

#CREATE THE CHANNEL
$sql = "INSERT INTO channels (board_id,name) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'is',$boardId,$name);

if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    die("TRUE");
} else {
    $conn->close();
    die("Failed to Execute the Querry");
}

$conn->close();
?>