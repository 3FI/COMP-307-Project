<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method.']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$userid = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

$sql = "SELECT ticket FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userid);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $row = $result->fetch_assoc();

    $ticketFromDB = $row['ticket']; //ticket from DB

    $ticketFromSESSION = $_SESSION['SESSION_ticket']; //ticket from POST request

    $isValid = ($ticketFromSESSION == $ticketFromDB);

    $response = ['isValid' => $isValid];

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Failed to execute the query.']);
}

$conn->close();
?>