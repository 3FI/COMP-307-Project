<?php
echo("test");

session_start();

//ISSET CHECK
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
if (isset($_SESSION['inputs'])) {
    $inputs = $_SESSION['inputs'];
    unset($_SESSION['inputs']);
}

if (isset($errors["unknown"])){
    echo "<script> window.onload = function () { 
        document.getElementById('duplicateEmail').style.display = 'none';
        document.getElementById('invalidPassword').style.color = '#a6a6a6';
        document.getElementById('invalidEmail').style.display = 'block';
    } </script>"; 
}
if (isset($errors["invalid_email"])){
    echo "<script> window.onload = function () { 
        document.getElementById('invalidEmail').style.display = 'none';
        document.getElementById('invalidPassword').style.color = '#a6a6a6';
        document.getElementById('duplicateEmail').style.display = 'block';
    } </script>"; 
}
if (isset($errors["invalid_password"])){
    echo "<script> window.onload = function () { 
        document.getElementById('invalidEmail').style.display = 'none';
        document.getElementById('duplicateEmail').style.display = 'none';
        document.getElementById('invalidPassword').style.color = '#dc3545';
    } </script>"; 
}

require "./register.html";
?>