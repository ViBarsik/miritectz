$(document).ready(function(){
    $( "#credit-start_date" ).datepicker({
        dateFormat : 'dd-mm-yy',
        minDate : 0,
        maxDate: 365
    });

    $(document).on('click','[name="create-credit-button"]',function (e) {
        e.preventDefault();

        var form = $('#credit-create-form');
        var elements = form.serializeArray();
        var data = new FormData();
        for(var key in elements){
            data.append(elements[key].name,elements[key].value)
        }

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data : data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#creditInfo').html(data);
            }
        });
    });
});