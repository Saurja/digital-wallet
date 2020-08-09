<?php

    ob_start();

    $timezone = date_default_timezone_set("Asia/Kolkata");
    $con = mysqli_connect("localhost", "root", "", "digital-wallet");

    if(mysqli_connect_errno()) {
        echo "Failed to Connect : " . mysqli_connect_errno() ;
    }

?>