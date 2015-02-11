/***************************************************************************************************************************************************
 Propre à l'affichage du référentiel
 /***************************************************************************************************************************************************/

function ServiceReferentielListe(id)
{
    this.id = id;
    this.params = $("#" + this.id).data('params');

    this.onAfterChange = function () {
        var that = this;

        $("#" + this.id + " tfoot").refresh({params: this.params}); // rafraichissement des totaux

//        // autres modifications...
        $("#formule-totaux-hetd").refresh( {} );
        if ($("#service-resume").length > 0) { // Si on est dans le résumé (si nécessaire)
            $("#service-resume").refresh();
        }
        $("#wf-nav-next").refresh(); // mise à jour de la navigation du Workflow
    }

    this.onAfterSaisie = function (serviceId) {
        var that = this;
        if ($("#" + that.id + " #referentiel-" + serviceId + "-ligne").length) { // simple modification
            $("#" + that.id + " #referentiel-" + serviceId + "-ligne").refresh({
                details: $('#referentiel-' + serviceId + '-volume-horaire-tr').css('display') == 'none' ? '0' : '1',
                params: that.params
            }, function () {
                that.onAfterChange();
            });
            $("#" + that.id + " #referentiel-" + serviceId + "-volume-horaire-td").refresh();
        } else { // nouveau service
            var url = Url("referentiel/rafraichir-ligne/" + serviceId, {
                'only-content': 0,
                'details': 1,
                params: that.params
            });
            $.get(url, function (data) {
                $("#" + that.id + " > table > tbody:last").append(data);
                that.onAfterChange();
            });
        }
    }

    this.onAfterDelete = function (serviceId) {
        if (this.params['in-realise']){ // si on est dans le réalisé alors les lignes apparaissent toujours, même si les heures réalisées ont été supprimées
            this.onAfterSaisie( serviceId );
        }else{
            $("#" + this.id + " #referentiel-" + serviceId + "-volume-horaire-tr").remove();
            $("#" + this.id + " #referentiel-" + serviceId + "-ligne").remove();
            this.onAfterChange();
        }
    }

    this.setRealisesFromPrevus = function(){
        var services = '';
        $("#"+this.id+" table.service-referentiel tr.referentiel-ligne").each( function(){
             if (services != '') services += ',';
             services += $(this).data('id');
        } );
        $.get(
            Url("referentiel/constatation"),
            {services: services},
            function(){ window.location.reload(); }
        );
    }

    this.init = function () {
        var thatId = this.id;

        $("#"+this.id+" .referentiel-prevu-to-realise").on('click', function(){ ServiceReferentielListe.get(thatId).setRealisesFromPrevus(); });
        
        $("body").on("service-referentiel-modify-message", function (event, data) {
            var serviceId = null;
            if ($("div .messenger, div .alert", event.div).length ? false : true) {
                event.div.modal('hide'); // ferme la fenêtre modale
                for (i in data) {
                    if (data[i].name == 'service[id]') {
                        serviceId = data[i].value;
                    }
                }
                if (serviceId) {
                    ServiceReferentielListe.get(thatId).onAfterSaisie(serviceId);
                }
            }
        });

        $("body").on("service-referentiel-add-message", function (event, data) {
            var thatId = event.a.parents('div.referentiel-liste').attr('id');
            if ($("div .messenger, div .alert", event.div).length ? false : true) { // si aucune erreur n'a été rencontrée
                event.div.modal('hide'); // ferme la fenêtre modale
                for (i in data) {
                    if (data[i].name == 'service[id]') {
                        serviceId = data[i].value;
                    }
                }
                if (serviceId) {
                    ServiceReferentielListe.get(thatId).onAfterSaisie(serviceId);
                }
            }
        });

        $("body").on("service-referentiel-delete-message", function (event, data) {
            var thatId = event.a.parents('div.referentiel-liste').attr('id');
            var serviceId = event.a.parents('tr.referentiel-ligne').data('id');
            event.div.modal('hide'); // ferme la fenêtre modale
            ServiceReferentielListe.get(thatId).onAfterDelete(serviceId);
        });

        $("body").tooltip({
            selector: 'a.volume-horaire',
            placement: 'top',
            title: "Cliquez pour ouvrir/fermer le formulaire de modification..."
        });

        $("body").on('save-volume-horaire-referentiel', function (event, data) {
            var thatId = event.a.parents('div.referentiel-liste').attr('id');
            var serviceId = event.a.data('service');
            event.a.popover('hide');
            ServiceReferentielListe.get(thatId).onAfterSaisie(serviceId);
        });
    }
}

ServiceReferentielListe.get = function (id) {
    if (null == ServiceReferentielListe.instances)
        ServiceReferentielListe.instances = new Array();
    if (null == ServiceReferentielListe.instances[id])
        ServiceReferentielListe.instances[id] = new ServiceReferentielListe(id);
    return ServiceReferentielListe.instances[id];
}





function ServiceReferentielForm(id) {

    this.id = id;

    this.showInterneExterne = function () {
        if ('service-interne' == this.id) {
            $('#element-interne').show();
            $('#element-externe').hide();
            $("input[name='service\\[etablissement\\]\\[label\\]']").val('');
            $("input[name='service\\[etablissement\\]\\[id\\]']").val('');
        } else {
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

    this.refreshFormVolumesHoraires = function (elementId, etablissementId, typeVolumeHoraireId) {
        $('form#service div#volumes-horaires').refresh({
            element: elementId,
            etablissement: etablissementId,
            'type-volume-horaire': typeVolumeHoraireId
        }, function () {
            $('form#service div#volumes-horaires input.form-control').each(function (element) {
                $(this).val('0');
            });
        });
    }

    this.prevuToRealise = function() {
        var form = $("form#referentiel");
        $("input.fonction-referentiel-heures", form).val($("#rappel-heures-prevu", form).data('heures'));
    }

}

ServiceReferentielForm.get = function (id) {
    if (null == ServiceReferentielForm.services)
        ServiceReferentielForm.services = new Array();
    if (null == ServiceReferentielForm.services[id])
        ServiceReferentielForm.services[id] = new ServiceReferentielForm(id);
    return ServiceReferentielForm.services[id];
}

ServiceReferentielForm.init = function () {
    var form = $("form#referentiel");
    $("button.referentiel-prevu-to-realise", form).on('click', function(){
        var serviceId = $('input[name="service\\[id\\]"]', form).val();
        ServiceReferentielForm.get(serviceId).prevuToRealise();
    } );
}





//ServiceFilter = function () {
//
//}
//
//ServiceFilter.initRecherche = function () {
//    var structureAffName = 'form.service-recherche select[name=\"structure-aff\"]';
//    var intervenantName = 'form.service-recherche input[name=\"intervenant[label]\"]';
//    var changeIntervenant = function () {
//        var structure_aff = $("form.service-recherche select[name=\"structure-aff\"]");
//        var type_intervenant = $('input[name=type-intervenant]:checked', 'form.service-recherche');
//        var url = $(intervenantName).autocomplete("option", "source");
//        var pi = url.indexOf('?');
//
//        if (-1 !== pi) {
//            url = url.substring(0, pi);
//        }
//        url += '?' + $.param({
//            typeIntervenant: type_intervenant.val(),
//            structure: structure_aff.val(),
//            'having-services': 1
//        });
//        $(intervenantName).autocomplete("option", "source", url);
//
//        if (type_intervenant.val() !== undefined && type_intervenant.data('intervenant-exterieur-id') == type_intervenant.val()) {
//            $('#structure-aff-div').hide();
//            structure_aff.val('');
//        } else {
//            $('#structure-aff-div').show();
//        }
//    }
//
//    $(structureAffName).change(changeIntervenant);
//    $('input[name=type-intervenant]', 'form.service-recherche').change(changeIntervenant);
//}


