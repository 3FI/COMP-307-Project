<?php
    $email = $_POST['email'];
    $password = $_POST['password'];

    #TODO : Sanitize Both Against SQL Injection

    $conn = new mysqli("localhost", "root", "", "COMP307-Project");
    if ($conn->connect_error) { die("Internal Server Error: " . $conn->connect_error); }

    $sql = "SELECT * FROM users WHERE email='".$email."'";
    $result = $conn->query($sql);

    if ($result->num_rows === 0) { $error = "Invalid Email";}
    else if ($result->num_rows > 1) {$error = "Multiple Accounts tied to this Email";}
    else {
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])){
            session_start();
            $_SESSION["user_id"] = $user['user_id'];
            readfile("select-discussion.html");
        }
        else{ $error = "Invalid Password"; }
    }
    
    if (isset($error)) {
        readfile("index.html");
        #TODO : Do better than a localhost pop-up
        echo "<script> window.onload = function () { alert('".$error."'); } </script>"; 
    }

    $conn->close();
?>