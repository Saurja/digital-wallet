<?php

include("includes/config.php");
include("includes/classes/Constants.php"); 
include("includes/classes/Transactions.php"); 

include("includes/handlers/transaction-handler.php");

$transactions = new Transactions($con);


if(isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
} else {
    header("Location: register.php");
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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