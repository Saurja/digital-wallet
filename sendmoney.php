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

if(isset($_POST['sendMoneyButton'])){
    
    $sender = $_SESSION['userLoggedIn'];
    $receiver = sanitizeSender($_POST['sendTo']);
    $amount = $_POST['sendAmount'];
    
    $wasSuccessful = $transactions->sendcredits($sender, $receiver, $amount);

    if(!isset($wasSuccessful)){
        header("Location: sendmoney.php");
    }

}

if (isset($_GET['send_task'])) {
    
    $requestID = $_GET['send_task'];
    //  Get the email of user logged in
    $sender = $_SESSION['userLoggedIn'];
    //  Select all tasks if page is visited or refreshed
    $reqCreditQuery = mysqli_query($con, "SELECT * FROM credit_requests WHERE send_from='$sender'");

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

?>

<!--    Send Money Handler End  -->


<!--    Website Code    -->

<!--    Send Money Form    -->
<div class="row justify-content-center my-5">
    <div class="col-6">
        <form class="sendCreditMoney" action="sendMoney.php" method="POST">
            <div class="form-group ">
                <label for="sendTo">Send To</label>
                <input type="text" class="form-control" id="sendTo" name="sendTo" placeholder="Enter Name...">
                <?php echo $transactions->getError(Constants::$usernameInvalid); ?>
                <?php echo $transactions->getError(Constants::$cantSendSelf); ?>
            </div>
            <div class="form-group">
                <label for="sendAmount">Amount</label>
                <input type="number" class="form-control" id="sendAmount" name="sendAmount" placeholder="Enter Amount...">
                <?php echo $transactions->getError(Constants::$InsufficientBalance); ?>
                <?php echo $transactions->getError(Constants::$amountLessthanZero); ?>
            </div>
            <div class="form-group">
                <button type="submit" name="sendMoneyButton" class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
</div>
<!--    Send Money Form End    -->

<!--    Display Request table    -->
<div class="row justify-content-center my-5">
    <div class="col-10"> 
    <h3>Transfer Requests</h3>
    <?php echo $transactions->getError(Constants::$InsufficientBalanceForReq); ?>
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th scope="col">Req ID</th>
                    <th scope="col">Who</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Req-Date</th>
                    <th scope="col">Responsce</th>
                </tr>
            </thead>
            <tbody>

            <!--    List All Requests   -->
            <?php
                //  Get the email of user logged in
                $sender = $_SESSION['userLoggedIn'];
                //  Select all tasks if page is visited or refreshed
                $reqCreditQuery = mysqli_query($con, "SELECT * FROM credit_requests WHERE send_from='$sender'");
                while($row = mysqli_fetch_array($reqCreditQuery)) {
            ?>        
                    <tr>
                        <th scope='row'><?php echo $row['req_id']; ?></th>
                        <td>From <?php echo  $row['req_from']; ?></td>
                        <td><?php echo $row['credits_requested']; ?> Points Requested</td>
                        <td><?php echo $row['req_dateTime']; ?></td>
                        <td>
                            <a href="sendMoney.php?send_task=<?php echo $row['req_id'] ?>">
                                <button type='submit' class='btn btn-dark'>Pay Now</button>
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
<!--    Display Request table    -->

<?php include("includes/footer.php"); ?>
