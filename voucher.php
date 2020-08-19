<?php include("includes/header.php"); ?>

<!--    Send Money Handler  -->
<?php

include("includes/classes/Transactions.php"); 

$transactions = new Transactions($con);

    if(isset($_POST['reedeemVoucherbutton'])){

        $sender = $_SESSION['userLoggedIn'];
        $voucherId = $_POST['voucherId'];
        
        $wasSuccessful = $transactions->redeemVoucherID($sender, $voucherId);

    }
        
?>

<!--    Voucher View    -->
<div class="row justify-content-center mt-5">
    <div class="col-6">
        <!--    Form to Create Voucher  -->
        <form class="createVoucher" action="voucher.php" method="POST">
            <label for="exampleInput">Create Voucher</label>
            <div class="input-group">

                <input type="number" class="form-control" id="voucherAmt" name="voucherAmt"
                    placeholder="Enter Amount...." aria-label="Enter Amount...." aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button type="submit" name="createVoucherbutton" class="btn btn-primary mb-2">Create ID</button>
                </div>
            </div>
            <!--    Voucher Handler  -->
            <?php
        
                if(isset($_POST['createVoucherbutton'])){
            
                    $sender = $_SESSION['userLoggedIn'];
                    $amount = $_POST['voucherAmt'];
                    
                    $wasSuccessful = $transactions->generateVoucherID($sender, $amount);
                    echo $wasSuccessful;
                
                }
            
            ?>
            <!--    Voucher Handler End  -->
            <?php echo $transactions->getError(Constants::$amountLessthanOne); ?>
            <?php echo $transactions->getError(Constants::$InsufficientBalanceForReq); ?>
        </form>
        <!--    Form to Create Voucher End  -->



        <!--    Form to Redeem Voucher  -->
        <form class="redeemVoucher" action="voucher.php" method="POST">
            <label for="exampleInput">Reedeem Voucher</label>
            <div class="input-group">

                <input type="text" class="form-control" id="voucherId" name="voucherId"
                    placeholder="Enter Voucher Id...." aria-label="Enter Voucher Id...."
                    aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button type="submit" name="reedeemVoucherbutton" class="btn btn-primary mb-2">Redeem</button>
                </div>

            </div>

        </form>
        <?php echo $transactions->getError(Constants::$voucherCodeInvalid); ?>
        <?php echo $transactions->getSuccess(Constants::$VoucherRedeemed); ?>
        <!--    Form to Redeem Voucher End  -->
    </div>
</div>
<!--    Voucher View    -->

<!--    Stops form from resubmitting    -->
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

$(function() {
  $("refresh").click(function() {
     $("#navbarNav").load("voucher.php")
  })
})

</script>
<!--    Stops form from resubmitting    -->

<?php include("includes/footer.php"); ?>