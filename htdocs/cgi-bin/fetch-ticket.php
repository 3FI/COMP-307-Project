<?php
session_start();

$userId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

$sql = "SELECT ticket FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $row = $result->fetch_assoc();

    // Return the ticket as JSON
    echo json_encode(['ticket' => $row['ticket']]);
} else {
    echo json_encode(['error' => 'Failed to execute the query.']);
}

$conn->close();
?>