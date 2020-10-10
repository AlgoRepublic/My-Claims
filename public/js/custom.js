$(document).ready(function() {

    $(document).on('click', '.admin-msg-btn', function() {
        var msg = atob($(this).attr('data-msg'));
        $("#admin-contac-msg").val(msg);
    });

    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $(document).on('change', '#blog-img', function() {
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
            $(this).val('');
            alert('Invalid extension! Only images are allowed.');
        }
    });

    // Check user/beneficiary cell number & IDN uniqueness
    $(document).on('keyup',"#reg-contact-no, #user-idn",function(){

        var cellNumber = $(this).val();
        var type = $(this).data('type');

        var csrf = $("input[name='_token']").val();
        // Composer url for ajax request
        var hostName = $(location).attr('hostname');
        if(hostName == 'localhost') {
            var url = $(location).attr('protocol')+'//'+ hostName +'/dollieInc/home/checkUser';
        } else {
            var url = $(location).attr('protocol')+'//'+ hostName +'/home/checkUser';
        }
        var msgSelector = '';
        if(type === 'mobile') {
            msgSelector = "#reg-contact-error";
            fieldSelector = "#reg-contact-no";
        }
        else if(type === 'identity_document_number') {
            msgSelector = "#reg-idn-error";
            fieldSelector = "#user-idn";
        }

        var ben = 0;
        if($(this).data('source') == 'beneficiary')
            ben = 1;

        // Send ajax request to check email
        $.ajax({
            method : 'POST',
            url : '/policyHolder/checkCell/',
            data : {col_value : cellNumber, _token : csrf, type: type, ben: ben},
            dataType : 'JSON',
            success : function (result){
                if(result == ''){
                    return FALSE;
                }
                if(result.status == 'error') {
                    $(fieldSelector).css('border', "2px solid red");
                    $(msgSelector).html(result.msg); // Show error msg
                    $(msgSelector).css('color', "red");

                    $("#reg-sub-btn").attr('disabled', true); // Disable Submit button
                    $("#reg-sub-btn").css('cursor', 'not-allowed');
                } else {
                    $(fieldSelector).css('border', "2px solid green");
                    $(msgSelector).html(''); // Show error msg
                    $(msgSelector).css('color', "red");

                    if($("#reg-pass-error").text().length == 0 && $("#reg-contact-error").text().length == 0 && $("#reg-idn-error").text().length == 0) {
                        $("#reg-sub-btn").attr('disabled', false); // Disable Submit button
                        $("#reg-sub-btn").css('cursor', 'pointer');
                    }
                }
            }
        });
    });

    // Check user cell number uniqueness
    $(document).on('keyup',"#reg-re-pass, #reg-pass",function(){

        var password = $("#reg-pass").val();
        var rePassword = $("#reg-re-pass").val();

        if(password != rePassword) {

            $("#reg-pass").css('border', "2px solid red");
            $("#reg-re-pass").css('border', "2px solid red");
            $("#reg-pass-error").html('Password and Repeat Password does not match!'); // Show error msg
            $("#reg-pass-error").css('color', "red");

            $("#reg-sub-btn").attr('disabled', true); // Disable Submit button
            $("#reg-sub-btn").css('cursor', 'not-allowed');
        }else {

            $("#reg-pass").css('border', "2px solid green");
            $("#reg-re-pass").css('border', "2px solid green");
            $("#reg-pass-error").html(''); // Show error msg
            $("#reg-pass-error").css('color', "green");

            if($("#reg-contact-error").text().length == 0 && $("#reg-idn-error").text().length == 0) {
                $("#reg-sub-btn").attr('disabled', false); // Disable Submit button
                $("#reg-sub-btn").css('cursor', 'pointer');
            }
        }
    });

    $("#creat-new-ben").on('click', function() {
        var htmlFields = '<div class="bene-wrap"><h3>New Beneficiary<i class="fa fa-times-circle attr-close"></i></h3>\n' +
            '                    <hr>\n' +
            '                    <div class="form-row">\n' +
            '                        <div class="form-group col-md-6">\n' +
            '                            <label for=" ">Name<span class="text-danger"><b>*</b></span></label>\n' +
            '                            <input type="text" class="form-control" name="bene_name[]" placeholder="Enter Name" required>\n' +
            '                        </div>\n' +
            '                        <div class="form-group col-md-6">\n' +
            '                            <label for=" ">Surname<span class="text-danger"><b>*</b></span></label>\n' +
            '                            <input type="text" class="form-control" name="bene_surname[]" placeholder="Enter Surname" required>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                    <div class="form-row">\n' +
            '                        <div class="form-group col-md-6">\n' +
            '                            <label for=" ">South African Identity Document Number<span class="text-danger"><b>*</b></span></label>\n' +
            '                            <input type="number" class="form-control" name="bene_document_number[]" placeholder="Enter Document Number" required>\n' +
            '                        </div>\n' +
            '                        <div class="form-group col-md-6">\n' +
            '                            <label for=" ">Cell Phone Number<span class="text-danger"><b>*</b></span></label>\n' +
            '                            <input type="text" class="form-control" name="bene_cell_number[]" placeholder="Enter Cell Number" required>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                </div>';
        $(".opp-attr-div").append(htmlFields);
    });

    // Removes the attorney html upon click
    $(document).on('click', '.attr-close', function() {
        $(this).parent().parent().html('');
    });
});
