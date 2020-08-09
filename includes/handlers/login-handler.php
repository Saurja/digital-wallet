<?php
    //login button was pressed
    if (isset($_POST['loginButton'])) {
        $email = $_POST['loginEmail'];
   
    //login function
    $loginSuccessful = $account->login($email);

    if($loginSuccessful) {
        $_SESSION['userLoggedIn'] = $email;
        header("Location: index.php");
    }
}
?>

    