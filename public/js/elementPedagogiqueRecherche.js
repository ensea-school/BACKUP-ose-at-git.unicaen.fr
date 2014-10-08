
elementPedagogiqueRecherche = {
    
    updateValues: function( id, element ){
        var relations = $('#structure-'+id).data('relations');
        var structureId = $('#structure-'+id).val();
        var niveauId = $('#niveau-'+id).val();
        var etapeId = $('#etape-'+id).val();

        var niveauxValues = [];
        for( nId in relations[structureId ? structureId : 'ALL']){
            niveauxValues.push( nId );
        }
        var etapesValues = relations[structureId ? structureId : 'ALL'][niveauId ? niveauId : 'ALL'];

        filterSelect( $('#niveau-'+id), niveauxValues );
        filterSelect( $('#etape-'+id), etapesValues );

        var query = {
            structure: structureId,
            niveau: niveauId,
            etape: etapeId
        };
        var url = $('#structure-'+id).data('default-url') + '?' + $.param( query );

        $('#element-' + id + '-autocomplete').autocomplete("option", "source", url);
    }
}




function filterSelect( select, values ){
    options = select.data('options');
    if (undefined == options){
        options = [];
        select.find('option').each(function() {
            options.push({value: $(this).val(), text: $(this).text()});
        });
        select.data('options', options);
    }
    var lastValue = select.val();
    select.empty();
    for (key in options){
        option = options[key];
        if (option.value == '' || $.inArray(option.value, values) !== -1){
            select.append(
                $('<option>').text(option.text).val(option.value)
            );
            if (option.value == lastValue){
                select.val(lastValue);
            }
        }
    }
}