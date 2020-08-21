<?php
    ob_start();
    session_start();
    $timezone = date_default_timezone_set("Asia/Kolkata");
    $con = mysqli_connect("sql7.freemysqlhosting.net", "sql7361593", "PLbzbqNpzK", "sql7361593");
    if(mysqli_connect_errno()) {
        echo "Failed to Connect : " . mysqli_connect_errno() ;
    }
?>