<?php
    session_start();
    session_unset();
    session_destroy();
    readfile("index.html");
    #TODO : Do better than a localhost pop-up
    echo "<script> window.onload = function () { alert('You have been properly logged-out'); } </script>"; 
?>