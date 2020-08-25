<?php

    #   function to sanitize email Id sent via form input
    function sanitizeSender($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return ucfirst(strtolower($inputText));
    }

    function sanitizevoucher($inputText) {
        $inputText = strip_tags($inputText);
        return str_replace(" ", "", $inputText);
        
    }

    #   when Send Money button is pressed
    if(isset($_POST['sendMoneyButton'])){
        
        $sender = $_SESSION['userLoggedIn'];
        $receiver = sanitizeSender($_POST['sendTo']);
        $amount = $_POST['sendAmount'];
        
        $wasSuccessful = $transactions->sendcredits($sender, $receiver, $amount);

        if($wasSuccessful){
            $logger->debug('Transaction Type: /Send_Money/ Successful. '.$amount.' has been sent to '.$receiver.' via account '.$sender);
            header("Location: index.php");
        }else{
            $logger->debug('Transaction Type: /Send_Money/ Failed. '.$amount.' has been reverted to account '.$sender);
        }

    }

    #   when Pay Requested Credit Button is pressed
    if (isset($_GET['send_task'])) {
        
        $requestID = $_GET['send_task'];
        //  Get the email of user logged in
        $sender = $_SESSION['userLoggedIn'];
        
        $reqCreditQuery = mysqli_query($con, "SELECT `req_id`, user1.`email_id` AS `req_from`, user2.`email_id` AS `send_from`, `credits_requested`, `req_dateTime` 
        FROM `credit_requests` t JOIN `user_details` user1
        ON t.`req_from` = user1.`user_ID`
        JOIN `user_details` user2 
        ON t.`send_from` = user2.`user_ID`
        WHERE user2.`email_id`='$sender'");

        while($row = mysqli_fetch_array($reqCreditQuery)) {

            if($row['req_id'] == $requestID) {
                $receiver= $row['req_from'];
                $amount = $row['credits_requested'];
                $requestmade =    $transactions->sendRequestedcredits($sender, $receiver, $amount);
                if($requestmade){
                    $transactions->deleteRowWithID($requestID);
                    $logger->debug('Transaction: /Send_Requested_Money/ Successful. '.$amount.' has been sent to '.$receiver.' via account '.$sender);
                    header("Location: index.php");
                }else{
                    $logger->debug('Transaction Type: /Send_Money/ Failed. '.$amount.' has been reverted to account '.$sender);
                }

            }
        
        }
    }

    #   when Delete Requested Money button is pressed
    if (isset($_GET['del_task'])) {
        
        $requestID = $_GET['del_task'];
        //  Get the email of user logged in
        $sender = $_SESSION['userLoggedIn'];
        
        $reqCreditQuery = mysqli_query($con, "SELECT `req_id`, user1.`email_id` AS `req_from`, user2.`email_id` AS `send_from`, `credits_requested`, `req_dateTime` 
        FROM `credit_requests` t JOIN `user_details` user1
        ON t.`req_from` = user1.`user_ID`
        JOIN `user_details` user2 
        ON t.`send_from` = user2.`user_ID`
        WHERE user2.`email_id`='$sender'");

        while($row = mysqli_fetch_array($reqCreditQuery)) {
            if($row['req_id'] == $requestID) {
                $transactions->deleteRowWithID($requestID);
                $logger->debug('Transaction: /Delete_Requested_Money/ Successful. Request ID: ' .$requestID. ' | via account '.$sender);
                array_push($transactions->SuccessArray, Constants::$RequestDeleted);
            }else{
                $logger->debug('Transaction: /Delete_Requested_Money/ Failed. Request ID: ' .$requestID. ' | via account '.$sender);
            }
            
        }
    }

    #   when Request Money button is pressed
    if(isset($_POST['reqMoneyButton'])){
        
        $sender = $_SESSION['userLoggedIn'];
        $receiver = sanitizeSender($_POST['reqFrom']);
        $amount = $_POST['reqAmount'];
        
        $wasSuccessful = $transactions->reqCredits($sender, $receiver, $amount);

        if($wasSuccessful){
            $logger->debug('Transaction: /Request_Money/ Successful. Request Amount: ' .$amount. ' | From: ' .$receiver. ' | via account '.$sender);
        }else{
            $logger->debug('Transaction: /Request_Money/ Failed. via account '.$sender);

        }

    }

    #   when Reedeem Voucher button is pressed
    if(isset($_POST['reedeemVoucherbutton'])){

        $sender = $_SESSION['userLoggedIn'];
        $voucherId = $_POST['voucherId'];
        $voucherId = sanitizevoucher($voucherId);
        
        $wasSuccessful = $transactions->redeemVoucherID($sender, $voucherId);
        if($wasSuccessful){
            $logger->debug('Transaction: /Reedeem_Voucher/ Successful. Voucher Code: '.$voucherId.' | via account '.$sender);
            header("Location: voucher.php");
        }else{
            $logger->debug('Transaction: /Reedeem_Voucher/ Failed. Voucher Code: '.$voucherId.' | via account '.$sender);
        }

    }

?>