<?php

    class Account {

        private $errorArray;

        public function __construct() {
            $this->errorArray = array();
        }

        public function register($un, $em, $em2) { 
            $this->validateUsername($un);
            $this->validateEmails($em, $em2);

            if(empty($this->errorArray)) {
                //Insert into db
                return true;
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

        private function validateUsername($un) {
            
            if (strlen($un) > 25 || strlen($un) < 5){
                array_push($this->errorArray, Constants::$UsernameCharecters);
                return;
            }

            //TODO: Check if username exists
            
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
        }
    }

?>