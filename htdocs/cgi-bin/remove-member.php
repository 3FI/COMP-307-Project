<?php
session_start();

#TODO : VERIFY TICKET

if(!isset($_POST['board_id']) || !isset($_POST['old_member_email']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

$boardId = $_POST['board_id'];
$email = $_POST['old_member_email'];
$oldMemberId = -1;
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

$sql = "SELECT * FROM users WHERE email=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $email);
if (mysqli_stmt_execute($stmt)){
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows === 0) {
        die("Invalid Email");
    }
    else if ($result->num_rows === 1){
        $row = mysqli_fetch_array($result);
        $oldMemberId = $row['user_id'];
    }
    else if ($result->num_rows >= 1){
        die("Multiple Account with Email");
    }
}


$sql = "DELETE FROM board_access WHERE user_id=? and board_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii' ,$oldMemberId ,$boardId);

if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    die("TRUE");
} else {
    $conn->close();
    die("Failed to Execute the Querry");
}

?>