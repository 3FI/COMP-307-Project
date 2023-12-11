<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

//ISSSET CHECK
if(!isset($_POST['message_id']) || !isset($_SESSION['user_id']) || !isset($_SESSION['SESSION_ticket'])) {die("Invalid Request");}

//SET VARIABLES
$messageId = $_POST['message_id'];
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

$isAdmin = FALSE;
$isSelf = FALSE;

//VERIFY ADMIN ACCESS FROM MESSAGE ID
$sql = "SELECT * FROM boards WHERE admin_id=? and id=(SELECT board_id FROM channels WHERE id=(SELECT channel_id FROM messages WHERE id=?))";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $userId, $messageId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows === 1) {
        $isAdmin = TRUE;
    }
}

//VERIFY IF MESSAGE FROM USER
$sql = "SELECT * FROM messages WHERE user_id=? and id=?";
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

//DELETE MESSAGE 
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
$conn->close();
?>