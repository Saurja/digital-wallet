<?php

    class Transactions{

        private $con;
        private $errorArray;

        public function __construct($con) {
            $this->con = $con;
            $this->errorArray = array();
        }

        #   Funtion to send Credits to an intended user account

        public function sendcredits($sen, $reciv, $amt) {

            $query = mysqli_query($this->con, "SELECT * FROM user_details WHERE email_id='$reciv'");
            $creditbalance = mysqli_query($this->con, "SELECT credits FROM user_details WHERE email_id='$sen'");
            $resultarr = mysqli_fetch_assoc($creditbalance);
            
            if($amt < 0) { 
                array_push($this->errorArray, Constants::$amountLessthanZero);
                return false;
            }
            else if($resultarr['credits'] < $amt) { 
                array_push($this->errorArray, Constants::$InsufficientBalance);
                return false;
            }
            else if(mysqli_num_rows($query) != 1) {
                    array_push($this->errorArray, Constants::$usernameInvalid);
                    return false;
            }
            else if($sen == $reciv) {
                array_push($this->errorArray, Constants::$cantSendSelf);
                return false;
            }
            else { 
                    return $this->sendCreditToUser($sen, $reciv, $amt);
            }
            $this->saveTransactionHistory($sen, $reciv, $amt);
            
        }

        #   Function to delete column using Row ID

        public function deleteRowWithID($Id) {

            $db = new mysqli("localhost", "root", "", "digital-wallet");

            try {
                # First of all, let's begin a transaction
                $db->begin_transaction();
                # A set of queries; if one fails, an exception should be thrown
                $db->query("DELETE FROM `credit_requests` WHERE  `req_id` = '$Id'");

                # If we arrive here, it means that no exception was thrown
                # i.e. no query has failed, and we can commit the transaction
                $db->commit();
            } catch (\Throwable $e) {
                # An exception has been thrown
                # We must rollback the transaction
                $db->rollback();
                throw $e; # but the error must be handled anyway
            }
            
            # closing connection 
            mysqli_close($db); 
        }

        #   Funtion to send Credits to an intended user account

        public function sendRequestedcredits($sen, $reciv, $amt) {

            $query = mysqli_query($this->con, "SELECT * FROM user_details WHERE email_id='$reciv'");
            $creditbalance = mysqli_query($this->con, "SELECT credits FROM user_details WHERE email_id='$sen'");
            $resultarr = mysqli_fetch_assoc($creditbalance);
            
            if($amt < 1) { 
                array_push($this->errorArray, Constants::$amountLessthanOne);
                return false;
            }
            else if($resultarr['credits'] < $amt) { 
                array_push($this->errorArray, Constants::$InsufficientBalanceForReq);
                return false;
            }
            else if(mysqli_num_rows($query) != 1) {
                array_push($this->errorArray, Constants::$usernameInvalid);
                return false;
            }
            else if($sen == $reciv) {
                array_push($this->errorArray, Constants::$cantSendSelf);
                return false;
            }
            else { 
                return $this->sendCreditToUser($sen, $reciv, $amt);
            }
            
        }
        
        #   Funtion to request Credits from an intended user account

        public function reqCredits($sen, $reciv, $amt) {

            $query = mysqli_query($this->con, "SELECT * FROM user_details WHERE email_id='$reciv'");
            
            if($amt < 1) { 
                array_push($this->errorArray, Constants::$amountLessthanOne);
                return false;
            }
            else if(mysqli_num_rows($query) != 1) {
                    array_push($this->errorArray, Constants::$usernameInvalid);
                    return false;
            }
            else if($sen == $reciv) {
                array_push($this->errorArray, Constants::$cantReqSelf);
                return false;
            }
            else { 
                    return $this->receiveCreditFromUser($sen, $reciv, $amt);
            }
            
        }

        #   Function to generate voucher ID and store it in a database

        public function generateVoucherID($sen, $amt) {
            $VoucherID = md5(time());

            $creditbalance = mysqli_query($this->con, "SELECT credits FROM user_details WHERE email_id='$sen'");
            $resultarr = mysqli_fetch_assoc($creditbalance);

            $db = new mysqli("localhost", "root", "", "digital-wallet");
            if($amt < 1) { 
                array_push($this->errorArray, Constants::$amountLessthanOne);
                return false;
            }
            else if($resultarr['credits'] < $amt) { 
                array_push($this->errorArray, Constants::$InsufficientBalanceForReq);
                return false;
            }else{
                try {
                    # First of all, let's begin a transaction
                    $db->begin_transaction();
                    # A set of queries; if one fails, an exception should be thrown
                    $db->query("INSERT INTO `voucher_table`(`sender`, `amount`, `voucher_code`) VALUES ('$sen','$amt','$VoucherID');");
                    $db->query("UPDATE `user_details` SET `credits`=`credits`-$amt WHERE `email_id` ='$sen';");
                    # If we arrive here, it means that no exception was thrown
                    # i.e. no query has failed, and we can commit the transaction
                    $db->commit();
                } catch (\Throwable $e) {
                    # An exception has been thrown
                    # We must rollback the transaction
                    $db->rollback();
                    throw $e; # but the error must be handled anyway
                }
            }
            
            # closing connection 
            mysqli_close($db); 

            return "<span class='voucherIdhere'>Voucher ID: $VoucherID</span>";

        }

        #   Function to Redeem from a voucher ID and drop it from the database

        public function redeemVoucherID($sen, $vId) {
            
            $checkVoucherCodeQuery = mysqli_query($this->con, "SELECT `voucher_id` FROM `voucher_table` WHERE `voucher_code`='$vId'");

            #   Fetches amount that is need to be added if redeemed
            $amt = mysqli_query($this->con, "SELECT `amount` FROM `voucher_table` WHERE `voucher_code`='$vId'");
            $amt = mysqli_fetch_array($amt);
            $amt = isset($amt['amount']) ? ($amt['amount']) : 0;

            if (mysqli_num_rows($checkVoucherCodeQuery) == 0) {
                array_push($this->errorArray, Constants::$voucherCodeInvalid);
                return;
            } else {

                $db = new mysqli("localhost", "root", "", "digital-wallet");

                try {
                    # First of all, let's begin a transaction
                    $db->begin_transaction();
                    # A set of queries; if one fails, an exception should be thrown
                    $db->query("UPDATE `user_details` SET `credits`=`credits`+$amt WHERE `email_id` ='$sen'");

                    $db->query("DELETE FROM `voucher_table` WHERE  `voucher_code` = '$vId'");
                    # If we arrive here, it means that no exception was thrown
                    # i.e. no query has failed, and we can commit the transaction
                    $db->commit();
                } catch (\Throwable $e) {
                    # An exception has been thrown
                    # We must rollback the transaction
                    $db->rollback();
                    throw $e; # but the error must be handled anyway
                }
                # closing connection 
                mysqli_close($db);  
            }

        }

        #   Getting the error array ready

        Public function getError($error) {
            if(!in_array($error, $this->errorArray)) {
                $error = "";
            }
            return "<span class='errorMessage'>$error</span>";
        }

        #   Function to send MySQL commands for sending Credits

        private function sendCreditToUser($sen, $reciv, $amt) {
            $db = new mysqli("localhost", "root", "", "digital-wallet");

            try {
                # First of all, let's begin a transaction
                $db->begin_transaction();
                # A set of queries; if one fails, an exception should be thrown
                $db->query("SELECT `credits` FROM `user_details` WHERE `user_ID`='$sen';");
                $db->query("SELECT `credits` FROM `user_details` WHERE `user_ID`='$reciv';");
                $db->query("UPDATE `user_details` SET `credits`=`credits`-$amt WHERE email_id ='$sen';");
                $db->query("UPDATE `user_details` SET `credits`=`credits`+$amt WHERE email_id ='$reciv';");
                # If we arrive here, it means that no exception was thrown
                # i.e. no query has failed, and we can commit the transaction
                $db->commit();
            } catch (\Throwable $e) {
                # An exception has been thrown
                # We must rollback the transaction
                $db->rollback();
                throw $e; # but the error must be handled anyway
            }
            $this->saveTransactionHistory($sen, $reciv, $amt);
            # closing connection 
            mysqli_close($db); 
        }

        #   Function to send MySQL commands for pushing req info to database

        private function receiveCreditFromUser($sen, $reciv, $amt) {

            $db = new mysqli("localhost", "root", "", "digital-wallet");
            $date = date("Y-m-d h:i:sa");

            try {
                # First of all, let's begin a transaction
                $db->begin_transaction();
                # A set of queries; if one fails, an exception should be thrown
                $db->query("INSERT INTO `credit_requests`(`req_from`, `send_from`, `credits_requested`, `req_dateTime`) VALUES ('$sen','$reciv','$amt','$date');");
                # If we arrive here, it means that no exception was thrown
                # i.e. no query has failed, and we can commit the transaction
                $db->commit();
            } catch (\Throwable $e) {
                # An exception has been thrown
                # We must rollback the transaction
                $db->rollback();
                throw $e; # but the error must be handled anyway
            }

            # closing connection 
            mysqli_close($db); 
        }

        private function saveTransactionHistory($sen, $rec, $amt) {
            $db = new mysqli("localhost", "root", "", "digital-wallet");
            $date = date("Y-m-d h:i:sa");

            try {
                # First of all, let's begin a transaction
                $db->begin_transaction();
                # A set of queries; if one fails, an exception should be thrown
                $db->query("INSERT INTO `transaction_table`(`sender`, `reciever`, `trans_date`, `amount`) VALUES ('$sen','$rec','$date','$amt')");
                # If we arrive here, it means that no exception was thrown
                # i.e. no query has failed, and we can commit the transaction
                $db->commit();
            } catch (\Throwable $e) {
                # An exception has been thrown
                # We must rollback the transaction
                $db->rollback();
                throw $e; # but the error must be handled anyway
            }

            # closing connection 
            mysqli_close($db);

        }

    }

?>