<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
        header('Location: /404'); 
        die();
    }

    //ISSET CHECK
    if(!isset($_POST['email'])) {$errors["invalid_password"] = "Invalid Password";}
    if(!isset($_POST['password'])) {$errors["invalid_password"] = "Invalid Password";}
    if (isset($errors)) {
        $_SESSION['errors'] = $errors;
        $conn->close();
        header('Location: /login-form');
        die();
    }

    //SET VARIABLES
    $email = $_POST['email'];
    $password = $_POST['password'];

    //HASH PASSWORD
    $password = password_hash($password, PASSWORD_DEFAULT);

    $conn = new mysqli("localhost", "root", "", "COMP307-Project");
    if ($conn->connect_error) {
        die("Internal Server Error: " . $conn->connect_error);
    }
    
    //SELECT USER INFO
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    if (mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows === 0) {

            #CREATE USER ACCOUNT IN DB
            $sql = "INSERT INTO users (email, password, username) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sss', $email, $password,$email);
            $result = mysqli_stmt_execute($stmt);

            if ($result === TRUE) {
                $inputs["register_success"] = "Your Account as Been Successfully Created. Please Log-In";
                $_SESSION['inputs'] = $inputs;
                $conn->close();
                header('Location: /login-form');
                die();
            }
            else{
                $errors["unknown"] = "An unknown error happened. Please try again.";
            }
        }
        else {
            $errors["invalid_email"] = "Invalid Email";
        }
    }
    else{
        $errors["unknown"] = "An unknown error happened. Please try again.";
    }
    if (isset($errors)) {
        $_SESSION['errors'] = $errors;
        $conn->close();
        header('Location: /register-form');
        die();
    }
?>