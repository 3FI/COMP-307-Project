<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

if(!isset($_POST['channelId']) || !isset($_POST['message']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

$message = $_POST['message'];
$userId = $_SESSION['user_id'];
$channelId = $_POST['channelId'];

//RIGHT HERE CALL VALIDATE-TICKET-INCLUDE TO CHECK TICKET
require 'validate-ticket-include.php';

if(!$is_valid){
    die('TICKET NOT VALID');
}

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

#VERIFY BOARD ACCESS
$sql = "SELECT * FROM board_access WHERE user_id=? and board_id=(SELECT board_id FROM channels WHERE id=?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $userId, $channelId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows !== 1) {
        $conn->close();
        die("Access Denied");
    }
}

#CREATE THE MESSAGE
$sql = "INSERT INTO messages (channel_id,user_id,message) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'iis',$channelId,$userId,$message);

if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    die("TRUE");
} else {
    $conn->close();
    die("Failed to Execute the Querry");
}

$conn->close();
?>