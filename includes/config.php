<?php
    ob_start();
    session_start();
    $timezone = date_default_timezone_set("Asia/Kolkata");
    try {
        $con = mysqli_connect("localhost", "root", "", "digital-wallet");
    } catch (Exception $e) {
        die("Unable to connect: " . $e->getMessage());
    }
    if(mysqli_connect_errno()) {
        echo "Failed to Connect : " . mysqli_connect_errno() ;
    }
?>