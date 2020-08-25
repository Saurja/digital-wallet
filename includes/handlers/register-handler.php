<?php

    #   function to sanatize username input
    function sanatizeFormUsername($inputText) {

        $inputText = strip_tags($inputText);
        return str_replace(" ", "", $inputText);

    }

    #   function to sanatize email input
    function sanatizeFormString($inputText) {

        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return ucfirst(strtolower($inputText));

    }

    #   function to sanatize password input
    function sanatizeFormPassword($inputText) {

        return str_replace(" ", "", $inputText);

    }

    #   function to sanatize mobile number input
    function sanatizeFormMobile($inputText) {

        // Allow +, - and . in phone number
        $inputText = filter_var($inputText, FILTER_SANITIZE_NUMBER_INT);
        // Remove "-" from number
        return str_replace("-", "", $inputText);

    }

    #   when register button is pressed
    if (isset($_POST['registerButton'])) {

        $logger->info('Register button pressed...');
        //Register Button Was Pressed
        $username = sanatizeFormUsername($_POST['username']);
        $email = sanatizeFormString($_POST['email']);
        $email2 = sanatizeFormString($_POST['email2']);
        $mobile = sanatizeFormMobile($_POST['mobile']);

        $wasSuccessful = $account->register($username, $email, $email2, $mobile);

        if($wasSuccessful) {
            $_SESSION['userLoggedIn'] = $email;
            $logger->info('User registration was Successfull...');
            $logger->info('User Details : Username'.$username.' | Email: '.$email.' | Mobile: '.$mobile.' saved to the server....');
            header("Location: index.php");
        }else{
            $logger->error('OOPS! Something went wrong with the registration......');
        }
    }
?>