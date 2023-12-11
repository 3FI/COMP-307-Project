<?php
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

if (isset($errors["invalid_email"])){
    echo "<script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>";
    echo "<script> $(document).ready(function() {
        $('#registrationError').show();
        $('#invalidEmail').show();
        $('#invalidPassword').hide();
     }); </script>"; 
} 
if (isset($errors["invalid_password"])){
    echo "<script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>";
    echo "<script> $(document).ready(function() {
        $('#registrationError').show();
        $('#invalidEmail').hide();
        $('#invalidPassword').show();
     }); </script>"; 
}

if (isset($inputs["register_success"])){
    echo "<script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>";
    echo "<script> $(document).ready(function() {
        $('#successMessage').show();
    }); </script>"; 
}
require "./index.html";
?>