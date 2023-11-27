<?php
session_start();

#TODO : VERIFY TICKET

if(!isset($_POST['message_id']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

$messageId = $_POST['message_id'];
$userId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

$isAdmin = FALSE;
$isSelf = FALSE;

#VERIFY ADMIN ACCESS
$sql = "SELECT * FROM boards WHERE admin_id=? and id=(SELECT board_id FROM channels WHERE id=(SELECT channel_id FROM messages WHERE id=?))";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $userId, $messageId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows === 1) {
        $isAdmin = TRUE;
    }
}

$sql = "SELECT * FROM messages WHERE user_id=?,id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $userId, $messageId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows === 1) {
        $isSelf = TRUE;
    }
}

if(!$isAdmin && !$isSelf){
    $conn->close();
    die("Access Denied");
}


$sql = "DELETE FROM messages where id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i',$messageId);

if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    die("TRUE");
} else {
    $conn->close();
    die("Failed to Execute the Querry");
}

?>