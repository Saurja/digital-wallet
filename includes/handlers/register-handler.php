<?php

    function sanatizeFormUsername($inputText) {

        $inputText = strip_tags($inputText);
        return str_replace(" ", "", $inputText);

    }

    function sanatizeFormString($inputText) {

        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return ucfirst(strtolower($inputText));

    }

    function sanatizeFormPassword($inputText) {

        return str_replace(" ", "", $inputText);

    }

    function sanatizeFormMobile($inputText) {

        // Allow +, - and . in phone number
        $inputText = filter_var($inputText, FILTER_SANITIZE_NUMBER_INT);
        // Remove "-" from number
        return str_replace("-", "", $inputText);

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