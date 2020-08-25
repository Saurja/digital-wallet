<?php

include("includes/config.php");
include("includes/classes/Constants.php"); 

if(isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
    if(time()-$_SESSION["login_time_stamp"] >600)   
    { 
        session_unset(); 
        session_destroy();
        mysqli_close($con);  
        header("Location:register.php"); 
    } 
} else {
    header("Location: register.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Digital Wallet is a payment application that allows users to store and spend virtual money. This project does not use real money and only for educational purposes." />
    <title>Digital Wallet</title>

    <!--    Adds Bootstrap links (Bootstrap / Jquery / Popper.js)    -->
    <?php include("includes/bootstrap.php"); ?>

    <!--    Adds CSS links      -->
    <link rel="stylesheet" href="assets/css/style.css">



</head>

<body>
    <!--    Adds Navigation Bar     -->
    <?php include("includes/navbarComponent.php"); ?>
    <!--    Adds Sidebar     -->
    <div id="mainContainer">
        <?php include("includes/sidebarComponent.php"); ?>
        <div class="mainViewContainer">
            <div class="mainContent">