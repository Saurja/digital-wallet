<?php include("includes/header.php"); ?>

<!--    Send Money Handler  -->
<?php

include("includes/classes/Transactions.php"); 
$transactions = new Transactions($con);
include("includes/handlers/transaction-handler.php");        
?>

<!--    Voucher View    -->
<div class="row justify-content-center mt-5">
    <!--    Form to Create Voucher  -->
    <div class="col-8">
        <div class="jumbotron">
            <div class="offset-1 col-10">
                <h1 class="display-5">Create Voucher</h1>
                <p class="lead">Create a voucher to send credits.</p>
                <form class="createVoucher mt-4" action="voucher.php" method="POST">
                    <div class="input-group">

                        <input type="number" class="form-control" id="voucherAmt" name="voucherAmt"
                            placeholder="Enter Amount...." aria-label="Enter Amount...."
                            aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button type="submit" name="createVoucherbutton" class="btn btn-primary mb-2">Create
                                ID</button>
                        </div>
                    </div>

                    <!--    Voucher Handler  -->
                    <?php
                    if(isset($_POST['createVoucherbutton'])){
                
                        $sender = $_SESSION['userLoggedIn'];
                        $amount = $_POST['voucherAmt'];
                        
                        $wasSuccessful = $transactions->generateVoucherID($sender, $amount);
                        if($wasSuccessful){
                            $logger->debug('Transaction: /Create_Voucher/ Successful. Voucher Amount: '.$amount.' | via account '.$sender);
                            header("Location: voucher.php");
                        }else{
                            $logger->debug('Transaction: /Create_Voucher/ Failed. Voucher Amount: '.$amount.' | via account '.$sender);
                        }
                    
                    }
                ?>
                    <!--    Voucher Handler End  -->
                    <?php echo $transactions->getError(Constants::$amountLessthanOne); ?>
                    <?php echo $transactions->getError(Constants::$InsufficientBalanceForReq); ?>
                </form>
            </div>
        </div>
    </div>
    <!--    Form to Create Voucher End  -->

    <!--    Form to Redeem Voucher  -->
    <div class="col-8">

        <div class="jumbotron">
            <div class="offset-1 col-10">
                <h1 class="display-5">Reedeem Voucher</h1>
                <p class="lead">Redeem a voucher to get credits.</p>
                <form class="redeemVoucher mt-4" action="voucher.php" method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control" id="voucherId" name="voucherId"
                            placeholder="Enter Voucher Id...." aria-label="Enter Voucher Id...."
                            aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button type="submit" name="reedeemVoucherbutton"
                                class="btn btn-primary mb-2">Redeem</button>
                        </div>
                    </div>
                </form>
                <?php echo $transactions->getError(Constants::$voucherCodeInvalid); ?>
                <?php echo $transactions->getSuccess(Constants::$VoucherRedeemed); ?>
            </div>
        </div>
    </div>
    <!--    Form to Redeem Voucher End  -->

    <!--    Voucher Table  -->
    
    <div class="col-8">
        <h3 class="mt-4">Vouchers Created</h3>
        <table class="table table-hover table-striped table-bordered table-hover ">
            <caption>These are the vouchers with their respective amount created by you.</caption>
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Voucher ID</th>
                    <th scope="col">Voucher Code</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sen = $_SESSION['userLoggedIn'];
                $VocuherQuery = mysqli_query($con, "SELECT `voucher_id`, user1.`email_id` AS `send_from`, voucher_amount, voucher_code
                FROM `voucher_table` t JOIN `user_details` user1
                ON t.`sender_id` = user1.`user_ID`
                WHERE user1.`email_id`='$sen'
                ORDER BY `voucher_id` DESC");
                ?>

                <?php
                if (mysqli_num_rows($VocuherQuery)==0) { 
                ?>
                <tr>
                    <td colspan="3">No vouchers to display here.</td>
                </tr>
                <?php
                }else{
                    while($row = mysqli_fetch_array($VocuherQuery)) {
                ?>
                <tr>
                    <th scope='row'><?php echo $row['voucher_id']; ?></th>
                    <td><?php echo $row['voucher_code']; ?></td>
                    <td><?php echo $row['voucher_amount']; ?></td>
                </tr>
                <?php
                }
                    }
            ?>
            </tbody>
        </table>
    </div>
</div>
<!--    Voucher View    -->
</div>

<!--    Stops form from resubmitting    -->
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<!--    Stops form from resubmitting    -->

<?php include("includes/footer.php"); ?>