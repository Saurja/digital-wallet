<?php

    class Account {

        private $con;
        private $errorArray;

        public function __construct($con) {
            $this->con = $con;
            $this->errorArray = array();
        }

        public function login($em) {
            
            $stmt = $this->con->prepare('SELECT * FROM `user_details` WHERE `email_id`= ?');
            $stmt->bind_param('s', $em);
            $stmt->execute();
            $query = $stmt->get_result();

            if(mysqli_num_rows($query)) {
                return true;
            } else {
                array_push($this->errorArray, Constants::$loginFailed);
                return false;
            }

            $stmt->close();

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
            $init_Counter = 1;
            $date = date("Y-m-d h:i:sa");
                    
            $stmt = $this->con->prepare("INSERT INTO `user_details` (`user_name`, `contact_no`, `email_id`, `credits`, `last_activity` ,`counter`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdsi", $un, $mb, $em, $init_cridit,$date ,$init_Counter);
            $stmt->execute();
            $stmt->close();
            return true;

        }

        private function validateUsername($un) {
            
            if (strlen($un) > 25 || strlen($un) < 5){
                array_push($this->errorArray, Constants::$UsernameCharecters);
                return;
            }

            $stmt = $this->con->prepare('SELECT `user_name` FROM `user_details` WHERE `user_name`= ?');
            $stmt->bind_param('s', $un);
            $stmt->execute();
            $checkUserNameQuery = $stmt->get_result();
            if (mysqli_num_rows($checkUserNameQuery) != 0) {
                array_push($this->errorArray, Constants::$usernameTaken);
            }
            $stmt->close();
            
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

            $stmt = $this->con->prepare('SELECT `email_id` FROM `user_details` WHERE `email_id`= ?');
            $stmt->bind_param('s', $em);
            $stmt->execute();
            $checkEmailQuery = $stmt->get_result();
            if (mysqli_num_rows($checkEmailQuery) != 0) {
                array_push($this->errorArray, Constants::$emailTaken);
            }
            $stmt->close();
        }

        private function validatePhoneNumber($mb)
        {

            $stmt = $this->con->prepare('SELECT `contact_no` FROM `user_details` WHERE `contact_no`= ?');
            $stmt->bind_param('s', $mb);
            $stmt->execute();
            $checkMobileQuery = $stmt->get_result();
            if (mysqli_num_rows($checkMobileQuery) != 0) {
                array_push($this->errorArray, Constants::$MobileTaken);
                return false;
            }

            if (strlen($mb) < 10 || strlen($mb) > 14) {
                array_push($this->errorArray, Constants::$MobileNotValid);
            } else {
            return true;
            }
            $stmt->close();
        }
    }

?>