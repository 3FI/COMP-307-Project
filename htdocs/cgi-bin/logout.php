<?php
    
    session_start();

    $userid = $_SESSION['user_id'];

    $conn = new mysqli("localhost", "root", "", "COMP307-Project");
    if ($conn->connect_error) {
        die("Internal Server Error: " . $conn->connect_error);
    }

    $sql = "UPDATE users SET ticket = NULL WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userid);

    if (mysqli_stmt_execute($stmt)) {
        session_unset();
        session_destroy();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    header('Location: /login-form');

    // Close the database connection
    $conn->close();
?>