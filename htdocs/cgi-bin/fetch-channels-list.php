<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

//ISSET CHECK
if(!isset($_POST['boardId']) || !isset($_SESSION['user_id']) || !isset($_SESSION['SESSION_ticket'])) {die("Invalid Request");}

//SET VARIABLES
$userId = $_SESSION['user_id'];
$boardId = $_POST['boardId'];

//TICKET CHECK
require 'validate-ticket-include.php';

if(!$is_valid || !isset($is_valid)){
    die('TICKET NOT VALID');
}

$conn = new mysqli("mysql.cs.mcgill.ca", "ecadot", "n#K#p6CEVw2USkJVFNDyetUb", "2023fall-comp307-ecadot");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

#VERIFY BOARD ACCESS
$sql = "SELECT * FROM board_access WHERE user_id=? and board_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $userId, $boardId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows !== 1) {
        $conn->close();
        die("Access Denied");
    }
}

#FETCH THE CHANNELS
$sql = "SELECT name,id FROM channels WHERE board_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $boardId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();
    while($row = mysqli_fetch_array($result)){
        $rows[] = array('name' => $row['name'],'id' => $row['id']);
    }
    $conn->close();
    die(json_encode($rows));
} else {
    $conn->close();
    die('Failed to execute the query.');
}
?>