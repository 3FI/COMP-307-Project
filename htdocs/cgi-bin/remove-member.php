<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: ./404'); 
    die();
}

//ISSET CHECK
if(!isset($_POST['board_id']) || !isset($_POST['old_member_email']) || !isset($_SESSION['user_id']) || !isset($_SESSION['SESSION_ticket'])) {die("Invalid Request");}

//SET VARIABLES
$boardId = $_POST['board_id'];
$email = $_POST['old_member_email'];
$oldMemberId = -1;
$userId = $_SESSION['user_id'];

//TICKET CHECK
require 'validate-ticket-include.php';

if(!$is_valid || !isset($is_valid)){
    die('TICKET NOT VALID');
}

//ADMIN CHECK
require 'is-admin-include.php';

if(!$is_valid || !isset($is_valid)){
    die("Access Denied");
}

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}


//SELECT INFO FROM USER
$sql = "SELECT * FROM users WHERE email=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $email);
if (mysqli_stmt_execute($stmt)){
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows === 0) {
        $conn->close();
        die("Invalid Email");
    }
    else if ($result->num_rows === 1){
        $row = mysqli_fetch_array($result);
        $oldMemberId = $row['user_id'];
    }
    else if ($result->num_rows >= 1){
        $conn->close();
        die("Multiple Account with Email");
    }
}

#CHECK IF ADMIN IS TRYING TO REMOVE HIMSELF
$sql = "SELECT * FROM boards WHERE admin_id=? and id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $oldMemberId, $boardId);
if (mysqli_stmt_execute($stmt)){
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows === 1) {
        $conn->close();
        die("Admin cannot remove himself");
    }
}

#DELETE MEMBER FORM BOARD
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

$conn->close();

?>