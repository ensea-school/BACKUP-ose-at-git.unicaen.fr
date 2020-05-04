$("document").ready(function ()
{

    $("body").on("change", "#reconduction-filters select", function (event)
    {
        $("*").css('cursor', 'wait');
        var structure = $('select.structure').val();
        var query = '';
        if (structure) {
            query += 'structure=' + structure + '&';
        }
        var url = document.location.href;
        var urlBase = url.substr(0, url.indexOf('?'));
        urlBase += '?' + query;
        $(location).attr('href', urlBase);
    });

    $("body").on("click", "#confirm-reconduction", function () {

        $("#form-reconduction").submit();
    });

    $("#check-all").click(function () {
        var check = $(this).prop('checked');
        if (check) {
            $("#form-reconduction tbody input[type='checkbox']").each(function() {
                if(!$(this).prop('disabled')) {
                    $(this).prop('checked', 'checked');
                }
            });
        } else {
            $("#form-reconduction tbody input[type='checkbox']").prop('checked', '');
        }
    });

    $('input.checkbox-element').change(function ()
    {
        var check = $(this).prop('checked');
        if (!check) {
            $("#check-all").prop('checked', false);
        }
        //Disable submit button if any check box are click
        if ($('#form-reconduction td .checkbox-element:checked').length > 0) {
            $('#reconduction-modulateur-button').prop('disabled', false);
        } else {
            $('#reconduction-modulateur-button').prop('disabled', true);
        }
    });

});
