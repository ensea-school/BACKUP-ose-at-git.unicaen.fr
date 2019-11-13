$("document").ready(function()
{

    $("#reconduction-button").click(function()
    {
        $("#modal-dialog").modal('show');
    });

    $("#btnYes").click(function()
    {
        $('#form-reconduction').submit();
    });
    $("body").on("change", "#reconduction-filters select", function(event)
    {
        $("*").css('cursor', 'wait');
        var structure = $('select.structure').val();
        var niveau = $('select.niveau').val();
        var etape = $('select.etape').val();
        var query = '';
        if(structure){ query += 'structure='+structure+'&';}
        if(niveau){ query += 'niveau='+niveau+'&';}
        if(etape){query += 'etape='+etape+'&';}
        var url = document.location.href;
        var urlBase = url.substr(0, url.indexOf('?'));
        urlBase += '?' + query;
        $(location).attr('href', urlBase);
    });

    $('input.checkbox-formation').change(function()
    {
        var check = $(this).prop('checked');
        if(check)
        {
            $(this).parents('table').find('.checkbox-element').each(function()
            {
                $(this).prop('checked','checked');
            });
        }
        else{
            $(this).parents('table').find('.checkbox-element').each(function()
            {
                $(this).prop('checked','');
            });
        }
    });
    $('input.checkbox-element').change(function()
    {
        var check = $(this).prop('checked');
        if(check)
        {
            $(this).parents('table').find('.checkbox-formation').each(function()
            {
                $(this).prop('checked','checked');
            });
        }
    });

});
