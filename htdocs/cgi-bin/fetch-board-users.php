<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: /404'); 
    die();
}

if(!isset($_POST['boardId'])) {die("Invalid Request");}

$boardId = $_POST['boardId'];

$conn = new mysqli("localhost", "root", "", "COMP307-Project");
if ($conn->connect_error) {
    die("Internal Server Error: " . $conn->connect_error);
}

#SELECT ALL USER_ID FROM SPECIFIC BOARD_ID
$sql = "SELECT user_id FROM board_access WHERE board_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $boardId);

if (mysqli_stmt_execute($stmt)) {

    $result = mysqli_stmt_get_result($stmt);

    $userIds = array();  // Initialize an array to store user_ids

    while($row = mysqli_fetch_array($result)){
        $userIds[] = $row['user_id'];
    }

    $conn->close();

    die(json_encode(array('users' => $userIds)));
} else {
    $conn->close();
    die('Failed to execute the query.');
}
?>