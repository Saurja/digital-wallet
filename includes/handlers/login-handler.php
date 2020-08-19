<?php
    //login button was pressed
    if (isset($_POST['loginButton'])) {
        $email = $_POST['loginEmail'];
   
    //login function

   

    $email = sanatizeFormString($email);
    $loginSuccessful = $account->login($email);
    mysqli_query($con, "UPDATE `user_details` SET `counter`=`counter`+1 WHERE `email_id` = '$email'");
    $email = sanatizeFormString($email);

    if($loginSuccessful) {
        $_SESSION['userLoggedIn'] = $email;
        header("Location: index.php");
    }
}
?>

    