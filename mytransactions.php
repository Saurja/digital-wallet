<?php include("includes/header.php"); ?>


<?php

include("includes/classes/Transactions.php"); 
$transactions = new Transactions($con);

?>

<div class="row justify-content-center mt-5 mx-2">
    <div class="col-10">
        <h3>Transaction History</h3>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th scope="col">Trans_id</th>
                    <th scope="col">To whom/ From whom</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sen = $_SESSION['userLoggedIn'];
                #$sen = $transactions->getUserId($sen);
                #$albumQuery = mysqli_query($con, "SELECT * FROM `transaction_table` WHERE `sender_id`='$sen' OR `receiver_id`='$sen'");
                $albumQuery = mysqli_query($con, "SELECT `transaction_id`, user1.`email_id` AS `sender` , user2.`email_id` AS `receiver`, `transaction_date`, `transaction_amount` 
                FROM `transaction_table` t 
                JOIN `user_details` user1 ON t.`sender_id` = user1.`user_ID` 
                JOIN `user_details` user2 ON t.`receiver_id` = user2.`user_ID`
                WHERE user1.`email_id` = '$sen' or user2.`email_id` = '$sen'");
                

                while($row = mysqli_fetch_array($albumQuery)) {
                
                if ($row['sender'] == $sen) {
                    # code...
                    
                    echo "<tr>
                        <th scope='row'>" . $row['transaction_id'] . "</th>
                        <td>To " . $row['receiver'] . "</td>
                        <td>" . $row['transaction_date'] . " Points Sent</td>
                        <td>" . $row['transaction_amount'] . "</td>
                    </tr>";
                

                }elseif ($row['receiver'] == $sen) {
                    # code....
                    echo "<tr>
                        <th scope='row'>" . $row['transaction_id'] . "</th>
                        <td>From " . $row['sender'] . "</td>
                        <td>" . $row['transaction_date'] . " Points Received</td>
                        <td>" . $row['transaction_amount'] . "</td>
                    </tr>";

                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("includes/footer.php"); ?>