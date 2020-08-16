<?php include("includes/header.php"); ?>

<!--    Send Money Handler  -->

<?php

include("includes/classes/Transactions.php"); 

$transactions = new Transactions($con);

function sanitizeSender($inputText) {
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    $inputText = ucfirst(strtolower($inputText));
    return $inputText;
}

if(isset($_POST['reqMoneyButton'])){
    
    $sender = $_SESSION['userLoggedIn'];
    $receiver = sanitizeSender($_POST['reqFrom']);
    $amount = $_POST['reqAmount'];
    
    $wasSuccessful = $transactions->reqCredits($sender, $receiver, $amount);

    if(!isset($wasSuccessful)){
        header("Location: requestmoney.php");
    }

}
?>


<!--    Website Code    -->

<!--    Request Money Form    -->
<div class="row justify-content-center my-5">
    <div class="col-6">
        <form class="reqCreditMoney" action="" method="POST">
            <div class="form-group ">
                <label for="reqFrom">Request From</label>
                <input type="text" class="form-control" id="reqFrom" name="reqFrom" placeholder="Enter Name...">
                <?php echo $transactions->getError(Constants::$usernameInvalid); ?>
                <?php echo $transactions->getError(Constants::$cantReqSelf); ?>
            </div>
            <div class="form-group">
                <label for="reqAmount">Amount</label>
                <input type="number" class="form-control" id="reqAmount" name="reqAmount" placeholder="Enter Amount...">
                <?php echo $transactions->getError(Constants::$InsufficientBalance); ?>
                <?php echo $transactions->getError(Constants::$amountLessthanOne); ?>
            </div>
            <div class="form-group">
                <button type="submit" name="reqMoneyButton" class="btn btn-primary">Request</button>
            </div>
        </form>
    </div>
</div>

<?php include("includes/footer.php"); ?>