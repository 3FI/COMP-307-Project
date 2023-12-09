<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') { 
    header('Location: /404'); 
    die();
}

#TODO : VERIFY TICKET

if(!isset($_SESSION['user_id'])) {die("Invalid Request");}

$userId = $_SESSION['user_id'];

//RIGHT HERE CALL VALIDATE-TICKET-INCLUDE TO CHECK TICKET
require 'validate-ticket-include.php';

if(!$is_valid){
    die('TICKET NOT VALID');
}

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

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