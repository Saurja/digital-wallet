<?php

    #   when login button is pressed
    if (isset($_POST['loginButton'])) {
        $email = $_POST['loginEmail'];
   
    #   Code to login user and reditect it to index.php i.e the main page
    $email = sanatizeFormString($email);
    $loginSuccessful = $account->login($email);
    mysqli_query($con, "UPDATE `user_details` SET `counter`=`counter`+1 WHERE `email_id` = '$email'");
    if($loginSuccessful) {
        $_SESSION['userLoggedIn'] = $email;
        header("Location: index.php");
    }
}
?>

    