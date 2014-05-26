


/***************************************************************************************************************************************************
    Propre à l'affichage des services
/***************************************************************************************************************************************************/

function Service( id ) {

    this.id = id;

    this.delete = function( url ){
        ok = window.confirm('Voulez-vous vraiment supprimer ce service ?');
        if (ok){
            $('#service-div').modal({remote: url});
    }
        return false;
    }

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
            $("input[name='etablissement\\[label\\]']").val('');
            $("input[name='etablissement\\[id\\]']").val('');
        }else{
            $('#element-interne').hide();
            $("input[name='elementPedagogique\\[label\\]']").val('');
            $("input[name='elementPedagogique\\[id\\]']").val('');
            $('#element-externe').show();
        }
    }

    this.onAfterAdd = function(){
        $.get( Service.voirLigneUrl+"/"+this.id+'?only-content=0&details=1', function( data ) {
            $( "#service-"+this.id+"-ligne" ).refresh();
            $('#services > tbody:last').append(data);
            Service.refreshFiltres();
        });
    }

    this.onAfterModify = function(){
        var details = $('#service-'+this.id+'-volume-horaire-tr').css('display') == 'none' ? '0' : '1';
        $( "#service-"+this.id+"-ligne" ).refresh( {details:details} );
        Service.refreshFiltres();
    }

    this.onAfterDelete = function(){
        $('#service-'+this.id+'-volume-horaire-tr').remove();
        $( "#service-"+this.id+"-ligne" ).remove();
        Service.refreshFiltres();
    }

}

Service.get = function( id ){
    if (null == Service.services) Service.services = new Array();
    if (null == Service.services[id]) Service.services[id] = new Service(id);
    return Service.services[id];
}

Service.init = function( voirLigneUrl ){
    Service.voirLigneUrl = voirLigneUrl;

    $("body").on("service-modify-message", function(event, data) {
        var id = null;
        if ($("div .messenger, div .alert", event.div).length ? false : true){
            event.div.modal('hide'); // ferme la fenêtre modale
            for( i in data ){
                if (data[i].name == 'id'){
                    id = data[i].value;
                }
            }

            if (id){
                Service.get(id).onAfterModify();
            }
        }
    });

//    $('#service-div').on('loaded.bs.modal', function (e) {
//        if (id = $('#service-deleted-id').val()){
//            var terminated = $("form .input-error, form .has-error, div.alert", $(e.target)).length ? false : true;
//            if (terminated){
//                Service.get(id).onAfterDelete();
//            }
//        }
//    });

    $("body").on("service-add-message", function(event, data) {
        var id = null;
        if ($("div .messenger, div .alert", event.div).length ? false : true){
            event.div.modal('hide'); // ferme la fenêtre modale
            for( i in data ){
                if (data[i].name == 'id'){
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

    $("body").on('change', 'form#service input[name="interne-externe"]', function(){
        Service.get(this.value).showInterneExterne( $( this ).val() );
    });

    $(".service-show-all-details").on('click', function(){ Service.showAllDetails(); });
    $(".service-hide-all-details").on('click', function(){ Service.hideAllDetails(); });
//    $('body').on('click', '.service-delete', function(){
//        Service.get( $(this).data('id') ).delete( $(this).attr('href') );
//        return false;
//    });

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
    $( "#filtres" ).refresh();
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
        $("#service-"+event.a.data('service')+"-volume-horaire-td").refresh();
        $( "#service-"+event.a.data('service')+"-ligne" ).refresh();
    });
}



/*************** Propre à l'affichage des services référentiels ***************/

function ServiceReferentiel( id ) {

    this.id = id;

    this.onAfterAdd = function() {
        $.get(ServiceReferentiel.voirLigneUrl, [], function(data) { $("#services-ref").replaceWith($(data).filter("table").fadeIn()); });
    }

    this.onAfterDelete = function() {
        $( "#service-ref-" + this.id + "-ligne" ).fadeOut().remove();
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
    
    $("body").on("service-delete-message", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        console.log(event.a.data('id'));
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