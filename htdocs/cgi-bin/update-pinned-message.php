<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

if( !isset($_POST['pinned_status']) || !isset($_POST['message_id']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

$message_id = $_POST['message_id'];
$is_pinned = $_POST['pinned_status'];
$userId = $_SESSION['user_id'];

if ($is_pinned == 1){
    $set_pinned_msg_to = 0;
} else {
    $set_pinned_msg_to = 1;
}

//RIGHT HERE CALL VALIDATE-TICKET-INCLUDE TO CHECK TICKET
require 'validate-ticket-include.php';

if(!$is_valid){
    die('TICKET NOT VALID');
}

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

#UPDATE is_pinned status
$sql = "UPDATE messages
SET is_pinned = ?
WHERE id = ?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $set_pinned_msg_to, $message_id);
if (mysqli_stmt_execute($stmt)) {
    $conn->close();

    if ($set_pinned_msg_to == 1){
        $return_text = array( 'message' => "Successfully pinned message");
    } else {
        $return_text = array( 'message' => "Successfully unpinned message");
    }

    die(json_encode($return_text));
} else {
    $conn->close();
    die('Failed to execute the query.');
}
?>