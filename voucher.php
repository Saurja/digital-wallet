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

if(isset($_POST['createVoucherbutton'])){
    
    $sender = $_SESSION['userLoggedIn'];
    $amount = $_POST['voucherAmt'];
    
    $wasSuccessful = $transactions->generateVoucherID($sender, $amount);
    echo $wasSuccessful;
    if(!isset($wasSuccessful)){
        header("Location: voucher.php");
    }
}

if(isset($_POST['reedeemVoucherbutton'])){
    
    $sender = $_SESSION['userLoggedIn'];
    $voucherId = $_POST['voucherId'];
    
    $wasSuccessful = $transactions->redeemVoucherID($sender, $voucherId);

    if(!isset($wasSuccessful)){
        #header("Location: voucher.php");
    }
}


?>

<div class="row justify-content-center mt-5">
    <div class="col-6">  
        <!--    Form to Create Voucher  -->
        <form class="createVoucher" action="voucher.php" method="POST">
            <label for="exampleInput">Create Voucher</label>
            <div class="input-group">
                
                <input type="number" class="form-control" id="voucherAmt" name="voucherAmt" placeholder="Enter Amount...." aria-label="Enter Amount...." aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button type="submit" name="createVoucherbutton" class="btn btn-primary mb-2">Create ID</button>
                </div>
            </div>
            <?php echo $transactions->getError(Constants::$amountLessthanOne); ?>
            <?php echo $transactions->getError(Constants::$InsufficientBalanceForReq); ?>
        </form>

        <!--    Form to Redeem Voucher  -->
        <form class="redeemVoucher" action="voucher.php" method="POST">
            <label for="exampleInput">Reedeem Voucher</label>
            <div class="input-group">
                
                <input type="text" class="form-control" id="voucherId" name="voucherId" placeholder="Enter Voucher Id...." aria-label="Enter Voucher Id...." aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button type="submit" name="reedeemVoucherbutton" class="btn btn-primary mb-2">Redeem</button>
                </div>
                
            </div>
            
        </form>
        <?php echo $transactions->getError(Constants::$voucherCodeInvalid); ?>
    </div>
</div>

<?php include("includes/footer.php"); ?>
