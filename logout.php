<?php

    session_start();
    mysqli_close($con); 
    unset($_SESSION['userLoggedIn']);
    header("Location: register.php");

?>