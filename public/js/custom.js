$(document).ready(function() {

    $("#manage-policy-tbl").DataTable({
        "bFilter": false
    });
    $("#manage-bene-tbl").DataTable({
        "bFilter": false
    });
    $("#add-policy-ben").select2({
        placeholder: "Choose Beneficiaries",
    });

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
