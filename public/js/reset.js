$(document).ready(function() {

    $(document).on('keyup', '#verificaion-token', function() {

        var token = $(this).val();
        var userID = $("input[name='user_id']").val();
        var csrf = $("input[name='_token']").val();

        $.ajax({
            method : 'POST',
            url : '/policyHolder/verifyToken/',
            data : {token : token, user_id : userID,_token : csrf},
            dataType : 'JSON',
            success : function (result){
                if(result == ''){
                    return FALSE;
                }
                if(result.status == 'error') {
                    $("#verificaion-token").css('border', "2px solid red");
                    $("#reset-pwd-error").html(result.msg); // Show error msg
                    $("#reset-pwd-error").css('color', "red");

                    $("#token-verify-btn").attr('disabled', true); // Disable Submit button
                    $("#token-verify-btn").css('cursor', 'not-allowed');
                } else {
                    $("#verificaion-token").css('border', "2px solid green");
                    $("#reset-pwd-error").html(''); // Show error msg
                    $("#reset-pwd-error").css('color', "green");

                    $("#token-verify-btn").attr('disabled', false); // Disable Submit button
                    $("#token-verify-btn").css('cursor', 'pointer');

                }
            }
        });
    });

    $(document).on('keyup', '#reset-re-password', function() {

        var rePassword = $(this).val();
        var password = $('#reset-password').val();
        if(rePassword !== password) {
            $("#reset-re-password").css('border', "2px solid red");
            $("#reset-password").css('border', "2px solid red");
            $("#reset-re-password-error").html('Password and Confirm Password fields does not match!'); // Show error msg
            $("#reset-re-password-error").css('color', "red");

            $("#token-verify-btn").attr('disabled', true); // Disable Submit button
            $("#token-verify-btn").css('cursor', 'not-allowed');
        }else {
            $("#reset-re-password").css('border', "2px solid green");
            $("#reset-password").css('border', "2px solid green");
            $("#reset-re-password-error").html(''); // Show error msg

            if($("#reset-pwd-error").val().length == 0) {
                $("#token-verify-btn").attr('disabled', false); // Disable Submit button
                $("#token-verify-btn").css('cursor', 'pointer');
            }
        }
    });
});
