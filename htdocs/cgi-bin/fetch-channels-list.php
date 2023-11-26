<?php
session_start();

#TODO : VERIFY TICKET

if(!isset($_POST['boardId']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

$userId = $_SESSION['user_id'];

$boardId = $_POST['boardId'];

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
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
$sql = "SELECT name FROM channels WHERE board_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $boardId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();
    while($row = mysqli_fetch_array($result)){
        $rows[] = array('name' => $row['name']);
    }
    $conn->close();
    die(json_encode($rows));
} else {
    $conn->close();
    die('Failed to execute the query.');
}
?>