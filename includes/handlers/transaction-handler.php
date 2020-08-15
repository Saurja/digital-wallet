<?php

    function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        $inputText = ucfirst(strtolower($inputText));
        return $inputText;
    }

    if(isset($_POST['sendMoneyButton'])){
        
        $sender = $_SESSION['userLoggedIn'];
        $receiver = sanitizeFormString($_POST['sendTo']);
        $amount = $_POST['sendAmount'];
        
        $wasSuccessful = $account->sendMoney($sender, $receiver, $amount);

    }
?>