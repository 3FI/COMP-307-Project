<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

if(!isset($_POST['channelId']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

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

#FETCH THE MESSAGES
$sql = "SELECT a.message,a.date, b.username,a.id,a.is_pinned FROM messages AS a INNER JOIN users as b ON a.user_id=b.user_id WHERE a.channel_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i',$channelId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();
    while($row = mysqli_fetch_array($result)){
        $rows[] = array('username' => $row['username'], 'message' => $row['message'], 'date' => $row['date'], 'id' => $row['id'], 'is_pinned' => $row['is_pinned']);
    }
    $conn->close();
    die(json_encode($rows));
} else {
    $conn->close();
    die('Failed to execute the query.');
}
?>