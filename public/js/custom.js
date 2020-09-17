$(document).ready(function() {

    $("#manage-policy-tbl").DataTable();
    $("#add-policy-ben").select2();

    // Check user cell number uniqueness
    $(document).on('keyup','#reg-contact-no',function(){

        var cellNumber = $(this).val();
        var csrf = $("input[name='_token']").val();
        // Composer url for ajax request
        var hostName = $(location).attr('hostname');
        if(hostName == 'localhost') {
            var url = $(location).attr('protocol')+'//'+ hostName +'/dollieInc/home/checkUser';
        } else {
            var url = $(location).attr('protocol')+'//'+ hostName +'/home/checkUser';
        }
        //var thisRef = $(this);
        // Send ajax request to check email
        $.ajax({
            method : 'POST',
            url : '/policyHolder/checkCell/',
            data : {cell_number : cellNumber, _token : csrf},
            dataType : 'JSON',
            success : function (result){
                if(result == ''){
                    return FALSE;
                }
                if(result.status == 'error') {
                    $("#reg-contact-no").css('border', "2px solid red");
                    $("#reg-contact-error").html(result.msg); // Show error msg
                    $("#reg-contact-error").css('color', "red");

                    $("#reg-sub-btn").attr('disabled', true); // Disable Submit button
                    $("#reg-sub-btn").css('cursor', 'not-allowed');
                } else {
                    $("#reg-contact-no").css('border', "2px solid green");
                    $("#reg-contact-error").html(''); // Show error msg
                    $("#reg-contact-error").css('color', "red");

                    $("#reg-sub-btn").attr('disabled', false); // Disable Submit button
                    $("#reg-sub-btn").css('cursor', 'pointer');
                }
            }
        });
    });
});
