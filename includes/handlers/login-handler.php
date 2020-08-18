<?php
    //login button was pressed
    if (isset($_POST['loginButton'])) {
        $email = $_POST['loginEmail'];
   
    //login function

    $email = sanatizeFormString($email);
    
    $loginSuccessful = $account->login($email);

    $email = sanatizeFormString($email);

    if($loginSuccessful) {
        $_SESSION['userLoggedIn'] = $email;
        header("Location: index.php");
    }
}
?>

    