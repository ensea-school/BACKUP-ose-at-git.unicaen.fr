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
    this.validation = true;
    this.validationMessage = undefined;



    /**
     *
     *
     * @returns {undefined}
     */
    this.demanderToutesHeuresEnPaiement = function()
    {
        this.element.find(".heures-non-dmep:visible").click();
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
     * @returns {Boolean}
     */
    this.valider = function()
    {
        var result = true;
        for( var id in this.misesEnPaiementListes ){
            if (! this.misesEnPaiementListes[id].valider()) result = false;
        }
        return result;
    }



    /**
     *
     * @returns {boolean}
     */
    this.sauvegarder = function()
    {
        if (! this.valider()){
            alert('Enregistrement impossible');
            return false;
        }
        this.element.find("form input[name='changements']").val( JSON.stringify( this.changes ) );
        return true;
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
            var id = $(this).attr('id');

            that.misesEnPaiementListes[id] = new MiseEnPaiementListe( that, $(this) );
            that.misesEnPaiementListes[id].init();
        } );

        this.element.find(".toutes-heures-non-dmep").on("click", function(){
            that.demanderToutesHeuresEnPaiement();
        });

        this.element.find("form").on("submit", function(){
            return that.sauvegarder();
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
    this.validation = true;



    /**
     * Détermine si la liste est en lecture seule ou non
     *
     * @returns {boolean}
     */
    this.isReadOnly = function()
    {
        return this.element.hasClass('read-only');
    }


    this.valider = function()
    {
        var that = this;

        this.validation = true;

        if (this.isReadOnly()) return true; // pas de validation puisque c'est en lecture seule!!

        if (this.params['heures-non-dmep'] < 0){
            this.showError( 'Trop d\'heures de paiement ont été demandées.' );
        }

        this.element.find("select[name='centre-cout']").each( function(){
            if ($(this).val() == ''){
                that.showError('Centre de coût à définir.');
            }
        } );

        return this.validation;
    }



    this.showError = function( errorStr )
    {
        var out = '<div class="alert alert-danger alert-dismissible" role="alert">'
                + '<span class="glyphicon glyphicon-exclamation-sign"></span> '
                + errorStr
                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>'
                + '</div>';
        this.element.parents('.type-heures').prepend( out );
        this.validation = false;
    }



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
                'centre-cout-id': centreCoutId,
                'read-only'     : false,
                'validation'    : null
            };

            var mepParams               = this.params['mep-defaults'];
            mepParams['heures']         = this.params['demandes-mep'][id]['heures'];
            mepParams['centre-cout-id'] = this.params['demandes-mep'][id]['centre-cout-id'];
            this.demandeMiseEnPaiement.changeInsert( id, mepParams );
        }

        this.element.append( this.renderMiseEnPaiement( id ) );
        $('.selectpicker').selectpicker();

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



    this.renderMiseEnPaiement = function( id )
    {
        var data = this.params['demandes-mep'][id];

        var out = '<tr class="mise-en-paiement" id="'+id+'"><td class="nombre" style="vertical-align:middle">';
        out += this.renderHeures( data );
        out += '</td><td style="vertical-align:middle">';
        out += this.renderCentreCout( data );
        out += '</td><td style="vertical-align:middle;text-align:center">';
        out += this.renderActions( data );
        out += '</td></tr>';

        return out;
    }



    this.renderHeures = function( data )
    {
        var out;
        var max = this.params['heures-total'] - this.params['heures-mep'];

        if (data['read-only']){
            out = Util.formattedHeures( data['heures'] );
        }else{
            out = '<input name="heures" class="form-control input-sm" step="any" min="0" max="'+max+'" value="'+data['heures']+'" type="number" />';
        }

        return out;
    }



    this.renderCentreCout = function( data )
    {
        var outC = '';
        
        ccCount = Util.json.count(this.params['centres-cout']);
        if( ccCount == 1 || data['read-only'] ){
            if (data['validation'] != undefined){
                outC += '<abbr title="Validé par '+data['validation']['utilisateur'] + ' le ' + data['validation']['date']+'">';
            }
            outC += '&nbsp;'+Util.json.first( this.params['centres-cout'] )['libelle'];
            if (data['validation'] != undefined){
                outC += ' </span></abbr>';
            }
        }else if( ccCount > 1 ){
            outC = '<select name="centre-cout" class="selectpicker" data-width="100%" data-live-search="true">';
            if (undefined == data['centre-cout-id']){
                outC += '<option value="" selected="selected">&Agrave; préciser ...</option>';
            }
            for ( var ccId in this.params['centres-cout']){
                var children = this.centreCoutGetChildren( ccId );
                if (Util.json.count(children) > 0){
                    outC += '<optgroup label="'+this.params['centres-cout'][ccId]['libelle']+'">';
                    for ( var cccId in children){
                        var selected = cccId == data['centre-cout-id'] ? ' selected="selected"' : '';
                        outC += '<option value="'+cccId+'"'+selected+'>'+this.params['centres-cout'][cccId]['libelle']+'</option>';
                    }
                    var selected = ccId == data['centre-cout-id'] ? ' selected="selected"' : '';
                    outC += '<option value="'+ccId+'"'+selected+'>'+this.params['centres-cout'][ccId]['libelle']+'</option>';
                    outC += '</optgroup>';
                }else if(this.params['centres-cout'][ccId]['parent'] == null){
                    var selected = ccId == data['centre-cout-id'] ? ' selected="selected"' : '';
                    outC += '<option value="'+ccId+'"'+selected+'>'+this.params['centres-cout'][ccId]['libelle']+'</option>';
                }
            }
            outC += '</select>';
        }else{
            outC = '<div class="alert alert-danger" role="alert">Aucun centre de coût ne correspond. Saisie impossible.</div>';
        }

        return outC;
    }



    this.renderActions = function( data )
    {
        var outA;

        if (data['read-only']){
            if (data['validation'] != undefined){
                outA = '<span class="glyphicon glyphicon-ok-circle" title="Validé par '+data['validation']['utilisateur'] + ' le ' + data['validation']['date']+'">';
            }else{
                outA = '';
            }
        }else{
           outA = '<a role="button" class="action-delete" title="Supprimer la ligne"><span class="glyphicon glyphicon-remove"></span></a>';
        }

        return outA;
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
            this.addMiseEnPaiement( undefined, this.params['heures-non-dmep'], this.params['default-centre-cout'], true );
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





function PaiementMiseEnPaiementRechercheForm( id )
{
    this.id = id;
    this.element = $(".paiement-mise-en-paiement-recherche-form#"+this.id);

    this.onStructureChange = function()
    {
        this.intervenantsSelectNone();
        var periodeElement = this.getPeriodeElement()
        if (periodeElement){
            periodeElement.val('');
        }
        this.getSuiteElement().click();
    }

    this.onPeriodeChange = function()
    {
        this.intervenantsSelectNone();
        this.getSuiteElement().click();
    }

    this.onIntervenantsChange = function()
    {
        if ( this.getIntervenantsElement().is(':visible') ){
            this.getSuiteElement().hide();
            if (this.getIntervenantsElement().val() == null){
                this.getAfficherElement().hide();
                this.getExporterElement().hide();
            }else{
                this.getAfficherElement().show();
                this.getExporterElement().show();
            }
        }else{
            this.getSuiteElement().show();
            this.getAfficherElement().hide();
            this.getExporterElement().hide();
        }
    }

    this.intervenantsSelectAll = function()
    {
        this.getIntervenantsElement().find("option").prop("selected", "selected");
        this.onIntervenantsChange();
    }

    this.intervenantsSelectNone = function()
    {
        this.getIntervenantsElement().val([]);
        this.onIntervenantsChange();
    }

    this.init = function()
    {
        var that = this;
        this.getStructureElement().change(function(){ that.onStructureChange() });
        this.getPeriodeElement().change(function(){ that.onPeriodeChange() });
        this.getIntervenantsElement().change(function(){ that.onIntervenantsChange() });

        var iediv = this.getIntervenantsElement().parent();
        iediv.append(
            '<a class="btn btn-default btn-xs select-all" role="button"><span class="glyphicon glyphicon-ok-circle"></span> Tout sélectionner</a>'
           +'<a class="btn btn-default btn-xs select-none" role="button"><span class="glyphicon glyphicon-remove-circle"></span> Ne rien sélectionner</a>'
        );
        iediv.find(".select-all").click( function(){ that.intervenantsSelectAll() } );
        iediv.find(".select-none").click( function(){ that.intervenantsSelectNone() } );

        $("body").on("mise-en-paiement-form-submit", function(event, data) {
            if ($("div .messenger, div .alert", event.div).length ? false : true){
                
                document.location.href = event.a.data('url-redirect');
            }
        });

        this.onIntervenantsChange();
    }

    this.getStructureElement = function()
    {
        return this.element.find('[name="structure"]');
    }

    this.getPeriodeElement = function()
    {
        return this.element.find('[name="periode"]');
    }

    this.getIntervenantsElement = function()
    {
        return this.element.find('[name="intervenants[]"]');
    }

    this.getSuiteElement = function()
    {
        return this.element.find('[name="suite"]');
    }

    this.getAfficherElement = function()
    {
        return this.element.find('[name="afficher"]');
    }

    this.getExporterElement = function()
    {
        return this.element.find('[name="exporter"]');
    }

    this.getEtat = function()
    {
        return this.element.parents('.filter').data('etat');
    }
}

/**
 *
 * @param {string} id
 * @returns {PaiementMiseEnPaiementRechercheForm}
 */
PaiementMiseEnPaiementRechercheForm.get = function( id )
{
    if (null == PaiementMiseEnPaiementRechercheForm.instances) PaiementMiseEnPaiementRechercheForm.instances = new Array();
    if (null == PaiementMiseEnPaiementRechercheForm.instances[id]) PaiementMiseEnPaiementRechercheForm.instances[id] = new PaiementMiseEnPaiementRechercheForm(id);
    return PaiementMiseEnPaiementRechercheForm.instances[id];
}





function PaiementMiseEnPaiementForm( id )
{
    this.id = id;
    this.element = $(".paiement-mise-en-paiement-form#"+this.id);

    this.onPeriodeChange = function()
    {
        var periodeId = this.getPeriodeElement().val();
        var dates = this.element.data('dates-mise-en-paiement');

        this.getDateMiseEnPaiementElement().val( dates[periodeId] );
    }

    this.init = function()
    {
        var that = this;
        this.getPeriodeElement().change(function(){ that.onPeriodeChange() });
    }

    this.getPeriodeElement = function()
    {
        return this.element.find('[name="periode"]');
    }

    this.getDateMiseEnPaiementElement = function()
    {
        return this.element.find('[name="date-mise-en-paiement"]');
    }
}

/**
 *
 * @param {string} id
 * @returns {PaiementMiseEnPaiementForm}
 */
PaiementMiseEnPaiementForm.get = function( id )
{
    if (null == PaiementMiseEnPaiementForm.instances) PaiementMiseEnPaiementForm.instances = new Array();
    if (null == PaiementMiseEnPaiementForm.instances[id]) PaiementMiseEnPaiementForm.instances[id] = new PaiementMiseEnPaiementForm(id);
    return PaiementMiseEnPaiementForm.instances[id];
}