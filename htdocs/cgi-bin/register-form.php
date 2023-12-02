<?php
session_start();
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
        document.getElementById('registrationError').style.display = 'block';
        document.getElementById('invalidPassword').style.display = 'none';
        document.getElementById('invalidEmail').style.display = 'none';
    } </script>"; 
}
if (isset($errors["invalid_email"])){
    echo "<script> window.onload = function () { 
        document.getElementById('registrationError').style.display = 'block';
        document.getElementById('invalidEmail').style.display = 'block';
        document.getElementById('invalidPassword').style.display = 'none';
    } </script>"; 
}
if (isset($errors["invalid_password"])){
    echo "<script> window.onload = function () { 
        document.getElementById('registrationError').style.display = 'block';
        document.getElementById('invalidEmail').style.display = 'none';
        document.getElementById('invalidPassword').style.display = 'block';
    } </script>"; 
}

require "register.html";
?>