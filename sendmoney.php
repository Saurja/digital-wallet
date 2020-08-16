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

    if($wasSuccessful){
        echo $sender . " ";
        echo $receiver . " ";
        echo $amount . " ";
    }

}
?>


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

<!--    Display Request table    -->
<div class="row justify-content-center my-5">
    <div class="col-10"> 
    <h3>Transfer Requests</h3>
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
            
                $sender = $_SESSION['userLoggedIn'];
                $reqCreditQuery = mysqli_query($con, "SELECT * FROM credit_requests WHERE send_from='$sender'");
                while($row = mysqli_fetch_array($reqCreditQuery)) {

                    $rowID = $row['req_id'];
                    echo "<tr>
                            <th scope='row'>" . $row['req_id'] . "</th>
                            <td>From " . $row['req_from'] .  "</td>
                            <td>" . $row['credits_requested'] .  " Points Requested</td>
                            <td>" . $row['req_dateTime'] .  "</td>
                            <td><button type='submit' name='sendRequestedMoneyButton" . $rowID . "' class='btn btn-dark'>Pay Now</button></td>
                        </tr>";
                }

                while($row = mysqli_fetch_array($reqCreditQuery)) {

                    $rowID = $row['req_id'];
                    
                    if(isset($_POST['sendRequestedMoneyButton'.$rowID])){

                        $sender = $_SESSION['userLoggedIn'];
                        $receiverName= $row['req_from'];
                        $amount = $row['credits_requested'];
                        
                        $wasSuccessful = $transactions->sendcredits($sender, $receiverName, $amount);

                        if($wasSuccessful){
                            echo $sender . " ";
                            echo $receiver . " ";
                            echo $amount . " ";
                        }
                    }
                }
            ?>
            </tbody>
        </table>
    </div>
</div
<?php include("includes/footer.php"); ?>
