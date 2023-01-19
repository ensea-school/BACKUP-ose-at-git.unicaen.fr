$("document").ready(function ()
{

    $("body").on("click", "#confirm-reconduction", function () {
        $("#form-reconduction").submit();
    });

    $("body").on("change", "#reconduction-filters select", function (event)
    {
        $("*").css('cursor', 'wait');
        var structure = $('select.structure').val();
        var niveau = $('select.niveau').val();
        var etape = $('select.etape').val();
        var query = '';
        if (structure) {
            query += 'structure=' + structure + '&';
        }
        if (niveau) {
            query += 'niveau=' + niveau + '&';
        }
        if (etape) {
            query += 'etape=' + etape + '&';
        }
        var url = document.location.href;
        var urlBase = url.substr(0, url.indexOf('?'));
        urlBase += '?' + query;
        $(location).attr('href', urlBase);
    });

    $('input.checkbox-formation').change(function ()
    {
        var check = $(this).prop('checked');
        if (check) {
            $(this).parents('table').find('.checkbox-element').each(function ()
            {
                $(this).prop('checked', 'checked');
            });
        } else {
            $(this).parents('table').find('.checkbox-element').each(function ()
            {
                $(this).prop('checked', '');
            });
        }
        //Disable submit button if any check box are click
        if ($('#form-reconduction input[type="checkbox"]:checked').length >= 1) {
            $('#reconduction-button').prop('disabled', false);
        } else {
            $('#reconduction-button').prop('disabled', true);
        }
    });
    $('input.checkbox-element').change(function ()
    {
        var check = $(this).prop('checked');
        if (check) {
            $(this).parents('table').find('.checkbox-formation').each(function ()
            {
                var disabled = $(this).prop('disabled');
                console.log(disabled);
                if (!disabled) {
                    $(this).prop('checked', 'checked');
                }
            });
        }
        //Disable submit button if any check box are click
        if ($('#form-reconduction input[type="checkbox"]:checked').length > 0) {
            $('#reconduction-button').prop('disabled', false);
        } else {
            $('#reconduction-button').prop('disabled', true);
        }
    });

});
