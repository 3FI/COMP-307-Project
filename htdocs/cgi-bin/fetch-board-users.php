<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: ./404'); 
    die();
}

//ISSET CHECK
if(!isset($_POST['boardId'])|| !isset($_SESSION['SESSION_ticket'])) {die("Invalid Request");}

//SET VARIABLES
$boardId = $_POST['boardId'];
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

//SELECT ALL USER_ID FROM SPECIFIC BOARD_ID
$sql = "SELECT u.username
FROM board_access ba
JOIN users u ON ba.user_id = u.user_id
WHERE ba.board_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $boardId);

if (mysqli_stmt_execute($stmt)) {

    $result = mysqli_stmt_get_result($stmt);

    $usernames = array();  // Initialize an array to store user_ids

    while($row = mysqli_fetch_array($result)){
        $usernames[] = $row['username'];
    }

    $conn->close();

    die(json_encode(array('users' => $usernames)));
} else {
    $conn->close();
    die('Failed to execute the query.');
}
?>