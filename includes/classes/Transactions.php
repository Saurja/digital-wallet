<?php

    define("CONNECT_DB", "includes/transactionConfig.php");

    class Transactions{

        private $con;
        private $errorArray;

        
        

        public function __construct($con) {
            $this->con = $con;
            $this->errorArray = array();
            $this->SuccessArray = array();
        }

        #   Funtion to send Credits to an intended user account

        public function sendcredits($sen, $reciv, $amt) {

            #   Get whom to send from database
            $stmt = $this->con->prepare('SELECT * FROM user_details WHERE email_id= ?');
            $stmt->bind_param('s', $reciv); // 's' specifies the variable type => 'string'
            $stmt->execute();
            $query = $stmt->get_result();
            $stmt->close();

            #   Get Logged in user's credit balance
            $stmt = $this->con->prepare('SELECT credits FROM user_details WHERE email_id= ?');
            $stmt->bind_param('s', $sen); // 's' specifies the variable type => 'string'
            $stmt->execute();
            $creditbalance = $stmt->get_result();
            $stmt->close();
            $creditbalance = mysqli_fetch_assoc($creditbalance);
            $creditbalance = numhash($creditbalance['credits']);
            
            if($amt < 1) { 
                array_push($this->errorArray, Constants::$amountLessthanOne);
            }
            else if($creditbalance < $amt) { 
                array_push($this->errorArray, Constants::$InsufficientBalance);
            }
            else if(mysqli_num_rows($query) != 1) {
                array_push($this->errorArray, Constants::$usernameInvalid);
            }
            else if($sen == $reciv) {
                array_push($this->errorArray, Constants::$cantSendSelf);
            }
            else { 
                $this->sendCreditToUser($sen, $reciv, $amt);
                return true;
            }
        }

        #   Funtion to send Credits to an intended user account

        public function sendRequestedcredits($sen, $reciv, $amt) {

            #   Get whom to send from database
            $stmt = $this->con->prepare('SELECT * FROM user_details WHERE email_id= ?');
            $stmt->bind_param('s', $reciv); // 's' specifies the variable type => 'string'
            $stmt->execute();
            $query = $stmt->get_result();
            $stmt->close();

            #   Get Logged in user's credit balance
            $stmt = $this->con->prepare('SELECT credits FROM user_details WHERE email_id= ?');
            $stmt->bind_param('s', $sen); // 's' specifies the variable type => 'string'
            $stmt->execute();
            $creditbalance = $stmt->get_result();
            $stmt->close();
            $creditbalance = mysqli_fetch_assoc($creditbalance);
            
            if($amt < 1) { 
                array_push($this->errorArray, Constants::$amountLessthanOne);
            }
            else if(numhash($creditbalance['credits']) < numhash($amt)) { 
                array_push($this->errorArray, Constants::$InsufficientBalanceForReq);
            }
            else if(mysqli_num_rows($query) != 1) {
                array_push($this->errorArray, Constants::$usernameInvalid);
            }
            else if($sen == $reciv) {
                array_push($this->errorArray, Constants::$cantSendSelf);
            }
            else { 
                $amt = numhash($amt);
                $this->sendCreditToUser($sen, $reciv, $amt);
                return true;
            }
            
        }
        
        #   Funtion to request Credits from an intended user account

        public function reqCredits($sen, $reciv, $amt) {

            #   Get whom to send from database
            $stmt = $this->con->prepare('SELECT * FROM user_details WHERE email_id= ?');
            $stmt->bind_param('s', $reciv); // 's' specifies the variable type => 'string'
            $stmt->execute();
            $query = $stmt->get_result();
            $stmt->close();
            
            if($amt < 1) { 
                array_push($this->errorArray, Constants::$amountLessthanOne);
            }
            else if(mysqli_num_rows($query) != 1) {
                array_push($this->errorArray, Constants::$usernameInvalid);
            }
            else if($sen == $reciv) {
                array_push($this->errorArray, Constants::$cantReqSelf);
            }
            else { 
                $this->receiveCreditFromUser($sen, $reciv, $amt);
                return true;
            }
            
        }

        #   Function to generate voucher ID and store it in a database

        public function generateVoucherID($sen, $amt) {
            
            $VoucherID = $this->generateRandomString(8);

            #   Get Logged in user's credit balance
            $stmt = $this->con->prepare('SELECT credits FROM user_details WHERE email_id= ?');
            $stmt->bind_param('s', $sen); // 's' specifies the variable type => 'string'
            $stmt->execute();
            $creditbalance = $stmt->get_result();
            $stmt->close();
            $creditbalance = mysqli_fetch_assoc($creditbalance);
            $creditbalance = numhash($creditbalance['credits']);
            # Create and check a new connection to the database

            include(CONNECT_DB);
            
            $sen = $this->getUserId($sen);

            if($amt < 1) { 
                array_push($this->errorArray, Constants::$amountLessthanOne);
                return false;
            }
            else if($creditbalance < $amt) { 
                array_push($this->errorArray, Constants::$InsufficientBalanceForReq);
                return false;
            }else{
                try {  
                    
                    $amtdeduct = $creditbalance - $amt;
                    $amtdeduct = $this->numhash($amtdeduct);

                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    # begin a Transaction
                    $dbh->beginTransaction();

                    $amt = numhash($amt);
                    # A set of queries; if one fails, an exception should be thrown
                    $sth = $dbh->prepare("INSERT INTO `voucher_table`(`sender_id`, `voucher_amount`, `voucher_code`) VALUES (?,?,?)");
                    $sth->execute(array($sen,$amt,$VoucherID));
                    $sth = $dbh->prepare("UPDATE `user_details` SET `credits`=? WHERE `user_ID` =?");
                    $sth->execute(array($amtdeduct, $sen));
                    # If we arrive here, it means that no exception was thrown
                    # i.e. no query has failed, and we can commit the transaction
                    $dbh->commit();
                
                } catch (Exception $e) {
                    # An exception has been thrown; We must rollback the transaction
                    $dbh->rollBack();
                    array_push($this->errorArray, Constants::$TranscErrSend);
                }
                # closing connection 
                $dbh = null;
                $sth = null;
            }

            return "<span class='voucherIdhere'>Voucher ID: $VoucherID</span>";

        }

        #   Function to Redeem from a voucher ID and drop it from the database

        public function redeemVoucherID($sen, $vId) {

            $sen = $this->getUserId($sen);

            $stmt = $this->con->prepare('SELECT `voucher_id` FROM `voucher_table` WHERE `voucher_code` = ?');
            $stmt->bind_param('s', $vId);
            $stmt->execute();
            $checkVoucherCodeQuery = $stmt->get_result();
            $stmt->close();
            
            #   Fetches amount that is need to be added if redeemed
            $stmt = $this->con->prepare('SELECT `voucher_amount` FROM `voucher_table` WHERE `voucher_code`= ?');
            $stmt->bind_param('s', $vId); // 's' specifies the variable type => 'string'
            $stmt->execute();
            $amt = $stmt->get_result();
            $stmt->close();
            $amt = mysqli_fetch_array($amt);
            $amt = isset($amt['voucher_amount']) ? ($amt['voucher_amount']) : 0;
            $amt = numhash($amt);

            if (mysqli_num_rows($checkVoucherCodeQuery) == 0) {
                array_push($this->errorArray, Constants::$voucherCodeInvalid);
            } else {

                # Create and check a new connection to the database
                include(CONNECT_DB);

                $amtadd = $this->getUserCredit($_SESSION["userLoggedIn"]);
                $amtadd = $this->numhash($amtadd);
                $amtadd = $amtadd + $amt;
                $amtadd = $this->numhash($amtadd);

                try {  

                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                    # begin a Transaction
                    $dbh->beginTransaction();
    
                    # A set of queries; if one fails, an exception should be thrown
                    $sth = $dbh->prepare("UPDATE `user_details` SET `credits`=? WHERE `user_ID` =?");
                    $sth->execute(array($amtadd,$sen));
                    $sth = $dbh->prepare("DELETE FROM `voucher_table` WHERE  `voucher_code` = ?");
                    $sth->execute(array($vId));
                    # If we arrive here, it means that no exception was thrown
                    # i.e. no query has failed, and we can commit the transaction
                    array_push($this->SuccessArray, Constants::$RequestSent);
                    $dbh->commit();
                    array_push($this->SuccessArray, Constants::$VoucherRedeemed);

                } catch (Exception $e) {
                    # An exception has been thrown; We must rollback the transaction
                    $dbh->rollBack();
                    array_push($this->errorArray, Constants::$TranscErr);
                } finally {
                    # closing connection 
                    $dbh = null;
                    $sth = null;
                    return true;
                }
                
            }

            

        }

        #   Function to delete column using Row ID

        public function deleteRowWithID($Id) {

            include(CONNECT_DB);

            try {  
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # begin a Transaction
                $dbh->beginTransaction();

                # A set of queries; if one fails, an exception should be thrown

                $sth = $dbh->prepare("DELETE FROM `credit_requests` WHERE  `req_id` = ?");
                $sth->execute(array($Id));

                # If we arrive here, it means that no exception was thrown
                # i.e. no query has failed, and we can commit the transaction
                $dbh->commit();
                
            } catch (Exception $e) {
                # An exception has been thrown; We must rollback the transaction
                $dbh->rollBack();
                array_push($this->errorArray, Constants::$TranscErr);
            }
            # closing connection 
            $dbh = null;
            $sth = null;
        }

        #   Function to get USER_ID from the Email-ID

        public function getUserId($un) {
            include(CONNECT_DB);
            
            try {  
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # begin a Transaction
                $dbh->beginTransaction();

                # A set of queries; if one fails, an exception should be thrown
                $sth = $dbh->prepare("SELECT `user_ID` FROM `user_details` WHERE email_id=?");
                $sth->execute(array($un));
                
                # If we arrive here, it means that no exception was thrown
                # i.e. no query has failed, and we can commit the transaction
                $UserID = $sth -> fetch();
                $UserID = $UserID["user_ID"];
                array_push($this->SuccessArray, Constants::$RequestSent);
                $dbh->commit();
                
            } catch (Exception $e) {
                # An exception has been thrown; We must rollback the transaction
                $dbh->rollBack();
                array_push($this->errorArray, Constants::$TranscErr);
            } finally {
                # closing connection 
                $dbh = null;
                $sth = null;
            }

            return $UserID;

        }

        #   Function to get Credits from the Email-ID

        public function getUserCredit($un) {
            include(CONNECT_DB);
            
            try {  
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # begin a Transaction
                $dbh->beginTransaction();

                # A set of queries; if one fails, an exception should be thrown
                $sth = $dbh->prepare("SELECT `credits` FROM `user_details` WHERE email_id=?");
                $sth->execute(array($un));
                
                # If we arrive here, it means that no exception was thrown
                # i.e. no query has failed, and we can commit the transaction
                $UserCredits = $sth -> fetch();
                $UserCredits = $UserCredits["credits"];
                array_push($this->SuccessArray, Constants::$RequestSent);
                $dbh->commit();
                
            } catch (Exception $e) {
                # An exception has been thrown; We must rollback the transaction
                $dbh->rollBack();
                array_push($this->errorArray, Constants::$TranscErr);
            } finally {
                # closing connection 
                $dbh = null;
                $sth = null;
            }

            return $UserCredits;

        }

        #   Getting the error array ready

        Public function getError($error) {
            if(!in_array($error, $this->errorArray)) {
                $error = "";
            }
            return "<span class='errorMessage'>$error</span>";
        }

        #   Getting the Success array ready
        Public function getSuccess($Success) {
            if(!in_array($Success, $this->SuccessArray)) {
                $Success = "";
            }
            return "<span class='successMessage'>$Success</span>";
        }

        private function numhash($n) {
            return ((0x0000000F & $n) << 4) + ((0x000000F0& $n)>>4)
            + ((0x00000F00 & $n) << 4) + ((0x0000F000& $n)>>4)
            + ((0x000F0000 & $n) << 4) + ((0x00F00000& $n)>>4)
            + ((0x0F000000 & $n) << 4) + ((0xF0000000& $n)>>4);
        }

        #   Function to generate random strings

        private function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        #   Function to send MySQL commands for sending Credits

        private function sendCreditToUser($sen, $reciv, $amt) {
            
            # Create and check a new connection to the database
            include(CONNECT_DB);

            $amtadd = $this->getUserCredit($reciv);
            $amtadd = $this->numhash($amtadd);
            $credit = $amtadd + $amt;
            $credit = $this->numhash($credit);

            $amtdeduct = $this->getUserCredit($sen);
            $amtdeduct = $this->numhash($amtdeduct);
            $debit = $amtdeduct - $amt;
            $debit = $this->numhash($debit);
            $amt = numhash($amt);

            try {  
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # begin a Transaction
                $dbh->beginTransaction();
                $date = date("Y-m-d h:i:sa");
                # A set of queries; if one fails, an exception should be thrown
                $sth = $dbh->prepare("UPDATE `user_details` SET `credits`=? WHERE email_id=?");
                $sth->execute(array($debit,$sen));
                $sth = $dbh->prepare("UPDATE `user_details` SET `credits`=? WHERE email_id=?");
                $sth->execute(array($credit,$reciv));

                $sen = $this->getUserId($sen);
                $reciv = $this->getUserId($reciv);

                #   Saves transation information to transaction table
                $sth = $dbh->prepare("INSERT INTO `transaction_table`(`sender_id`, `receiver_id`, `transaction_date`, `transaction_amount`) VALUES (?,?,?,?)");
                $sth->execute(array($sen,$reciv,$date,$amt));
                
                # If we arrive here, it means that no exception was thrown
                array_push($this->SuccessArray, Constants::$CreditsSent);
                
                # i.e. no query has failed, and we can commit the transaction
                $dbh->commit();
                
            } catch (Exception $e) {
                # An exception has been thrown; We must rollback the transaction
                $dbh->rollBack();
                array_push($this->errorArray, Constants::$TranscErrSend);
                return false;
            } finally {
                # closing connection 
                $dbh = null;
                $sth = null;
            }
            
        }

        #   Function to send MySQL commands for pushing req info to database

        private function receiveCreditFromUser($sen, $reciv, $amt) {

            $date = date("Y-m-d h:i:sa");
            # Create and check a new connection to the database
            include(CONNECT_DB);
            $sen = $this->getUserId($sen);
            $reciv = $this->getUserId($reciv);

            try {  
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # begin a Transaction
                $dbh->beginTransaction();

                $amt = numhash($amt);
                # A set of queries; if one fails, an exception should be thrown
                $sth = $dbh->prepare("INSERT INTO `credit_requests`(`req_from`, `send_from`, `credits_requested`, `req_dateTime`) VALUES (?,?,?,?)");
                $sth->execute(array($sen,$reciv,$amt,$date));
                
                # If we arrive here, it means that no exception was thrown
                # i.e. no query has failed, and we can commit the transaction
                array_push($this->SuccessArray, Constants::$RequestSent);
                $dbh->commit();
                
            } catch (Exception $e) {
                # An exception has been thrown; We must rollback the transaction
                $dbh->rollBack();
                array_push($this->errorArray, Constants::$TranscErr);
            }
            
            # closing connection 
            $dbh = null;
            $sth = null;
        }

    }

?>