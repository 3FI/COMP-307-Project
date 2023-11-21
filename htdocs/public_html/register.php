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

        #set default username to email
        $sql = "INSERT INTO users (email, password, username) VALUES ('".$email."','".$password."','".$email."')";
        
        $result = $conn->query($sql);
        if ($result === TRUE) {
            readfile("index.html");
            #Unhide message if user was created successfully
            echo "<script>
                    window.onload = function () {
                        document.getElementById('successMessage').style.display = 'block';
                    }
             </script>"; 
        }
        else{
            readfile("register.html");
            #Unhide error message
            echo "<script> window.onload = function () { 
                document.getElementById('random_error').style.display = 'block';
            } </script>"; 
        }
    }
    else {
        readfile("register.html");
            #Unhide unavailable email message
            echo "<script> window.onload = function () { 
                document.getElementById('unavailable_email').style.display = 'block';
             } </script>"; 
    }

    
    $conn->close();
?>