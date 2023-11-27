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
        document.getElementById('registration-error').style.display = 'block';
        document.getElementById('random-error').style.display = 'block';
        document.getElementById('invalid-password').style.display = 'none';
        document.getElementById('invalid-email').style.display = 'none';
    } </script>"; 
}
if (isset($errors["invalid_email"])){
    echo "<script> window.onload = function () { 
        document.getElementById('registration-error').style.display = 'block';
        document.getElementById('invalid-email').style.display = 'block';
        document.getElementById('invalid-password').style.display = 'none';
        document.getElementById('random-error').style.display = 'none';
    } </script>"; 
}
if (isset($errors["invalid_password"])){
    echo "<script> window.onload = function () { 
        document.getElementById('registration-error').style.display = 'block';
        document.getElementById('invalid-email').style.display = 'none';
        document.getElementById('invalid-password').style.display = 'block';
        document.getElementById('random-error').style.display = 'none';
    } </script>"; 
}

require "register.html";
?>