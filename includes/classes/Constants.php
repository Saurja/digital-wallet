<?php

class Constants {

    #   Error Messages

    public static $emailsDoNotMatch = "Error: Your emails don't match";
    public static $emailsNotValid = "Error: Your email is Invalid";
    public static $emailTaken = "Error: The email is already taken";

    public static $UsernameCharecters = "Error: Your username must be between 5 and 25 charecters";
    public static $usernameTaken = "Error: The username already exists";

    public static $loginFailed = "Error: Your Email ID is incorrect";

    public static $MobileNotValid = "Error: Your phone is Invalid";
    public static $MobileTaken = "Error: The Mobile Number is already taken";

    public static $usernameInvalid = "Error: User doesn't exist";
    public static $InsufficientBalance = "Error: Insufficient Account Balance";
    public static $amountLessthanZero = "Error: Amount can't be less than zero";
    public static $cantSendSelf = "Error: You cannot send Money to yourself";
    public static $cantReqSelf = "Error: You cannot request Money from yourself";

    public static $InsufficientBalanceForReq = "Error: Insufficient Account Balance To Send Credits";
    public static $amountLessthanOne = "Error: Amount must be greater than zero";

    public static $voucherCodeInvalid = "Error: The voucher code is Invalid";
    
    public static $TranscErr = "Error in transaction : Please try again";
    public static $CannotConnectToDB = "Error : Unable to connect to Database";

    #   Success Messages

    public static $RequestSent = "Success: The request has been sent to user!";
    public static $VoucherRedeemed = "Success: The voucher has been Redeemed";
    public static $CreditsSent = "Success: Credits sent to user";
    
}

?>