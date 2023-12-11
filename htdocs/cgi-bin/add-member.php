<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: ./404'); 
    die();
}

session_start();

//ISSET CHECK
if(!isset($_POST['board_id']) || !isset($_POST['new_member_email']) || !isset($_SESSION['user_id']) || !isset($_SESSION['SESSION_ticket'])) {die("Invalid Request");}

//SET VARIABLES
$boardId = $_POST['board_id'];
$email = urldecode($_POST['new_member_email']);
$newMemberId = -1;
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



$conn = new mysqli("mysql.cs.mcgill.ca", "ecadot", "n#K#p6CEVw2USkJVFNDyetUb", "2023fall-comp307-ecadot");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}


//VERIFY IF USER EXIST
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
        $newMemberId = $row['user_id'];
    }
    else if ($result->num_rows >= 1){
        die("Multiple Account with Email");
    }
}

//ADD MEMBER TO DB
$sql = "INSERT INTO board_access (user_id,board_id) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii' ,$newMemberId ,$boardId);

if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    die("TRUE");
} else {
    $conn->close();
    die("Failed to Execute the Querry");
}
$conn->close();
?>