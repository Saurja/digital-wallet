<?php include("includes/header.php"); ?>

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
            </div>
            <div class="form-group">
                <label for="sendAmount">Amount</label>
                <input type="number" class="form-control" id="sendAmount" name="sendAmount" placeholder="Enter Amount...">
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
                    <th scope="col">#</th>
                    <th scope="col">Who</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Req-Date</th>
                    <th scope="col">Responsce</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>From user_A@exapmple.com</td>
                    <td>20 Points Requested</td>
                    <td>2020-01-23</td>
                    <td><button type="button" class="btn btn-dark">Pay Now</button></td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>From user_B@exapmple.com</td>
                    <td>15 Points Requested</td>
                    <td>2020-01-28</td>
                    <td><button type="button" class="btn btn-dark">Pay Now</button></td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>From user_B@exapmple.com</td>
                    <td>100 Points Requested</td>
                    <td>2020-01-28</td>
                    <td><button type="button" class="btn btn-dark">Pay Now</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div
<?php include("includes/footer.php"); ?>
