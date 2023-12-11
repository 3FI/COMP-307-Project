<?php

/*  
REQUIRED IN MOST PHP FILE FOR AN ADDITIONAL TICKET CHECK
BEFORE CONNECTING TO THE DB
*/

$conn = new mysqli("mysql.cs.mcgill.ca", "ecadot", "n#K#p6CEVw2USkJVFNDyetUb", "2023fall-comp307-ecadot");
if ($conn->connect_error) {
    $is_valid = false;
}

//SELECT USER TICKET
$sql = "SELECT ticket FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $row = $result->fetch_assoc();

    $ticketFromDB = $row['ticket']; //ticket from DB

    $ticketFromSESSION = $_SESSION['SESSION_ticket']; //ticket from POST request

    //CHECK IF TICKETS ARE EQUAL
    if ($ticketFromSESSION == $ticketFromDB){
        $is_valid = true;
    }
    else{
        $is_valid = false;
    }

} else {
    $is_valid = false;
}

$conn->close();
?>