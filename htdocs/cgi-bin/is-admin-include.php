<?php

/*  
REQUIRED IN MOST PHP FILE FOR AN ADDITIONAL TICKET CHECK
BEFORE CONNECTING TO THE DB
*/

$conn = new mysqli("mysql.cs.mcgill.ca", "ecadot", "n#K#p6CEVw2USkJVFNDyetUb", "2023fall-comp307-ecadot");
if ($conn->connect_error) {
    $is_valid = false;
}

//VERIFY ADMIN ACCESS
$sql = "SELECT * FROM boards WHERE admin_id=? and id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $userId, $boardId);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows !== 1) {
        $is_valid = false;
    }
    else{
        $is_valid = true;
    }
}
else{
    $is_valid = false;
}

$conn->close();
?>