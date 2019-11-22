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

});
