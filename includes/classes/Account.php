<?php

    class Account {

        private $con;
        private $errorArray;

        public function __construct($con) {
            $this->con = $con;
            $this->errorArray = array();
        }

        public function login($em) {
            
            $query = mysqli_query($this->con, "SELECT * FROM `login_details` WHERE `email_id`='$em'");

            if(mysqli_num_rows($query)) {
                return true;
            } else {
                array_push($this->errorArray, Constants::$loginFailed);
                return false;
            }

        }

        public function register($un, $em, $em2, $mb) { 
            $this->validateUsername($un);
            $this->validateEmails($em, $em2);
            $this->validatePhoneNumber($mb);

            if(empty($this->errorArray)) {
                //Insert into db
                return $this->insertUserDetails($un, $em, $mb);
            } else {
                return false;
            }
        }

        Public function getError($error) {
            if(!in_array($error, $this->errorArray)) {
                $error = "";
            }
            return "<span class='errorMessage'>$error</span>";
        }

        private function insertUserDetails($un, $em, $mb){

            $init_cridit = 100;
            $result = mysqli_query($this->con, "INSERT INTO `login_details` (`user_name`, `contact_no`, `email_id`, `credits`, `counter`) VALUES ('$un', '$mb', '$em', '$init_cridit', '1')");
        
            return $result;
        }

        private function validateUsername($un) {
            
            if (strlen($un) > 25 || strlen($un) < 5){
                array_push($this->errorArray, Constants::$UsernameCharecters);
                return;
            }

            $checkUserNameQuery = mysqli_query($this->con, "SELECT `user_name` FROM `login_details` WHERE `user_name`='$un'");
            if (mysqli_num_rows($checkUserNameQuery) != 0) {
                array_push($this->errorArray, Constants::$usernameTaken);
                return;
            }
            
        }

        private function validateEmails($em, $em2) {
            if ($em != $em2){
                array_push($this->errorArray, Constants::$emailsDoNotMatch);
                return;
            }

            if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$emailsNotValid);
                return;
            }

            $checkEmailQuery = mysqli_query($this->con, "SELECT `email_id` FROM `login_details` WHERE `email_id`='$em'");
            if (mysqli_num_rows($checkEmailQuery) != 0) {
                array_push($this->errorArray, Constants::$emailTaken);
                return;
            }
        }

        private function validatePhoneNumber($mb)
        {
            // Check the lenght of number
            // This can be customized if you want phone number from a specific country
            if (strlen($mb) < 10 || strlen($mb) > 14) {
                array_push($this->errorArray, Constants::$MobileNotValid);
                return;
            } else {
            return true;
            }
        }
    }

?>