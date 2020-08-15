<?php

    class Transactions{

        private $con;
        private $errorArray;

        public function __construct($con) {
            $this->con = $con;
            $this->errorArray = array();
        }

        public function sendMoney($sen, $reciv, $amt) {

            $query = mysqli_query($this->con, "SELECT * FROM user_details WHERE username='$reciv'");

            if(mysqli_num_rows($query) == 1) {
                return true;
            } else {
                array_push($this->errorArray, Constants::$usernameInvalid);
                return false;
            }
            
        }

        Public function getError($error) {
            if(!in_array($error, $this->errorArray)) {
                $error = "";
            }
            return "<span class='errorMessage'>$error</span>";
        }

    }

?>