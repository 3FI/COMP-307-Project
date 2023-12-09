<?php

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    $is_valid = false;
}

$sql = "SELECT ticket FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $row = $result->fetch_assoc();

    $ticketFromDB = $row['ticket']; //ticket from DB

    $ticketFromSESSION = $_SESSION['SESSION_ticket']; //ticket from POST request

    if ($ticketFromSESSION == $ticketFromDB){
        $is_valid = true;
    }

} else {
    $is_valid = false;
}

$conn->close();
?>