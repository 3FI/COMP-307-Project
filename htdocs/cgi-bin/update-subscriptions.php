<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: ./404'); 
    die();
}

//ISSET CHECK
if(!isset($_POST['boardName']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

//SET VARIABLES
$mode = $_POST['mode'];
$boardName = $_POST['boardName'];
$userId = $_SESSION['user_id'];

//TICKET CHECK
require 'validate-ticket-include.php';

if(!$is_valid || !isset($is_valid)){
    die('TICKET NOT VALID');
}

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

#UPDATE SUBSCRIPTION RECORDS 
$sql = "UPDATE board_access
SET subscribed = ?
WHERE board_ID = (
    SELECT id
    FROM boards
    WHERE name = ?
) AND user_id = ?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'isi', $mode, $boardName, $userId);
if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    die(json_encode("Successfully updated the subscriptions"));
} else {
    $conn->close();
    die('Failed to execute the query.');
}
?>