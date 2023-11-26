<?php
    session_start();

    # TODO : DOUBLE CHECK IF INVALID INPUT
    if(!isset($_POST['email'])) {$errors["invalid_password"] = "Invalid Password";}
    if(!isset($_POST['password'])) {$errors["invalid_password"] = "Invalid Password";}
    if (isset($errors)) {
        $_SESSION['errors'] = $errors;
        $conn->close();
        header('Location: /login-form');
        die();
    }


    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $conn = new mysqli("localhost", "root", "", "COMP307-Project");
    if ($conn->connect_error) { die("Internal Server Error: " . $conn->connect_error); }

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    if (mysqli_stmt_execute($stmt)){  
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows === 0) { $errors["invalid_email"] = "Invalid Email";}
        else if ($result->num_rows > 1) {$errors["invalid_email"] = "Multiple Accounts tied to this Email";}
        else {
            $user = $result->fetch_assoc();
            if ($user && password_verify($password, $user['password'])){
                
                $_SESSION["user_id"] = $user['user_id'];

                #Generate ticket
                $min = 10000000;
                $max = 99999999; 
                $ticket = rand($min, $max);
 
                //store ticket in the DB
                $updateSql = "UPDATE users SET ticket = ? WHERE user_id = ?";
                $updateStmt = mysqli_prepare($conn, $updateSql);
                mysqli_stmt_bind_param($updateStmt, 'ii', $ticket, $user['user_id']);
                mysqli_stmt_execute($updateStmt);

                //store ticket in $_SESSION
                $_SESSION["SESSION_ticket"] = $ticket;
 
                header('Location: /select-discussion');

            }
            else{ $errors["invalid_password"] = "Invalid Password"; }
        }
    }
    else{
        $errors["invalid_password"] = "Invalid Password";
    }

    if (isset($errors)) {
        $_SESSION['errors'] = $errors;
        $conn->close();
        header('Location: /login-form');
        die();
    }
?>