<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') { 
    header('Location: /404'); 
    die();
}

//ISSET CHECK
if(!isset($_SESSION['user_id']) || !isset($_SESSION['SESSION_ticket'])) {die("Invalid Request");}

//SET VARIABLES
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

//FETCH THE LIST OF BOARDS FOR USERID
$sql = "SELECT boards.name as pName, boards.description as pDescription, boards.id as pId, boards.color as pColor, board_access.subscribed as pSubscribed
FROM boards
JOIN board_access ON boards.id = board_access.board_id
WHERE board_access.user_id = ?;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();
    while($row = mysqli_fetch_array($result)){
        $rows[] = array('name' => $row['pName'], 'description' => $row['pDescription'], 'id' => $row['pId'], 'color' => $row['pColor'], 'subscribed' => $row['pSubscribed']);
    }
    echo json_encode($rows);
} else {
    echo json_encode(['error' => 'Failed to execute the query.']);
}



$conn->close();
?>