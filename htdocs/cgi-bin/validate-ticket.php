<?php

/*
TICKET VERIFICATION FOR THE ONLOAD JAVASCRIPT CALL OF SELECT-DISCUSSION.HTML
*/


session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: ./404'); 
    die();
}

//ISSET CHECK
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

//SET VARIABLES
$userid = $_SESSION['user_id'];

$conn = new mysqli("mysql.cs.mcgill.ca", "ecadot", "n#K#p6CEVw2USkJVFNDyetUb", "2023fall-comp307-ecadot");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

//SELECT USER TICKET
$sql = "SELECT ticket FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userid);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $row = $result->fetch_assoc();

    $ticketFromDB = $row['ticket']; //ticket from DB

    $ticketFromSESSION = $_SESSION['SESSION_ticket']; //ticket from POST request

    //CHECK IF TICKETS ARE EQUAL
    $isValid = ($ticketFromSESSION == $ticketFromDB);

    $response = ['isValid' => $isValid];

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Failed to execute the query.']);
}

$conn->close();
?>