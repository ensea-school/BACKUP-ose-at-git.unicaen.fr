

function ElementPedagogiqueRecherche( id )
{
    this.id = id;
    this.element = $(".element-pedagogique-recherche#"+this.id);
    this.relations = this.element.data('relations');

    this.updateValues = function()
    {
        var structureId = this.getStructureElement().val();
        var niveauId    = this.getNiveauElement().val();
        var etapeId     = this.getFormationElement().val();
        var lastEtapeId = etapeId;

        var niveauxValues = [];
        for( nId in this.relations[structureId ? structureId : 'ALL']){
            niveauxValues.push( nId );
        }
        var etapesValues = this.relations[structureId ? structureId : 'ALL'][niveauId ? niveauId : 'ALL'];
        if ($.inArray(etapeId, etapesValues) == -1){
            etapeId = "";
        }

        this.filterSelect( this.getNiveauElement(), niveauxValues );
        this.filterSelect( this.getFormationElement(), etapesValues );

        var query = {
            structure: structureId,
            niveau: niveauId,
            etape: etapeId
        };
        var url = this.element.data('default-url') + '?' + $.param( query );

        var that = this;
        if (etapeId != ""){
            this.setElementState('wait');
            $.get(url, function (data) {
                that.populateElements(data);
            });
        }else{
            this.getElementAutocompleteElement().autocomplete("option", "source", url);
            this.setElementState('search');
        }
    }

    this.updateElementValue = function()
    {
        var id = this.getElementListeElement().val();
        var label = this.getElementListeElement().find(":selected").text();

        this.element.find('input#element').attr('value', id );
        this.getElementAutocompleteElement().attr('value', label);
    }

    this.filterSelect = function( select, values )
    {
        var ul = select.next().find('ul');
        select.find('option').each( function(){

            var li = ul.find("li[data-original-index='"+this.index+"']");

            if (this.index == 0 || $.inArray(this.value, values) !== -1){
                li.show();
            }else{
                if (select.val() == this.value){
                    select.selectpicker('val', '');
                }
                li.hide();
            }

        } );
    }

    this.setElementState = function( state )
    {
        switch( state ){
        case 'liste':
            this.element.find("#ep-liste").show();
            this.element.find("#ep-wait").hide();
            this.element.find("#ep-search").hide();
        break;
        case 'wait':
            this.element.find("#ep-liste").hide();
            this.element.find("#ep-wait").show();
            this.element.find("#ep-search").hide();
        break;
        case 'search':
            this.element.find("#ep-liste").hide();
            this.element.find("#ep-wait").hide();
            this.element.find("#ep-search").show();
        break;
        }
    }

    this.populateElements = function( data )
    {
        var select = this.getElementListeElement();
        var value = this.element.find('input#element').attr('value' );

        select.empty();
        if (Util.json.count(data) > 1){
            select.append(
                $('<option>').text('(Tous)').val('')
            );
        }
        for( var i in data ){
            select.append(
                $('<option>').text(data[i].label).val(data[i].id)
            );
        }
        select.val( value );
        select.selectpicker('refresh');
        this.setElementState( 'liste' );
        this.updateElementValue();
    }

    this.getStructureElement = function()
    {
        return this.element.find('select#structure');
    }

    this.getNiveauElement = function()
    {
        return this.element.find('select#niveau');
    }

    this.getFormationElement = function()
    {
        return this.element.find('select#formation');
    }

    this.getElementElement = function()
    {
        return this.element.find('select#element');
    }

    this.getElementListeElement = function()
    {
        return this.element.find('select#element-liste');
    }

    this.getElementAutocompleteElement = function()
    {
        return this.element.find('input#element-autocomplete');
    }

    this.init = function()
    {
        var that = this;

        $('.selectpicker').selectpicker();
        this.getStructureElement().change( function(){ that.updateValues(); } );
        this.getNiveauElement   ().change( function(){ that.updateValues(); } );
        this.getFormationElement().change( function(){ that.updateValues(); } );
        this.getElementListeElement().change( function(){ that.updateElementValue(); } );
        this.updateValues();
    }

}

/**
 *
 * @param {string} id
 * @returns {PaiementMiseEnPaiementForm}
 */
ElementPedagogiqueRecherche.get = function( id )
{
    if (null == ElementPedagogiqueRecherche.instances) ElementPedagogiqueRecherche.instances = new Array();
    if (null == ElementPedagogiqueRecherche.instances[id]) ElementPedagogiqueRecherche.instances[id] = new ElementPedagogiqueRecherche(id);
    return ElementPedagogiqueRecherche.instances[id];
}




