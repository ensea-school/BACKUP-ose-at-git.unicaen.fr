
elementPedagogiqueRecherche = {

    updateValues: function( id, element ){
        var relations = $('#structure-'+id).data('relations');

        var structureId = $('#structure-'+id).val();
        var niveauId = $('#niveau-'+id).val();
        var etapeId = $('#etape-'+id).val();

        $('#niveau-'+id+' option').each( function(){
            if ( structureId ){
                if ('' == $(this).attr('value')){
                    $(this).show();
                }else if ( relations[structureId].hasOwnProperty($(this).attr('value')) ){
                    $(this).show();
                }else{
                    $(this).hide();
                    if (niveauId == $(this).val()) $('#niveau-'+id).val('');
                }
            }else{
                $(this).show();
            }
        } );

        $('#etape-'+id+' option').each( function(){
            if ( niveauId || structureId ){
                if ('' == $(this).attr('value')){
                    $(this).show();
                }else if ( $.inArray(parseInt($(this).attr('value')), relations[structureId ? structureId : 'ALL'][niveauId ? niveauId : 'ALL']) != -1 ){
                    $(this).show();
                }else{
                    $(this).hide();
                    if (etapeId == $(this).val()) $('#etape-'+id).val('');
                }
            }else{
                $(this).show();
            }
        } );

        var query = {
            structure: structureId,
            niveau: niveauId,
            etape: etapeId
        };
        var url = $('#structure-'+id).data('default-url') + '?' + $.param( query );

        $('#element-' + id + '-autocomplete').autocomplete("option", "source", url);
    }
}