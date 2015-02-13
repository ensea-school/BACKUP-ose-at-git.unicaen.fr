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

Util = {
    formattedHeures: function( heures )
    {
        heures = parseFloat( heures );
        var hclass = (heures < 0) ? 'negatif' : 'positif';

        heures = Math.round( heures * 100 ) / 100;
        var parts = heures.toString().split(".");
        if (undefined === parts[1]){ parts[1] = '<span class="heures-dec-00">,00</span>'; }else{ parts[1] = ',' + parts[1]; }
        return '<span class="heures heures-'+hclass+'">'+parts[0]+parts[1]+'</span>';
    },

    json: {

        count: function( tab )
        {
            var key, result = 0;
            for(key in tab) {
              if(tab.hasOwnProperty(key)) {
                result++;
              }
            }
            return result;
        },

        first: function( tab )
        {
            for( var key in tab ){
                return tab[key];
            }
        }

    }
};


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








function Modulateur( id ) 
{
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

    $("body").on("click", "form#modulateurs-saisie button.form-set-value", function(e){
        var typeModulateurCode = $(this).data('code');
        var value = $('form#modulateurs-saisie select[name="'+typeModulateurCode+'"]').val();
        Modulateur.setFormValues(typeModulateurCode, value);
        e.stopPopagation();
    });
}

Modulateur.setFormValues = function( typeModulateurCode, value )
{
    $('form#modulateurs-saisie select[name$="\\['+typeModulateurCode+'\\]"]').val(value);
}







function EtapeCentreCout(id) 
{
    this.id = id;
}

EtapeCentreCout.init = function ()
{
    $("body").on("event-of-etape-centres-couts", function (event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
    });

    $("body").on("click", "form#etape-centre-cout button.form-set-value", function (e) {
        var typeHeuresCode = $(this).data('code');
        var value = $('form#etape-centre-cout select[name="' + typeHeuresCode + '"]').val();
        EtapeCentreCout.setFormValues(typeHeuresCode, value);
        e.stopPopagation();
    });
}

EtapeCentreCout.setFormValues = function (typeHeuresCode, value)
{
    $('form#etape-centre-cout select[name$="\\[' + typeHeuresCode + '\\]"]').val(value);
}
