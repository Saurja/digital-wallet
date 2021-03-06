<?php include("includes/header.php"); ?>


<?php

include("includes/classes/Transactions.php"); 
$transactions = new Transactions($con);

?>

<div class="row justify-content-center mt-5">
    <div class="col-8">
        <h1 class="display-5"><strong>Transaction History</strong></h1>
        <table class="table table-bordered table-hover mt-4">
        <caption>These are the last transactions that you have made in the past.</caption>
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Trans_Id</th>
                    <th scope="col">To whom/ From whom</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sen = $_SESSION['userLoggedIn'];
                $albumQuery = mysqli_query($con, "SELECT `transaction_id`, user1.`email_id` AS `sender` , user2.`email_id` AS `receiver`, `transaction_date`, `transaction_amount` 
                FROM `transaction_table` t 
                JOIN `user_details` user1 ON t.`sender_id` = user1.`user_ID` 
                JOIN `user_details` user2 ON t.`receiver_id` = user2.`user_ID`
                WHERE user1.`email_id` = '$sen' or user2.`email_id` = '$sen' 
                ORDER BY `transaction_id` DESC LIMIT 20");
                

                while($row = mysqli_fetch_array($albumQuery)) {
                
                if ($row['sender'] == $sen) {
                    
                    echo "<tr class='table-warning'>
                        <th scope='row'>" . $row['transaction_id'] . "</th>
                        <td>To " . $row['receiver'] . "</td>
                        <td>" . numhash($row['transaction_amount']) . " Points Sent</td>
                        <td>" . $row['transaction_date'] . "</td>
                    </tr>";
                
                }elseif ($row['receiver'] == $sen) {
                    
                    echo "<tr class='table-success'>
                        <th scope='row'>" . $row['transaction_id'] . "</th>
                        <td>From " . $row['sender'] . "</td>
                        <td>" . numhash($row['transaction_amount']) . " Points Received</td>
                        <td>" . $row['transaction_date'] . "</td>
                    </tr>";

                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("includes/footer.php"); ?>