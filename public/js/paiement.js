/**
 * Formulaire de demande de mise en paiement
 *
 * @constructor
 * @this {DemandeMiseEnPaiement}
 * @param {string} id
 * @returns {DemandeMiseEnPaiement}
 */
function DemandeMiseEnPaiement( id )
{
    this.id      = id;
    this.element = $(".demande-mise-en-paiement#"+this.id);
    this.params  = this.element.data('params');
    this.misesEnPaiementListes = {};
    this.miseEnPaiementSequence = 1;
    this.changes = {};



    /**
     *
     *
     * @returns {undefined}
     */
    this.demanderToutesHeuresEnPaiement = function()
    {
        this.element.find(".heures-non-dmep").click();
    }


    this.changeUpdate = function( miseEnPaiementId, propertyName, newValue )
    {
        if (this.changes[miseEnPaiementId] == undefined){
            this.changes[miseEnPaiementId] = {};
        }
        this.changes[miseEnPaiementId][propertyName] = newValue;
    }

    this.changeInsert = function( miseEnPaiementId, properties )
    {
        this.changes[miseEnPaiementId] = properties;
    }

    this.changeDelete = function( miseEnPaiementId )
    {
        if (0 === miseEnPaiementId.indexOf('new-')){
            delete this.changes[miseEnPaiementId];
        }else{
            this.changes[miseEnPaiementId] = 'removed';
        }
    }

    /**
     *
     * @returns {undefined}
     */
    this.sauvegarder = function()
    {
        alert(JSON.stringify( this.changes ));
    }



    /**
     * Initialisation
     * 
     * @returns {undefined}
     */
    this.init = function()
    {
        var that = this;

        this.element.find(".mise-en-paiement-liste").each( function(){
            that.misesEnPaiementListes[id] = new MiseEnPaiementListe( that, $(this) );
            that.misesEnPaiementListes[id].init();
        } );

        this.element.find(".toutes-heures-non-dmep").on("click", function(){
            that.demanderToutesHeuresEnPaiement();
        });

        this.element.find(".sauvegarde").on("click", function(){
            that.sauvegarder();
        });
    }
}

/**
 *
 * @param {string} id
 * @returns {DemandeMiseEnPaiement}
 */
DemandeMiseEnPaiement.get = function( id )
{
    if (null == DemandeMiseEnPaiement.instances) DemandeMiseEnPaiement.instances = new Array();
    if (null == DemandeMiseEnPaiement.instances[id]) DemandeMiseEnPaiement.instances[id] = new DemandeMiseEnPaiement(id);
    return DemandeMiseEnPaiement.instances[id];
}





/**
 * Liste de mises en paiement (par service ou par service référentiel)
 *
 * @constructor
 * @this {DemandeMiseEnPaiement}
 * @param {DemandeMiseEnPaiement} demandeMiseEnPaiement
 * @param {Object} element
 * @returns {MiseEnPaiementListe}
 */
function MiseEnPaiementListe( demandeMiseEnPaiement, element )
{
    this.demandeMiseEnPaiement = demandeMiseEnPaiement;
    this.id      = element.attr('id');
    this.element = element;
    this.params  = element.data('params');



    /**
     *
     * @param {string} id
     * @returns {DemandeMiseEnPaiement}
     */
    this.removeMiseEnPaiement = function( id )
    {
        miseEnPaiement = this.element.find(".mise-en-paiement#"+id);
        miseEnPaiement.empty();
        this.params['demandes-mep'][id] = 'removed';
        this.demandeMiseEnPaiement.changeDelete(id);
        this.updateHeuresRestantes();
        return this;
    }



    /**
     *
     * @param {Object}  miseEnPaiementListe
     * @param {string}  id
     * @param {float}   heures
     * @param {string}  centreCoutId
     * @returns {DemandeMiseEnPaiement}
     */
    this.addMiseEnPaiement = function( id, heures, centreCoutId, focus )
    {
        var that  = this;

        if (undefined === id){
            id = 'new-'+this.demandeMiseEnPaiement.miseEnPaiementSequence++;
            this.params['demandes-mep'][id] = {
                heures          : heures,
                'centre-cout-id': centreCoutId
            };
            this.demandeMiseEnPaiement.changeInsert( id, this.params['demandes-mep'][id] );
        }else{
            heures       = this.params['demandes-mep'][id]['heures'];
            centreCoutId = this.params['demandes-mep'][id]['centre-cout-id'];
        }

        var max = this.params['heures-total'] - this.params['heures-mep'];

        var out = '<tr class="mise-en-paiement" id="'+id+'"><td class="nombre" style="vertical-align:middle">';
        out += '<input name="heures" class="form-control input-sm" step="any" min="0" max="'+max+'" value="'+heures+'" type="number" />';
        out += '</td><td style="vertical-align:middle">';

        ccCount = Util.json.count(this.params['centres-cout']);
        if( ccCount > 1 ){
            out += '<select name="centre-cout" class="selectpicker" data-width="100%" data-live-search="true">';
            if (undefined === centreCoutId){
                out += '<option value="" selected="selected">&Agrave; préciser ...</option>';
            }
            for ( var ccId in this.params['centres-cout']){
                var children = this.centreCoutGetChildren( ccId );
                if (Util.json.count(children) > 0){
                    out += '<optgroup label="'+this.params['centres-cout'][ccId]['libelle']+'">';
                    for ( var cccId in children){
                        var selected = cccId == centreCoutId ? ' selected="selected"' : '';
                        out += '<option value="'+cccId+'"'+selected+'>'+this.params['centres-cout'][cccId]['libelle']+'</option>';
                    }
                    var selected = ccId == centreCoutId ? ' selected="selected"' : '';
                    out += '<option value="'+ccId+'"'+selected+'>'+this.params['centres-cout'][ccId]['libelle']+'</option>';
                    out += '</optgroup>';
                }else if(this.params['centres-cout'][ccId]['parent'] == null){
                    var selected = ccId == centreCoutId ? ' selected="selected"' : '';
                    out += '<option value="'+ccId+'"'+selected+'>'+this.params['centres-cout'][ccId]['libelle']+'</option>';
                }
            }
            out += '</select>';
        }else if( ccCount == 1 ){
            out += '&nbsp;'+Util.json.first( this.params['centres-cout'] )['libelle'];
        }else{
            out += '<div class="alert alert-danger" role="alert">Aucun centre de coût ne correspond. Saisie impossible.</div>';
        }
        out += '</td><td style="vertical-align:middle">&nbsp;<a role="button" class="action-delete" title="Supprimer la ligne"><span class="glyphicon glyphicon-remove"></span></a></td></tr>';
        this.element.append(out);
        $('select').selectpicker();


        /* Connexion des événements */
        var heuresElement = this.element.find(".mise-en-paiement#"+id+" input[name='heures']");
        heuresElement.on('change', function(){
            that.onHeuresChange( $(this) );
        } );

        this.element.find(".mise-en-paiement#"+id+" select[name='centre-cout']").on('change', function(){
            that.onCentreCoutChange( $(this) );
        } );

        this.element.find(".mise-en-paiement#"+id+" .action-delete").on('click', function(){
            that.removeMiseEnPaiement( id );
        } );

        /* Finalisation */
        if (focus) heuresElement.focus();
        this.updateHeuresRestantes();
        return this;
    }



    /**
     *
     * @param {integer} centreCoutId
     * @returns {undefined}
     */
    this.centreCoutGetChildren = function( centreCoutId )
    {
        var result = {};
        for ( var ccId in this.params['centres-cout']){
            if (this.params['centres-cout'][ccId]['parent'] == centreCoutId){
                result[ccId] = this.params['centres-cout'][ccId];
            }
        }
        return result;
    }


    /**
     *
     * @param {Object} element
     */
    this.onHeuresChange = function( element )
    {
        var miseEnPaiementId = element.parents('.mise-en-paiement').attr('id');
        var heures           = parseFloat( element.val() == '' ? 0 : element.val() );

        this.demandeMiseEnPaiement.changeUpdate( miseEnPaiementId, 'heures', heures );
        if (heures > 0){
            this.params['demandes-mep'][miseEnPaiementId]['heures'] = heures;
            this.updateHeuresRestantes();
        }else{
            this.removeMiseEnPaiement( miseEnPaiementId );
        }
    }



    /**
     *
     * @param {Object} element
     * @returns {undefined}
     */
    this.onCentreCoutChange = function( element )
    {
        var miseEnPaiementId = element.parents('.mise-en-paiement').attr('id');
        var centreCoutId     = element.val();
        this.demandeMiseEnPaiement.changeUpdate( miseEnPaiementId, 'centre-cout-id', centreCoutId );
    }



    /**
     *
     * @returns {undefined}
     */
    this.onAddHeuresRestantes = function()
    {
        if (this.params['heures-non-dmep'] < 0){
            alert('Il est impossible d\'ajouter des HETD négatifs.');
        }else{
            this.addMiseEnPaiement( undefined, this.params['heures-non-dmep'], undefined, true );
        }
    }



    /**
     *
     * @returns {undefined}
     */
    this.updateHeuresRestantes = function()
    {
        this.params['heures-dmep']     = 0;
        for( var miseEnPaiementId in this.params['demandes-mep'] ){
            if (this.params['demandes-mep'][miseEnPaiementId] !== 'removed'){
                this.params['heures-dmep'] += this.params['demandes-mep'][miseEnPaiementId]['heures'];
            }
        }
        this.params['heures-non-dmep'] = Math.round( (this.params['heures-total'] - this.params['heures-mep'] - this.params['heures-dmep'])*100 ) / 100;

        this.element.find('.heures-non-dmep').html( Util.formattedHeures(this.params['heures-non-dmep']) );

        if (0 == this.params['heures-non-dmep']){
            this.element.find('.heures-non-dmep').parents('tr').hide();
            this.element.addClass('bg-success');
        }else{
            this.element.find('.heures-non-dmep').parents('tr').show();
            this.element.removeClass('bg-success');
        }
    }



    /**
     * Initialisation des lignes du formulaire à partir des données
     *
     * @returns {undefined}
     */
    this.populate = function()
    {
        for( var miseEnPaiementId in this.params['demandes-mep']){
            this.addMiseEnPaiement( miseEnPaiementId );
        }
    }



    /**
     * Initialisation
     *
     * @returns {undefined}
     */
    this.init = function()
    {
        var that = this;
        this.element.find('.heures-non-dmep').on('click', function(){ 
            that.onAddHeuresRestantes();
        } );
        this.populate();
    }
}