<?php
    //login button was pressed
    if (isset($_POST['loginButton'])) {
        $email = $_POST['loginEmail'];
   
    //login function
    $loginSuccessful = $account->login($email);

    if($loginSuccessful) {
        header("Location: index.php");
    }
}
?>

    