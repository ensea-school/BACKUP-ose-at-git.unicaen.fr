/***************************************************************************************************************************************************
    Divers
/***************************************************************************************************************************************************/

$( document ).ajaxError(function( event, jqxhr, settings, exception ) {
    if ($('body').hasClass('development')){
        errorDialog.show( 'Une erreur '+jqxhr.status + '('+jqxhr.statusText+') est survenue', jqxhr.responseText );
    }
    console.log( jqxhr );
});

function errorDialog() {}
errorDialog.show = function( title, text ){
    if (undefined === errorDialog.sequence){
        errorDialog.sequence = 1;
    }else{
        errorDialog.sequence += 1;
    }

    $(document.body).append(
        '<div id="error-dialog-'+errorDialog.sequence+'" class="scr-center">'
        +'<div class="alert alert-danger alert-dismissable">'
            +'<button type="button" class="close" onclick="document.getElementById(\'error-dialog-'+errorDialog.sequence+'\').style.display=\'none\';" data-dismiss="alert" aria-hidden="true">&times;</button>'
            +'<h1>'+title+'</h1>'+text
            +'<br /><hr /><button type="button" onclick="document.getElementById(\'error-dialog-'+errorDialog.sequence+'\').style.display=\'none\';" class="btn btn-danger">Fermer</button>'
            +'</div>'
       +'</div>');
}

function Url( route, data ){
    var getArgs = data ? $.param( data ) : null;
    return Url.getBase() + route + (getArgs ? '?'+getArgs : '');
}

Url.getBase = function(){
    sc = document.getElementsByTagName("script");
    for(idx = 0; idx < sc.length; idx++)
    {
        s = sc.item(idx);
        if(s.src && s.src.match(/js\/app\.js$/)){
            return s.src.replace( /()js\/app\.js$/, '$1');;
        }
    }
}



/***************************************************************************************************************************************************
    Propre à l'affichage des services
/***************************************************************************************************************************************************/

function Service( id ) {

    this.id = id;

    this.showHideDetails = function( serviceA ){

        var state = $.data(serviceA,'state');
        var tr = $('#service-' + this.id + '-volume-horaire-tr');

        if (('show' == state || 'none' == tr.css('display')) && 'hide' != state ){
            $(serviceA).html('<span class="glyphicon glyphicon-chevron-up"></span>');
            tr.show(200);
        }else{
            $(serviceA).html('<span class="glyphicon glyphicon-chevron-down"></span>');
            tr.hide(200);
        }
    }

    this.showInterneExterne = function(){
        if ('service-interne' == this.id){
            $('#element-interne').show();
            $('#element-externe').hide();
            $("input[name='service\\[etablissement\\]\\[label\\]']").val('');
            $("input[name='service\\[etablissement\\]\\[id\\]']").val('');
        }else{
            $('#element-interne').hide();
            $("input[name='service\\[element-pedagogique\\]\\[element\\]\\[label\\]']").val('');
            $("input[name='service\\[element-pedagogique\\]\\[element\\]\\[id\\]']").val('');
            $('#element-externe').show();
        }
        this.refreshFormVolumesHoraires(
            $('form#service input[name="service\\[element-pedagogique\\]\\[element\\]\\[id\\]"]').val(),
            $("input[name='service\\[etablissement\\]\\[id\\]']").val()
        );
    }

    this.refreshFormVolumesHoraires = function( elementId, etablissementId ){
        $('form#service div#volumes-horaires').refresh({
            element      : elementId,
            etablissement: etablissementId
        }, function(){
            $('form#service div#volumes-horaires input.form-control').each( function(element){
                $(this).val('0');
            } );
        });
    }

    this.onAfterAdd = function(){
        var url = Url("service/rafraichir-ligne/"+this.id+"/"+$("#services").data('type-volume-horaire'), {
            'only-content'                  : 0,
            'details'                       : 1,
            'intervenant'                   : $("#services").data('intervenant'),
            'structure'                     : $("#services").data('structure'),
            'types-intervention'            : $("#services").data('types-intervention'),
            'types-intervention-visibility' : $("#services").data('types-intervention-visibility'),
        });
        $.get( url, function( data ) {
            $( "#service-resume" ).refresh(); // Si on est dans le résumé
            $('#services > tbody:last').append(data);
            Service.refreshFiltres();
            Service.refreshTotaux();
            Service.refreshWorkflowNavigation();
        });
    }

    this.onAfterModify = function(){
        $( "#service-"+this.id+"-ligne" ).refresh( {
            details                         : $('#service-'+this.id+'-volume-horaire-tr').css('display') == 'none' ? '0' : '1',
            'types-intervention-visibility' : $("#services").data('types-intervention-visibility'),
        } );
        $( "#service-"+this.id+"-volume-horaire-td" ).refresh();
        Service.refreshFiltres();
        Service.refreshTotaux();
        Service.refreshWorkflowNavigation();
    }

    this.onAfterDelete = function(){
        $('#service-'+this.id+'-volume-horaire-tr').remove();
        $( "#service-"+this.id+"-ligne" ).remove();
        Service.refreshFiltres();
        Service.refreshTotaux();
        Service.refreshWorkflowNavigation();
    }

}

Service.get = function( id ){
    if (null == Service.services) Service.services = new Array();
    if (null == Service.services[id]) Service.services[id] = new Service(id);
    return Service.services[id];
}

Service.init = function(){

    $("body").on("service-modify-message", function(event, data) {
        var id = null;
        if ($("div .messenger, div .alert", event.div).length ? false : true){
            event.div.modal('hide'); // ferme la fenêtre modale
            for( i in data ){
                if (data[i].name == 'service[id]'){
                    id = data[i].value;
                }
            }

            if (id){
                Service.get(id).onAfterModify();
            }
        }
    });

    $("body").on("service-add-message", function(event, data) {
        var id = null;
        if ($("div .messenger, div .alert", event.div).length ? false : true){
            event.div.modal('hide'); // ferme la fenêtre modale
            for( i in data ){
                if (data[i].name == 'service[id]'){
                    id = data[i].value;
                }
            }

            if (id){
                Service.get(id).onAfterAdd();
            }
        }
    });

    $("body").on("service-delete-message", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        Service.get(event.a.data('id')).onAfterDelete();
    });

    /* Détection de changement d'état du radio interne-externe */
    $("body").on('change', 'form#service input[name="service\\[interne-externe\\]"]', function(){
        Service.get(this.value).showInterneExterne( $( this ).val() );
    });

    /* Détection des changements d'éléments pédagogiques dans le formulaire de saisie */
    $( "body" ).on( "autocompleteselect", 'form#service input[name="service\\[element-pedagogique\\]\\[element\\]\\[label\\]"]', function( event, ui ) {
        var serviceId = $('form#service input[name="service\\[id\\]"]').val();
        var elementId = ui.item.id;
        Service.get(serviceId).refreshFormVolumesHoraires( elementId, $("input[name='service\\[etablissement\\]\\[id\\]']").val() );
    } );

    $(".service-show-all-details").on('click', function(){ Service.showAllDetails(); });
    $(".service-hide-all-details").on('click', function(){ Service.hideAllDetails(); });

    VolumeHoraire.init();
}

Service.showAllDetails = function(){
    $('.service-details-button').data('state', 'show');
    $('.service-details-button').click();
    $('.service-details-button').data('state', '');
}

Service.hideAllDetails = function(){
    $('.service-details-button').data('state', 'hide');
    $('.service-details-button').click();
    $('.service-details-button').data('state', '');
}

Service.refreshFiltres = function(){
    if ($( "#filtres" ).length > 0){
        $( "#filtres" ).refresh();
    }
}

Service.refreshTotaux = function(){
    $("#services tfoot").refresh( {}, Service.onListeChanged );
    $("#formule-totaux-hetd").refresh();
}

Service.onListeChanged = function(){
    Service.showHideTypesIntervention();
}

Service.showHideTypesIntervention = function(){
    var typesIntervention = $('table#services').data('types-intervention').split(",");
    var typesInterventionVisibility = '';
    for(var key in typesIntervention){
        var visibility = $('table#services tfoot td.type-intervention.'+typesIntervention[key]).is(':visible');
        $('table#services tbody th.type-intervention.'+typesIntervention[key]+', table#services tbody td.type-intervention.'+typesIntervention[key]).each( function(){
            if ( visibility ){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
        if (visibility){
            if (typesInterventionVisibility != '') typesInterventionVisibility += ',';
            typesInterventionVisibility += typesIntervention[key];
        }
    }
    $('table#services').data('types-intervention-visibility', typesInterventionVisibility);
}

/**
 * Mise à jour de la div affichant le bouton pointant vers l'étape suivante (workflow).
 * @returns void
 */
Service.refreshWorkflowNavigation = function() {
    $("#wf-nav-next").refresh();
}






/***************************************************************************************************************************************************
    Propre à l'affichage des volumes horaires
/***************************************************************************************************************************************************/

function VolumeHoraire( id ) {

    this.id = id;
}

VolumeHoraire.get = function( id ){
    if (null === VolumeHoraire.volumeHoraires) VolumeHoraire.volumeHoraires = new Array();
    if (null === VolumeHoraire.volumeHoraires[id]) VolumeHoraire.volumeHoraires[id] = new VolumeHoraire(id);
    return VolumeHoraire.volumeHoraires[id];
}

VolumeHoraire.init = function(){
    $("body").tooltip({
        selector: 'a.volume-horaire',
        placement: 'top',
        title: "Cliquez pour ouvrir/fermer le formulaire de modification..."
    });

    $("body").on('save-volume-horaire', function(event,data){
        event.a.popover('hide');
        Service.get( event.a.data('service') ).onAfterModify();
    });
}



/*************** Propre à l'affichage des services référentiels ***************/

function ServiceReferentiel( id ) {

    this.id = id;

    this.onAfterAdd = function() {
        $.get(ServiceReferentiel.voirLigneUrl, [], function(data) { $("#services-ref").replaceWith($(data).filter("table").fadeIn()); });
        $("#formule-totaux-hetd").refresh();
    }

    this.onAfterDelete = function() {
        $( "#service-ref-" + this.id + "-ligne" ).fadeOut().remove();
        $("#formule-totaux-hetd").refresh();
    }
}

ServiceReferentiel.get = function( id )
{
    if (null == ServiceReferentiel.services) ServiceReferentiel.services = new Array();
    if (null == ServiceReferentiel.services[id]) ServiceReferentiel.services[id] = new ServiceReferentiel(id);
    return ServiceReferentiel.services[id];
}

ServiceReferentiel.init = function( voirLigneUrl )
{
    ServiceReferentiel.voirLigneUrl = voirLigneUrl;

    $("body").on("service-ref-add-message service-ref-modify-message", function(event, data) {
        var id = null;
        event.div.modal('hide'); // ferme la fenêtre modale
        ServiceReferentiel.get(id).onAfterAdd();
    });
    
    $("body").on("service-ref-delete-message", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
//        console.log(event.a.data('id'));
        ServiceReferentiel.get(event.a.data('id')).onAfterDelete();
    });
}


/***************************************************************************************************************************************************
    Offre de formation
/***************************************************************************************************************************************************/

//$("body").on("etape-after-suppression", function(event, data) {
//    window.history.back();
//});

function Etape( id ) {

    this.id = id;

    this.onAfterAdd = function()
    {
//        $.get( Etape.voirLigneUrl + "/" + this.id, function( data ) {
//            $("#etape-" + this.id + "-ligne").refresh();
//            $('#etapes > tbody:last').append(data);
//        });
        window.location = updateQueryStringParameter(window.location.href, "etape", this.id);
    }

    this.onAfterModify = function()
    {
//        $( "#etape-"+this.id+"-ligne" ).refresh( {details:details} );
        window.location = updateQueryStringParameter(window.location.href, "etape", this.id);
    }

    this.onAfterDelete = function()
    {
//        $( "#etape-"+this.id+"-ligne" ).fadeOut().remove();
        window.location.reload();
    }
}

Etape.get = function( id )
{
    if (null == Etape.services) Etape.services = new Array();
    if (null == Etape.services[id]) Etape.services[id] = new Etape(id);
    return Etape.services[id];
}

Etape.init = function( voirLigneUrl )
{
    Etape.voirLigneUrl = voirLigneUrl;
    
    $("body").on("event-of-etape-ajouter", function(event, data) {
        var id = null;
        event.div.modal('hide'); // ferme la fenêtre modale
        for (i in data){
            if (data[i].name === 'id') {
                id = data[i].value;
                break;
            }
        }
        if (id) {
            Etape.get(id).onAfterAdd();
        }
    });
    
    $("body").on("event-of-etape-modifier", function(event, data) {
        var id = null;
        event.div.modal('hide'); // ferme la fenêtre modale
        for (i in data){
            if (data[i].name === 'id'){
                id = data[i].value;
                break;
            }
        }
        if (id) {
            Etape.get(id).onAfterModify();
        }
    });
    
    $("body").on("event-of-etape-supprimer", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        Etape.get(event.a.data('id')).onAfterDelete();
    });
}


function ElementPedagogique( id ) {

    this.id = id;

    this.onAfterAdd = function(){
//        $.get( Etape.voirLigneUrl + "/" + this.id, function( data ) {
//            $("#etape-" + this.id + "-ligne").refresh();
//            $('#etapes > tbody:last').append(data);
//        });
            window.location.reload();
    }

    this.onAfterModify = function(){
//        $( "#etape-"+this.id+"-ligne" ).refresh( {details:details} );
            window.location.reload();
    }

    this.onAfterDelete = function(){
//        $( "#etape-"+this.id+"-ligne" ).fadeOut().remove();
            window.location.reload();
    }
}

/**
 *
 * @param integer id
 * @returns ElementPedagogique
 */
ElementPedagogique.get = function( id )
{
    if (null == ElementPedagogique.services) ElementPedagogique.services = new Array();
    if (null == ElementPedagogique.services[id]) ElementPedagogique.services[id] = new ElementPedagogique(id);
    return ElementPedagogique.services[id];
}

ElementPedagogique.init = function( voirLigneUrl )
{
    ElementPedagogique.voirLigneUrl = voirLigneUrl;

    $("body").on("event-of-element-ajouter", function(event, data) {
        console.log(event, data);
        var id = null;
        event.div.modal('hide'); // ferme la fenêtre modale
        ElementPedagogique.get(id).onAfterAdd();
    });
    
    $("body").on("event-of-element-modifier", function(event, data) {
        console.log(event, data);
        var id = null;
        event.div.modal('hide'); // ferme la fenêtre modale
        ElementPedagogique.get(id).onAfterModify();
    });
    
    $("body").on("event-of-element-supprimer", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        ElementPedagogique.get(event.a.data('id')).onAfterDelete();
    });
}




function Modulateur( id ) {

    this.id = id;

}

Modulateur.get = function( id )
{
    if (null == Modulateur.modulateurs) Modulateur.modulateurs = new Array();
    if (null == Modulateur.modulateurs[id]) Modulateur.modulateurs[id] = new Etape(id);
    return Modulateur.modulateurs[id];
}

Modulateur.init = function()
{
    $("body").on("event-of-etape-modulateurs", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
    });

    $("body").on("click", "form#modulateurs-saisie a.form-set-value", function(e){
        typeModulateurCode = $(this).data('code');
        value = $('form#modulateurs-saisie select[name="'+typeModulateurCode+'"]').val();
        Modulateur.setFormValues(typeModulateurCode, value);
        e.stopPopagation();
    });
}

Modulateur.setFormValues = function( typeModulateurCode, value )
{

    $('form#modulateurs-saisie select[name$="\\['+typeModulateurCode+'\\]"]').val(value);
}
