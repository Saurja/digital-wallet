<!--    PHP Code    -->
<?php 

#   MySQL Database Config File
include("includes/config.php"); 

#   PHP Classes Links
include("includes/classes/Account.php"); 
include("includes/classes/Constants.php"); 

$account = new Account($con);

#   PHP Handlers Links
include("includes/handlers/register-handler.php");
include("includes/handlers/login-handler.php"); 

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

?>

<!--    Website Code    -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Digital Wallet</title>

        <!--    Add Bootstrap links (Bootstrap / Jquery / Popper.js)-->
        <?php include("includes/bootstrap.php"); ?>

        <link rel="stylesheet" href="assets/css/register.css">

    </head>
    <body>
        <div class="from-group loginFormHeading mb-5">
            <h1>Digital Wallets</h1>
        </div>

        <!--    Login Form  -->
        <div class="row justify-content-center h-50 align-items-center">
            <div class="col-6">
                <h2>Login to your account</h2>
                <form id="walletLoginForm" action="register.php" method="POST">
                    <div class="from-group loginFormHeading">
                        
                    </div>
                    <div class="form-group">
                        <label for="loginEmail">Enter Email id:</label><br>
                        <input id="loginEmail" name="loginEmail" class="form-control" type="email"  placeholder="johnDoe@gmail.com" required>
                        <?php echo $account->getError(Constants::$loginFailed); ?>
                    </div>
                    <button type="submit" name="loginButton" class="btn btn-primary">LOGIN</button>
                </form>
            </div>
        </div>
        <!--    Login Form End      -->

        <!--    Register Form   -->
        <div class="row justify-content-center h-50 align-items-center">
            <div class="col-6">
                <h2>Register a new account</h2>
                <form id="walletLoginForm" action="register.php" method="POST">
                    <div class="form-group">

                        <label for="username">Enter Username:</label><br>
                        <input id="username" name="username" class="form-control" type="text" value="<?php getInputValue('username') ?>"  placeholder="johnDoe" required>
                        <?php echo $account->getError(Constants::$UsernameCharecters); ?>
                        <?php echo $account->getError(Constants::$usernameTaken); ?>

                        <label for="email">Enter Email id:</label><br>
                        <input id="email" name="email" class="form-control" type="email" value="<?php getInputValue('email') ?>"  placeholder="johnDoe@gmail.com" required>

                        <label for="email2">Confirm Email id:</label><br>
                        <input id="email2" name="email2" class="form-control" type="email" value="<?php getInputValue('email2') ?>"  placeholder="johnDoe@gmail.com" required>
                        <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
                        <?php echo $account->getError(Constants::$emailsNotValid); ?>
                        <?php echo $account->getError(Constants::$emailTaken); ?>

                        <label for="mobile">Enter Mobile Number:</label><br>
                        <input id="mobile" name="mobile" class="form-control" type="tel" value="<?php getInputValue('mobile') ?>"  placeholder="+918555751171" required>

                    </div>
                    <button type="submit" name="registerButton" class="btn btn-primary">SIGN UP</button>
                </form>
            </div>
        </div>
        <!--    Register Form End   -->
    
    </body>
</html>