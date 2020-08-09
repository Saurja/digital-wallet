$(document).ready(function(){

    $("#hideLogin").click(function() {
        $("#walletLoginForm").hide();
        $("#walletRegisterForm").show();
    });

    $("#hideRegister").click(function() {
        $("#walletLoginForm").show();
        $("#walletRegisterForm").hide();
    });
});