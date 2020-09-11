$(document).ready(function() {

    // Product service subcategory
    $(document).on('change', '#pro-service', function() {

        // Get csrf token to send in the request
        var csrf = $("input[name='_token']").val();
        var service = $(this).val();
        $("#pro-service-cat").html('');
        $("#pro-service-subcat").html('');

        // Send this id to ajax request
        $.ajax({
            type: 'POST',
            url: '/admin/getServiceCategory',
            data: {service_id : service, _token : csrf},
            dataType: 'JSON',
            success: function(result) {
                var opt = '';
                /*if(result.status == 'error') {
                    alert(result.msg);
                    return;
                }*/
                if(result.status == 'success') {
                    $("#pro-service-cat").attr('disabled', false);
                    $('#pro-service-cat').append("<option selected disabled value=''>-Select Category-</option>");

                    $.each(result.data, function(index, element) {
                        var data = {
                            id: element.id,
                            text: element.name
                        };

                        var newOption = new Option(data.text, data.id, false, false);
                        $('#pro-service-cat').append(newOption).trigger('change');
                    });

                } else {
                    console.log(result.msg)
                }
            }
        });

    });

    // Product service subcategory
    $(document).on('change', '#pro-service-cat', function() {

        // Get csrf token to send in the request
        var csrf = $("input[name='_token']").val();
        var category = $(this).val();
        $("#pro-service-subcat").html('');

        // Send this id to ajax request
        $.ajax({
            type: 'POST',
            url: '/admin/getServiceSubCategory',
            data: {cat_id : category, _token : csrf},
            dataType: 'JSON',
            success: function(result) {
                var opt = '';
                /*if(result.status == 'error') {
                    alert(result.msg);
                    return;
                }*/
                if(result.status == 'success') {
                    $("#pro-service-subcat").attr('disabled', false);
                    $('#pro-service-subcat').append("<option selected disabled value=''>-Select Category-</option>");

                    $.each(result.data, function(index, element) {
                        var data = {
                            id: element.id,
                            text: element.name
                        };

                        var newOption = new Option(data.text, data.id, false, false);
                        $('#pro-service-subcat').append(newOption).trigger('change');
                    });

                } else {
                    console.log(result.msg)
                }
            }
        });

    });

    $(document).on('click','.serviceEditBtn', function () {

        $("#serviceID").val('');
        $("#editServiceName").val('');

        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');

        $("#serviceID").val(id);
        $("#editServiceName").val(name);
    });

    $(document).on('click','.serviceCatEditBtn', function () {

        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var serviceID = $(this).attr('data-servID');

        $("#serviceCatID").val(id);
        $("#editServCatName").val(name);
        $('#editCatSel option[value="'+serviceID+'"]').prop('selected', true);
    });

    $(document).on('click','.serviceSubCatEditBtn', function () {

        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var catID = $(this).attr('data-catID');

        $("#serviceSubCatID").val(id);
        $("#editServCatName").val(name);
        $('#editSubCatSel option[value="'+catID+'"]').prop('selected', true);
    });

    $(document).on('click','.prodEditBtn', function () {

        var dump = $(this).attr('data-prod');
        var json = atob(dump);
        var arr = JSON.parse(json);

        var description = arr.description;
        var id = arr.id;
        var image = arr.image;
        var name = arr.name;
        var serviceID = arr.service_id;
        var serviceCatID = arr.service_cat_id;
        var serviceSubCatID = arr.service_subcat_id;

        $("#prodImgEdit").attr('src', image);
        $('#pro-service option[value="'+serviceID+'"]').prop('selected', true);
        $("#prodNameEdit").val(name);
        $("#prodDescEdit").val(description);
        $("#prodIDEdit").val(id);

        if(serviceCatID.length > 0) {
            alert('Make request');
        }else {
            alert('Dont');
        }
        return;
        // Get category and subcategory list according to the current selected data
        // Get csrf token to send in the request
        var csrf = $("input[name='_token']").val();
        var category = $(this).val();
        $("#pro-service-subcat").html('');

        // Send this id to ajax request
        $.ajax({
            type: 'POST',
            url: '/admin/getServiceSubCategory',
            data: {cat_id : category, _token : csrf},
            dataType: 'JSON',
            success: function(result) {
                var opt = '';
                /*if(result.status == 'error') {
                    alert(result.msg);
                    return;
                }*/
                if(result.status == 'success') {
                    $("#pro-service-subcat").attr('disabled', false);
                    $('#pro-service-subcat').append("<option selected disabled value=''>-Select Category-</option>");

                    $.each(result.data, function(index, element) {
                        var data = {
                            id: element.id,
                            text: element.name
                        };

                        var newOption = new Option(data.text, data.id, false, false);
                        $('#pro-service-subcat').append(newOption).trigger('change');
                    });

                } else {
                    console.log(result.msg)
                }
            }
        });
    });
});
