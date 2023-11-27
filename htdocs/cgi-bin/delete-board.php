<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

#TODO : VERIFY TICKET

if(!isset($_POST['board_id']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

$boardId = $_POST['board_id'];
$userId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

#VERIFY ADMIN ACCESS
$sql = "SELECT * FROM boards WHERE admin_id=? and id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $userId, $boardId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows !== 1) {
        $conn->close();
        die("Access Denied");
    }
}

$sql = "DELETE FROM boards where id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i',$boardId);

if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    die("TRUE");
} else {
    $conn->close();
    die("Failed to Execute the Querry");
}

?>