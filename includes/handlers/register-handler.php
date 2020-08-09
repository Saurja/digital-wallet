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

    if (isset($_POST['registerButton'])) {

        //Register Button Was Pressed
        $username = sanatizeFormUsername($_POST['username']);
        $email = sanatizeFormString($_POST['email']);
        $email2 = sanatizeFormString($_POST['email2']);

        $wasSuccessful = $account->register($username, $email, $email2);

        if($wasSuccessful) {
            header("Location: index.php");
        }
    }
?>