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

    $("#check-all").click(function () {
        var check = $(this).prop('checked');
        if (check) {
            $("#form-reconduction tbody input[type='checkbox']").prop('checked', 'checked');
        } else {
            $("#form-reconduction tbody input[type='checkbox']").prop('checked', '');
        }
    });

});
