<?php
session_start();

if(!isset($_SESSION['user_id'])) {die("Invalid Request");}

$userId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

$sql = "SELECT name,description FROM boards WHERE id in (SELECT board_id FROM board_access WHERE user_id = ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();
    while($row = mysqli_fetch_array($result)){
        $rows[] = array('name' => $row['name'], 'description' => $row['description']);
    }
    echo json_encode($rows);
} else {
    echo json_encode(['error' => 'Failed to execute the query.']);
}


$conn->close();
?>