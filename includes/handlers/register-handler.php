<?php

    function sanatizeFormUsername($inputText) {

        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;

    }

    function sanatizeFormString($inputText) {

        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        $inputText = ucfirst(strtolower($inputText));
        return $inputText;

    }

    function sanatizeFormPassword($inputText) {

        $inputText = str_replace(" ", "", $inputText);
        return $inputText;

    }

    function sanatizeFormMobile($inputText) {

        // Allow +, - and . in phone number
        $inputText = filter_var($inputText, FILTER_SANITIZE_NUMBER_INT);
        // Remove "-" from number
        $inputText = str_replace("-", "", $inputText);
        return $inputText;

    }

    if (isset($_POST['registerButton'])) {

        //Register Button Was Pressed
        $username = sanatizeFormUsername($_POST['username']);
        $email = sanatizeFormString($_POST['email']);
        $email2 = sanatizeFormString($_POST['email2']);
        $mobile = sanatizeFormMobile($_POST['mobile']);

        $wasSuccessful = $account->register($username, $email, $email2, $mobile);

        if($wasSuccessful) {
            $_SESSION['userLoggedIn'] = $email;
            header("Location: index.php");
        }
    }
?>