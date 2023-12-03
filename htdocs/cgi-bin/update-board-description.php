<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

if( !isset($_POST['boardName']) || !isset($_POST['newBoardDescription']) || !isset($_SESSION['user_id'])) {die("Invalid Request");}

$board_name = $_POST['boardName'];
$new_description = $_POST['newBoardDescription'];

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

#UPDATE is_pinned status
$sql = "UPDATE boards
SET description = ?
WHERE name = ?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ss', $new_description, $board_name);
if (mysqli_stmt_execute($stmt)) {
    $conn->close();
    $return_text = array( 'message' => "Successfully Updated the Description");
    die(json_encode($return_text));
} else {
    $conn->close();
    die('Failed to execute the query.');
}
?>