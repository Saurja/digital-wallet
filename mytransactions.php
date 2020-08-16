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
                <th scope="col">Who</th>
                <th scope="col">Amount</th>
                <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $sen = $_SESSION['userLoggedIn'];
                $albumQuery = mysqli_query($con, "SELECT * FROM `transaction_table` WHERE `sender`='$sen' OR `reciever`='$sen'");
                while($row = mysqli_fetch_array($albumQuery)) {
                
                if ($row['sender'] == $sen) {
                    # code...
                    
                    echo "<tr>
                        <th scope='row'>" . $row['trans_id'] . "</th>
                        <td>To " . $row['reciever'] . "</td>
                        <td>" . $row['amount'] . " Points Sent</td>
                        <td>" . $row['trans_date'] . "</td>
                    </tr>";
                

                }elseif ($row['reciever'] == $sen) {
                    # code....
                    echo "<tr>
                        <th scope='row'>" . $row['trans_id'] . "</th>
                        <td>From " . $row['sender'] . "</td>
                        <td>" . $row['amount'] . " Points Received</td>
                        <td>" . $row['trans_date'] . "</td>
                    </tr>";

                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("includes/footer.php"); ?>
