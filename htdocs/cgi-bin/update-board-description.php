<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

//ISSET CHECK
if( !isset($_POST['boardName']) || !isset($_POST['description']) || !isset($_SESSION['user_id']) || !isset($_SESSION['SESSION_ticket'])) {die("Invalid Request");}

//SET VARIABLES
$board_name = $_POST['boardName'];
$new_description = $_POST['description'];
$userId = $_SESSION['user_id'];

//TICKET CHECK
require 'validate-ticket-include.php';

if(!$is_valid || !isset($is_valid)){
    die('TICKET NOT VALID');
}

$conn = new mysqli("mysql.cs.mcgill.ca", "ecadot", "n#K#p6CEVw2USkJVFNDyetUb", "2023fall-comp307-ecadot");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

#UPDATE BOARD'S DESCRIPTION 
$sql = "UPDATE boards
SET description = ?
WHERE name = ?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ss', $new_description, $board_name);
if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    $return_text = array( 'message' => "Successfully Updated the Description");
    die(json_encode($return_text));
} else {
    $conn->close();
    die('Failed to execute the query.');
}
?>