<?php include("includes/header.php"); ?>

<!--    Send Money Handler  -->
<?php

include("includes/classes/Transactions.php"); 

$transactions = new Transactions($con);

include("includes/handlers/transaction-handler.php"); 

?>


<!--    Send Money Handler End  -->


<!--    Website Code    -->

<!--    Send Money Form    -->

<div class="row justify-content-center my-5">
    
    <div class="col-8">
    <?php echo $transactions->getSuccess(Constants::$CreditsSent); ?>
    <div class="jumbotron ">
    <div class="offset-1 col-10">
    <h1 class="display-5 mb-5">Send Money</h1>
    <p class="lead">Send credits to a intended user via his email id.</p>
        <?php echo $transactions->getError(Constants::$TranscErrSend); ?>
        <?php echo $transactions->getError(Constants::$TranscErrHistory); ?>
        
        <form class="sendCreditMoney" action="index.php" method="POST">
            <div class="form-group ">
                <label for="sendTo">Send To</label>
                <input type="text" class="form-control" id="sendTo" name="sendTo" placeholder="Enter Email...">
                <?php echo $transactions->getError(Constants::$usernameInvalid); ?>
                <?php echo $transactions->getError(Constants::$cantSendSelf); ?>
            </div>
            <div class="form-group">
                <label for="sendAmount">Amount</label>
                <input type="number" class="form-control" id="sendAmount" name="sendAmount" placeholder="Enter Amount...">
                <?php echo $transactions->getError(Constants::$InsufficientBalance); ?>
                <?php echo $transactions->getError(Constants::$amountLessthanOne); ?>
            </div>
            <div class="form-group">
                <button id="sendMoneyButton" type="submit" name="sendMoneyButton" class="btn btn-primary">Send</button>
            </div>
        </form>
        </div>  
    </div>
    </div>

</div>
<!--    Send Money Form End    -->

<!--    Display Request table    -->
<div class="row justify-content-center my-5">
    <div class="col-8"> 
    <h3>Transfer Requests</h3>
    <?php echo $transactions->getError(Constants::$InsufficientBalanceForReq); ?>
    <?php echo $transactions->getSuccess(Constants::$RequestDeleted); ?>
        <table class="table table-striped table-bordered mt-2 text-center">
        <caption>The following users have requested credits. Press "Pay Now" to send or Press "Del" to Cancel.</caption>
            <thead>
                <tr>
                    <th scope="col">Req ID</th>
                    <th scope="col">Who</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Req-Date</th>
                    <th scope="col">Response</th>
                </tr>
            </thead>
            <tbody>

            <!--    List All Requests   -->
            <?php
                //  Get the email of user logged in
                $sender = $_SESSION['userLoggedIn'];
                //  Select all tasks if page is visited or refreshed
                $reqCreditQuery = mysqli_query($con, "SELECT `req_id`, user1.`email_id` AS `req_from`, user2.`email_id` AS `send_from`, `credits_requested`, `req_dateTime` 
                FROM `credit_requests` t JOIN `user_details` user1
                ON t.`req_from` = user1.`user_ID`
                JOIN `user_details` user2 
                ON t.`send_from` = user2.`user_ID`
                WHERE user2.`email_id`='$sender'
                ORDER BY `req_id` DESC");
            ?>
            
            <?php
            if (mysqli_num_rows($reqCreditQuery)==0) { 
            ?> 
                <tr>
                    <td colspan="5">No requests to display here.</td>
                </tr>
            <?php
            }else{
                while($row = mysqli_fetch_array($reqCreditQuery)) {
            ?>
                <tr>
                    <th scope='row'><?php echo $row['req_id']; ?></th>
                    <td>From <?php echo  $row['req_from']; ?></td>
                    <td><?php echo $row['credits_requested']; ?> Points Requested</td>
                    <td><?php echo $row['req_dateTime']; ?></td>
                    <td>
                        <a class="mx-1" href="index.php?send_task=<?php echo $row['req_id'] ?>">
                            <button type='submit' class='btn btn-dark'>Pay Now</button>
                        </a>
                        <a class="mx-1" href="index.php?del_task=<?php echo $row['req_id'] ?>">
                            <button type='submit' class='btn btn-danger'>Del</button>
                        </a>
                    </td>
                </tr>
            <?php
                }
                    }
            ?>
            </tbody>
        </table>
    </div>
</div>
<!--    Display Request table End    -->

<!--    Stops form from resubmitting    -->
<script>
    
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
};

</script>
<!--    Stops form from resubmitting    -->
<?php include("includes/footer.php"); ?>
