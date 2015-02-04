/***************************************************************************************************************************************************
    Propre à l'affichage des services
/***************************************************************************************************************************************************/

function ServiceListe( id ){
    this.id     = id;
    this.params = $("#"+this.id).data('params');

     this.showHideTypesIntervention = function(){
         var that = this;

         // initialisation des visibilités : tout masqué par défaut
         for( var i in this.params["types-intervention-visibility"] ){ // initialisation
             this.params["types-intervention-visibility"][i] = false;
         }

         // on détecte les types (par leur code) qui ne doivent plus être masqués et on en profite pour mettre à jour les paramètres
         $("#"+this.id+" table.service tr.service-ligne td.type-intervention").each( function(){
             var typeInterventionCode = $(this).data('type-intervention-code');
             var visibility = '1' == $(this).data('visibility');

             if (visibility){
                 that.params["types-intervention-visibility"][typeInterventionCode] = true;
             }
         } );

         // on applique la visilibité fraichement calculées sur les colonnes
         $("#"+this.id+" table.service tr.service-ligne td.type-intervention").each( function(){
             var typeInterventionCode = $(this).data('type-intervention-code');
             var visibility = that.params["types-intervention-visibility"][typeInterventionCode];

             if (visibility){
                 $(this).show(200);
             }else{
                 $(this).hide(200);
             }
         } );

         // on met à jour aussi les entêtes et les totaux
         var count = 0;
         for( var i in this.params["types-intervention-visibility"] ){
             if (this.params["types-intervention-visibility"][i]){
                 count++;
                 $("#"+this.id+" table.service tr th."+i).show(200); // entête
                 $("#"+this.id+" table.service tfoot tr td."+i).show(200); // total
             }else{
                 $("#"+this.id+" table.service tr th."+i).hide(200); // entête
                 $("#"+this.id+" table.service tfoot tr td."+i).hide(200); // total
             }
         }
         $("#"+this.id+" table.service #total-general").attr('colspan', count);
         if (count == 0){
             $("#"+this.id+" table.service tfoot").hide();
         }else{
             $("#"+this.id+" table.service tfoot").show();
         }
    }

    this.showHideDetails = function( serviceId, action ){
        var tr = $("#"+this.id+" #service-"+serviceId+"-volume-horaire-tr");
        var button = $("#"+this.id+" #service-"+serviceId+"-ligne td.actions .service-details-button");
        if (undefined === action){
            if (tr.css('display') === 'none'){
                action = 'show';
            }else{
                action = 'hide';
            }
        }
        if (action === 'show'){
            button.html('<span class="glyphicon glyphicon-chevron-up"></span>');
            tr.show(200);
        }else{
            button.html('<span class="glyphicon glyphicon-chevron-down"></span>');
            tr.hide(200);
        }
    }

    this.showAllDetails = function(){
        var thatId = this.id;
        $("#"+thatId+" .service-ligne").each( function(){
            if ($(this).is(':visible')){
               ServiceListe.get( thatId ).showHideDetails( $(this).data('id'), 'show' );
            }
        } );
    }

    this.hideAllDetails = function(){
        var thatId = this.id;
        $("#"+thatId+" .service-ligne").each( function(){
            if ($(this).is(':visible')){
               ServiceListe.get( thatId ).showHideDetails( $(this).data('id'), 'hide' );
            }
        } );
    }

    this.onAfterChange = function(){
        var that = this;

        this.init2();
        $("#"+this.id+" tfoot").refresh( { params  : this.params } ); // rafraichissement des totaux

        // autres modifications...
        $("#formule-totaux-hetd").refresh( {}, function(){
            that.showHideTypesIntervention();
        } );

        if ($( "#service-resume" ).length > 0){ // Si on est dans le résumé (si nécessaire)
            $( "#service-resume" ).refresh(); 
        }
        $("#wf-nav-next").refresh(); // mise à jour de la navigation du Workflow
    }

    this.onAfterSaisie = function( serviceId ){
        var that = this;
        if ( $( "#"+that.id+" #service-"+serviceId+"-ligne" ).length ){ // simple modification
            $( "#"+that.id+" #service-"+serviceId+"-ligne" ).refresh( {
                details : $('#service-'+serviceId+'-volume-horaire-tr').css('display') == 'none' ? '0' : '1',
                params  : that.params
            }, function(){ that.onAfterChange(); } );
            $( "#"+that.id+" #service-"+serviceId+"-volume-horaire-td div#vhl" ).refresh();
        }else{ // nouveau service
            var url = Url("service/rafraichir-ligne/"+serviceId, {
                'only-content'                  : 0,
                'details'                       : 1,
                params                          : that.params
            });
            $.get( url, function( data ) {
                $("#"+that.id+" > table > tbody:last").append(data);
                that.onAfterChange();
            });
        }
    }

    this.onAfterDelete = function( serviceId ){
        if (this.params['in-realise']){ // si on est dans les services réalisés alors les lignes apparaissent toujours, même si les heures réalisées ont été supprimées
            this.onAfterSaisie( serviceId );
        }else{
            $("#"+this.id+" #service-"+serviceId+"-volume-horaire-tr").remove();
            $("#"+this.id+" #service-"+serviceId+"-ligne" ).remove();
            this.onAfterChange();
        }
    }

    this.setRealisesFromPrevus = function(){
        var services = '';
        $("#"+this.id+" table.service tr.service-ligne").each( function(){
             if (services != '') services += ',';
             services += $(this).data('id');
        } );
        $.get(
            Url("service/constatation"),
            {services: services},
            function(){ window.location.reload(); }
        );
    }

    this.init2 = function(){
        var thatId = this.id;
        $("#"+this.id+" .service-details-button").off();
        $("#"+this.id+" .service-details-button").on('click', function(){
            ServiceListe.get(thatId).showHideDetails( $(this).parents('.service-ligne').data('id') );
        });

        $("#"+this.id+" table.service tr.service-ligne").each( function(){
             var id = $(this).data('id');
             if ( $("#"+thatId+" table.service tr#service-"+id+"-volume-horaire-tr td.heures-not-empty").length ? false : true){
                 $(this).hide();
                 $("#"+thatId+" table.service tr#service-"+id+"-volume-horaire-tr").hide();
             }else{
                 $(this).show();
             }
        } );
    }

    this.init = function(){
        var thatId = this.id;
        $("#"+this.id+" .service-show-all-details").on('click', function(){ ServiceListe.get(thatId).showAllDetails(); });
        $("#"+this.id+" .service-hide-all-details").on('click', function(){ ServiceListe.get(thatId).hideAllDetails(); });
        $("#"+this.id+" .prevu-to-realise").on('click', function(){ ServiceListe.get(thatId).setRealisesFromPrevus(); });
        this.init2();

        $("body").on("service-modify-message", function(event, data) {
            var serviceId = null;
            if ($("div .messenger, div .alert", event.div).length ? false : true){
                event.div.modal('hide'); // ferme la fenêtre modale
                for( i in data ){
                    if (data[i].name == 'service[id]'){
                        serviceId = data[i].value;
                    }
                }
                if (serviceId){
                    ServiceListe.get(thatId).onAfterSaisie( serviceId );
                }
            }
        });

        $("body").on("service-add-message", function(event, data) {
            var thatId = event.a.parents('div.service-liste').attr('id');
            if ($("div .messenger, div .alert", event.div).length ? false : true){ // si aucune erreur n'a été rencontrée
                event.div.modal('hide'); // ferme la fenêtre modale
                for( i in data ){
                    if (data[i].name == 'service[id]'){
                        serviceId = data[i].value;
                    }
                }
                if (serviceId){
                    ServiceListe.get(thatId).onAfterSaisie( serviceId );
                }
            }
        });

        $("body").on("service-delete-message", function(event, data) {
            var thatId = event.a.parents('div.service-liste').attr('id');
            var serviceId = event.a.parents('tr.service-ligne').data('id');
            event.div.modal('hide'); // ferme la fenêtre modale
            ServiceListe.get(thatId).onAfterDelete( serviceId );
        });

        $("body").tooltip({
            selector: 'a.volume-horaire',
            placement: 'top',
            title: "Cliquez pour ouvrir/fermer le formulaire de modification..."
        });

        $("body").on('save-volume-horaire', function(event,data){
            var thatId = event.a.parents('div.service-liste').attr('id');
            var serviceId = event.a.data('service');
            event.a.popover('hide');
            ServiceListe.get(thatId).onAfterSaisie( serviceId );
        });
    }
}

ServiceListe.get = function( id ){
    if (null == ServiceListe.instances) ServiceListe.instances = new Array();
    if (null == ServiceListe.instances[id]) ServiceListe.instances[id] = new ServiceListe(id);
    return ServiceListe.instances[id];
}





function ServiceForm( id ) {

    this.id = id;

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
            $("input[name='service\\[etablissement\\]\\[id\\]']").val(),
            $("input[name='type-volume-horaire']").val()
        );
    }

    this.refreshFormVolumesHoraires = function( elementId, etablissementId, typeVolumeHoraireId ){
        $('form#service div#volumes-horaires').refresh({
            element      : elementId,
            etablissement: etablissementId,
            'type-volume-horaire': typeVolumeHoraireId
        }, function(){
            $('form#service div#volumes-horaires input.form-control').each( function(element){
                $(this).val('0');
            } );
        });
    }

    this.prevuToRealise = function( periode ){
        $("form#service div.periode#"+periode+" .form-control").each( function(){
            var id = $(this).attr('name').replace(periode+'[', 'prev-').replace(']','');
            var value = $("form#service div.periode#"+periode+" #"+id).data('heures');
            $(this).val( value );
        });
    }

}

ServiceForm.get = function( id ){
    if (null == ServiceForm.services) ServiceForm.services = new Array();
    if (null == ServiceForm.services[id]) ServiceForm.services[id] = new ServiceForm(id);
    return ServiceForm.services[id];
}

ServiceForm.init = function(){
    /* Détection de changement d'état du radio interne-externe */
    $("body").on('change', 'form#service input[name="service\\[interne-externe\\]"]', function(){
        ServiceForm.get(this.value).showInterneExterne( $( this ).val() );
    });

    /* Détection des changements d'éléments pédagogiques dans le formulaire de saisie */
    $( "body" ).on( "autocompleteselect", 'form#service input[name="service\\[element-pedagogique\\]\\[element\\]\\[label\\]"]', function( event, ui ) {
        var serviceId = $('form#service input[name="service\\[id\\]"]').val();
        var elementId = ui.item.id;
        ServiceForm.get(serviceId).refreshFormVolumesHoraires( elementId, $("input[name='service\\[etablissement\\]\\[id\\]']").val(), $("input[name='type-volume-horaire']").val() );
    } );

    $("form#service button.prevu-to-realise").on('click', function(){
        var serviceId = $('form#service input[name="service\\[id\\]"]').val();
        var periode = $(this).parents('div.periode').attr('id')
        ServiceForm.get(serviceId).prevuToRealise( periode );
    } );
}





ServiceFilter = function(){

}

ServiceFilter.initRecherche = function(){
    var structureAffName = 'form.service-recherche select[name=\"structure-aff\"]';
    var intervenantName = 'form.service-recherche input[name=\"intervenant[label]\"]';
    var changeIntervenant = function(){
        var structure_aff = $( "form.service-recherche select[name=\"structure-aff\"]" );
        var type_intervenant = $('input[name=type-intervenant]:checked', 'form.service-recherche');
        var url = $( intervenantName ).autocomplete( "option", "source" );
        var pi = url.indexOf('?');

        if (-1 !== pi){
            url = url.substring( 0, pi );
        }
        url += '?' + $.param( {
            typeIntervenant     : type_intervenant.val(),
            structure           : structure_aff.val(),
            'having-services'   : 1
        } );
        $( intervenantName ).autocomplete( "option", "source", url );

        if (type_intervenant.val() !== undefined && type_intervenant.data('intervenant-exterieur-id') == type_intervenant.val()){
            $('#structure-aff-div').hide();
            structure_aff.val('');
        }else{
            $('#structure-aff-div').show();
        }
    }

    $( structureAffName ).change( changeIntervenant );
    $('input[name=type-intervenant]', 'form.service-recherche').change(changeIntervenant);
}


