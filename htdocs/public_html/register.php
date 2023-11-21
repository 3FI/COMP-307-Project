<?php
    $email = $_POST['email'];
    $password = $_POST['password'];

    #TODO : Sanitize Both Against SQL Injection / Spam

    $password = password_hash($password, PASSWORD_DEFAULT);

    $conn = new mysqli("localhost", "root", "", "COMP307-Project");
    if ($conn->connect_error) {
        die("Internal Server Error: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM users WHERE email='".$email."'";
    $result = $conn->query($sql);

    if ($result->num_rows === 0) {
        $sql = "INSERT INTO users (email, password, username) VALUES ('".$email."','".$password."','".$email."')";
        
        $result = $conn->query($sql);
        if ($result === TRUE) {
            readfile("index.html");
            #TODO : Do better than a localhost pop-up
            echo "<script> window.onload = function () { alert('Your Account as Been Successfully Created Please Log-In'); } </script>"; 
        }
        else{
            readfile("register.html");
            #TODO : Do better than a localhost pop-up
            echo "<script> window.onload = function () { alert('An Error Happened Please Try Again'); } </script>"; 
        }
    }
    else {
        readfile("register.html");
            #TODO : Do better than a localhost pop-up
            echo "<script> window.onload = function () { alert('A user with this email is already registered'); } </script>"; 
    }

    
    $conn->close();
?>