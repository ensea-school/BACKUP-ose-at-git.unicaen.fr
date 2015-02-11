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

    this.init = function()
    {
        var that = this;

        this.element.find(".mise-en-paiement-liste").each( function(){
            that.misesEnPaiementListes[id] = new MiseEnPaiementListe( that, $(this) );
            that.misesEnPaiementListes[id].init();
        } );
    }
}

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
        }else{
            heures       = this.params['demandes-mep'][id]['heures'];
            centreCoutId = this.params['demandes-mep'][id]['centre-cout-id'];
        }

        var max = this.params['heures-total'] - this.params['heures-mep'];

        var out = '<tr class="mise-en-paiement" id="'+id+'"><td class="nombre">';
        out += '<input name="heures" class="form-control input-sm" step="any" min="0" max="'+max+'" value="'+heures+'" type="number" />';
        out += '</td><td style="vertical-align:middle">';

        ccCount = Util.json.count(this.params['centres-cout']);
        if( ccCount > 1 ){
            out += '<select name="centre-cout" class="form-control input-sm">';
            if (undefined === centreCoutId){
                out += '<option value="" selected="selected">&nbsp;</option>';
            }
            for ( var ccId in this.params['centres-cout']){
                var selected = ccId == centreCoutId ? ' selected="selected"' : '';
                out += '<option value="'+ccId+'"'+selected+'>'+this.params['centres-cout'][ccId]+'</option>';
            }
            out += '</select>';
        }else if( ccCount == 1 ){
            out += '&nbsp;'+Util.json.first( this.params['centres-cout'] );
        }else{
            out += '<div class="alert alert-danger" role="alert">Aucun centre de coût ne correspond. Saisie impossible.</div>';
        }
        out += '</td><td style="vertical-align:middle">&nbsp;<a role="button" class="btn btn-xs btn-default action-delete"><span class="glyphicon glyphicon-remove"></span> Supprimer</a></td></tr>';
        this.element.append(out);

        var heuresElement = this.element.find(".mise-en-paiement#"+id+" input[name='heures']");
        heuresElement.on('change', function(){
            that.onHeuresChange( $(this) );
        } );
        this.element.find('.action-delete').on('click', function(){
            that.removeMiseEnPaiement( id );
        } );

        if (focus) heuresElement.focus();
        this.updateHeuresRestantes();
        return this;
    }



    /**
     *
     * @param {Object} element
     */
    this.onHeuresChange = function( element )
    {
        var miseEnPaiementId = element.parents('.mise-en-paiement').attr('id');
        var heures           = parseFloat( element.val() == '' ? 0 : element.val() );

        if (heures > 0){
            this.params['demandes-mep'][miseEnPaiementId]['heures'] = heures;
            this.updateHeuresRestantes();
        }else{
            this.removeMiseEnPaiement( miseEnPaiementId );
        }
    }

    this.onAddHeuresRestantes = function()
    {
        if (this.params['heures-non-dmep'] < 0){
            alert('Il est impossible d\'ajouter des HETD négatifs.');
        }else{
            this.addMiseEnPaiement( undefined, this.params['heures-non-dmep'], undefined, true );
        }
    }

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
        }else{
            this.element.find('.heures-non-dmep').parents('tr').show();
        }
    }



    this.populate = function()
    {
        for( var miseEnPaiementId in this.params['demandes-mep']){
            this.addMiseEnPaiement( miseEnPaiementId );
        }
            //heuresRestantes = Math.round( (params['heures-total'] - params['heures-mep'] - heuresDemandesMep)*100, 2) / 100;
            //if (heuresRestantes > 0){
            //    that.addMiseEnPaiement( $(this), heuresRestantes, null );
            //}

        //$(this).find('.heures-restant-mep').html( Util.formattedHeures(params['heures-total'] - params['heures-mep']) );
    }



    this.init = function()
    {
        var that = this;
        this.element.find('.heures-non-dmep').on('click', function(){ 
            that.onAddHeuresRestantes();
        } );
        this.populate();
    }
}