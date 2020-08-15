<?php

    class Transactions{

        private $con;
        private $errorArray;

        public function __construct($con) {
            $this->con = $con;
            $this->errorArray = array();
        }

        public function sendcredits($sen, $reciv, $amt) {

            $query = mysqli_query($this->con, "SELECT * FROM user_details WHERE email_id='$reciv'");
            $creditbalance = mysqli_query($this->con, "SELECT credits FROM user_details WHERE email_id='$sen'");
            $resultarr = mysqli_fetch_assoc($creditbalance);
            
            if($amt > 0) {

                if($resultarr['credits'] >= $amt) {

                    if(mysqli_num_rows($query) == 1) {
                        return $this->sendCreditToUser($sen, $reciv, $amt);
                    } else {
                        array_push($this->errorArray, Constants::$usernameInvalid);
                        return false;
                    }
    
                    
                } else {

                    array_push($this->errorArray, Constants::$InsufficientBalance);
                    return false;

                }

            } else {
                array_push($this->errorArray, Constants::$amountLessthanZero);
                return false;
            }
            
        }

        Public function getError($error) {
            if(!in_array($error, $this->errorArray)) {
                $error = "";
            }
            return "<span class='errorMessage'>$error</span>";
        }

        private function sendCreditToUser($sen, $reciv, $amt) {
            $db = new mysqli("localhost", "root", "", "digital-wallet");

            try {
                // First of all, let's begin a transaction
                $db->begin_transaction();
                // A set of queries; if one fails, an exception should be thrown
                $db->query("SELECT `credits` FROM `user_details` WHERE `user_ID`='$sen';");
                $db->query("SELECT `credits` FROM `user_details` WHERE `user_ID`='$reciv';");
                $db->query("UPDATE `user_details` SET `credits`=`credits`-$amt WHERE email_id ='$sen';");
                $db->query("UPDATE `user_details` SET `credits`=`credits`+$amt WHERE email_id ='$reciv';");
                // If we arrive here, it means that no exception was thrown
                // i.e. no query has failed, and we can commit the transaction
                $db->commit();
            } catch (\Throwable $e) {
                // An exception has been thrown
                // We must rollback the transaction
                $db->rollback();
                throw $e; // but the error must be handled anyway
            }
        }

    }

?>