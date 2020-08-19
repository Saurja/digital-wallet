<?php include("includes/header.php"); ?>

<!--    Send Money Handler  -->
<?php

include("includes/classes/Transactions.php"); 

$transactions = new Transactions($con);

function sanitizeSender($inputText) {
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    return ucfirst(strtolower($inputText));
}

if(isset($_POST['sendMoneyButton'])){
    
    $sender = $_SESSION['userLoggedIn'];
    $receiver = sanitizeSender($_POST['sendTo']);
    $amount = $_POST['sendAmount'];
    
    $wasSuccessful = $transactions->sendcredits($sender, $receiver, $amount);

}

if (isset($_GET['send_task'])) {
    
    $requestID = $_GET['send_task'];
    //  Get the email of user logged in
    $sender = $_SESSION['userLoggedIn'];
    
    $reqCreditQuery = mysqli_query($con, "SELECT `req_id`, user1.`email_id` AS `req_from`, user2.`email_id` AS `send_from`, `credits_requested`, `req_dateTime` 
    FROM `credit_requests` t JOIN `user_details` user1
    ON t.`req_from` = user1.`user_ID`
    JOIN `user_details` user2 
    ON t.`send_from` = user2.`user_ID`
    WHERE user2.`email_id`='$sender'");

    while($row = mysqli_fetch_array($reqCreditQuery)) {

        if($row['req_id'] == $requestID) {
            $receiver= $row['req_from'];
            $amount = $row['credits_requested'];
            $wasSuccess = $transactions->sendRequestedcredits($sender, $receiver, $amount);
            if(!isset($wasSuccess)) {
                $transactions->deleteRowWithID($requestID);
            }
        }
        
    }
}


if (isset($_GET['del_task'])) {
    
    $requestID = $_GET['del_task'];
    //  Get the email of user logged in
    $sender = $_SESSION['userLoggedIn'];
    
    $reqCreditQuery = mysqli_query($con, "SELECT `req_id`, user1.`email_id` AS `req_from`, user2.`email_id` AS `send_from`, `credits_requested`, `req_dateTime` 
    FROM `credit_requests` t JOIN `user_details` user1
    ON t.`req_from` = user1.`user_ID`
    JOIN `user_details` user2 
    ON t.`send_from` = user2.`user_ID`
    WHERE user2.`email_id`='$sender'");

    while($row = mysqli_fetch_array($reqCreditQuery)) {


        if($row['req_id'] == $requestID) {
            $transactions->deleteRowWithID($requestID);
        }
        
    }
}

?>

<!--    Send Money Handler End  -->


<!--    Website Code    -->

<!--    Send Money Form    -->
<div class="row justify-content-center my-5">
    <div class="col-6">
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
                <button type="submit" name="sendMoneyButton" class="btn btn-primary">Send</button>
            </div>
        </form>
        <?php echo $transactions->getSuccess(Constants::$CreditsSent); ?>
        <?php echo $transactions->getError(Constants::$TranscErr); ?>
    </div>
</div>
<!--    Send Money Form End    -->

<!--    Display Request table    -->
<div class="row justify-content-center my-5">
    <div class="col-8"> 
    <h3>Transfer Requests</h3>
    <?php echo $transactions->getError(Constants::$InsufficientBalanceForReq); ?>
        <table class="table table-striped table-bordered mt-2 text-center">
        <caption>The following users have requested credits. Press "Pay Now" to send.</caption>
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
}

</script>
<!--    Stops form from resubmitting    -->
<?php include("includes/footer.php"); ?>
