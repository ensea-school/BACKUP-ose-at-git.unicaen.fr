/***************************************************************************************************************************************************
 Propre à l'affichage des services
 /***************************************************************************************************************************************************/

function ServiceListe(id)
{
    this.id = id;
    this.params = $("#" + this.id).data('params');

    this.showHideTypesIntervention = function ()
    {
        var that = this;

        // initialisation des visibilités : tout masqué par défaut
        for (var i in this.params["types-intervention-visibility"]) { // initialisation
            this.params["types-intervention-visibility"][i] = false;
        }

        // on détecte les types (par leur code) qui ne doivent plus être masqués et on en profite pour mettre à jour les paramètres
        $("#" + this.id + " table.service tr.service-ligne td.type-intervention").each(function ()
        {
            var typeInterventionCode = $(this).data('type-intervention-code');
            var visibility = '1' == $(this).data('visibility');

            if (visibility) {
                that.params["types-intervention-visibility"][typeInterventionCode] = true;
            }
        });

        // on applique la visilibité fraichement calculées sur les colonnes
        $("#" + this.id + " table.service tr.service-ligne td.type-intervention").each(function ()
        {
            var typeInterventionCode = $(this).data('type-intervention-code');
            var visibility = that.params["types-intervention-visibility"][typeInterventionCode];

            if (visibility) {
                $(this).show(200);
            } else {
                $(this).hide(200);
            }
        });

        // on met à jour aussi les entêtes et les totaux
        var count = 0;
        for (var i in this.params["types-intervention-visibility"]) {
            if (this.params["types-intervention-visibility"][i]) {
                count++;
                $("#" + this.id + " table.service tr th." + i).show(200); // entête
                $("#" + this.id + " table.service tfoot tr td." + i).show(200); // total
            } else {
                $("#" + this.id + " table.service tr th." + i).hide(200); // entête
                $("#" + this.id + " table.service tfoot tr td." + i).hide(200); // total
            }
        }
        $("#" + this.id + " table.service #total-general").attr('colspan', count);
        if (count == 0) {
            $("#" + this.id + " table.service tfoot").hide();
        } else {
            $("#" + this.id + " table.service tfoot").show();
        }
    }

    this.showHideDetails = function (serviceId, action)
    {
        var tr = $("#" + this.id + " #service-" + serviceId + "-volume-horaire-tr");
        var button = $("#" + this.id + " #service-" + serviceId + "-ligne td.actions .service-details-button");
        if (undefined === action) {
            if (tr.css('display') === 'none') {
                action = 'show';
            } else {
                action = 'hide';
            }
        }
        if (action === 'show') {
            button.html('<span class="glyphicon glyphicon-chevron-up"></span>');
            tr.show(200);
        } else {
            button.html('<span class="glyphicon glyphicon-chevron-down"></span>');
            tr.hide(200);
        }
    }

    this.showAllDetails = function ()
    {
        var thatId = this.id;
        $("#" + thatId + " .service-ligne").each(function ()
        {
            if ($(this).is(':visible')) {
                ServiceListe.get(thatId).showHideDetails($(this).data('id'), 'show');
            }
        });
    }

    this.hideAllDetails = function ()
    {
        var thatId = this.id;
        $("#" + thatId + " .service-ligne").each(function ()
        {
            if ($(this).is(':visible')) {
                ServiceListe.get(thatId).showHideDetails($(this).data('id'), 'hide');
            }
        });
    }

    this.onAfterChange = function ()
    {
        var that = this;

        this.init2();
        $("#" + this.id + " tfoot").refresh({params: this.params}, function ()
        {
            that.showHideTypesIntervention();
        }); // rafraichissement des totaux

        // autres modifications...
        $("#formule-totaux-hetd").refresh({}, function ()
        {
            that.showHideTypesIntervention();
        });

        if ($("#service-resume").length > 0) { // Si on est dans le résumé (si nécessaire)
            $("#service-resume").refresh();
        }
        $("#wf-nav-next").refresh(); // mise à jour de la navigation du Workflow
        $("#s-horodatage").refresh();
    }

    this.onAfterSaisie = function (serviceId)
    {
        var that = this;
        if ($("#" + that.id + " #service-" + serviceId + "-ligne").length) { // simple modification
            $("#" + that.id + " #service-" + serviceId + "-ligne").refresh({
                details: $('#service-' + serviceId + '-volume-horaire-tr').css('display') == 'none' ? '0' : '1',
                params: that.params
            }, function () { that.onAfterChange(); });
            $("#" + that.id + " #service-" + serviceId + "-volume-horaire-td div#vhl").refresh();
        } else { // nouveau service
            var url = Url("service/rafraichir-ligne/" + serviceId, {
                'only-content': 0,
                'details': 1,
                params: that.params
            });
            $.get(url, function (data)
            {
                $("#" + that.id + " > table > tbody:last").append(data);
                that.onAfterChange();
            });
        }
    }

    this.onAfterDelete = function (serviceId)
    {
        if (this.params['in-realise']) { // si on est dans les services réalisés alors les lignes apparaissent toujours, même si les heures réalisées ont été supprimées
            this.onAfterSaisie(serviceId);
        } else {
            $("#" + this.id + " #service-" + serviceId + "-volume-horaire-tr").remove();
            $("#" + this.id + " #service-" + serviceId + "-ligne").remove();
            this.onAfterChange();
        }
    }

    this.setRealisesFromPrevus = function ()
    {
        var services = '';
        var that = this;
        $("#" + this.id + " table.service tr.service-ligne").each(function ()
        {
            if (services != '') services += ',';
            services += $(this).data('id');
        });
        $.get(
            Url("service/constatation"),
            {services: services},
            function (data)
            {
                if (data != 'OK') {
                    $("#" + that.id + " #prevu-to-realise-modal").modal('hide');
                    $("#" + that.id + " #prevu-to-realise-modal").after('<div style="margin-top:.5em">' + data + '</div>');
                } else {
                    window.location.reload();
                }
            }
        );
    }

    this.setPrevusFromPrevus = function ()
    {
        var that = this;
        $.get(
            Url("service/initialisation/" + this.getElementPrevuToPrevu().data('intervenant')),
            {},
            function (data)
            {
                if (data != 'OK') {
                    $("#" + that.id + " #prevu-to-prevu-modal").modal('hide');
                    $("#" + that.id + " #prevu-to-prevu-modal").after('<div style="margin-top:.5em">' + data + '</div>');
                } else {
                    window.location.reload();
                }
            }
        );
    }

    this.init2 = function ()
    {
        var thatId = this.id;
        $("#" + this.id + " .service-details-button").off();
        $("#" + this.id + " .service-details-button").on('click', function ()
        {
            ServiceListe.get(thatId).showHideDetails($(this).parents('.service-ligne').data('id'));
        });

        $("#" + this.id + " table.service tr.service-ligne").each(function ()
        {
            var id = $(this).data('id');
            if ($("#" + thatId + " table.service tr#service-" + id + "-volume-horaire-tr td.heures-not-empty").length ? false : true) {
                $(this).hide();
                $("#" + thatId + " table.service tr#service-" + id + "-volume-horaire-tr").hide();
            } else {
                $(this).show();
            }
        });
    }

    this.init = function ()
    {
        var thatId = this.id;
        $("#" + this.id + " .service-show-all-details").on('click', function () { ServiceListe.get(thatId).showAllDetails(); });
        $("#" + this.id + " .service-hide-all-details").on('click', function () { ServiceListe.get(thatId).hideAllDetails(); });
        $("#" + this.id + " .prevu-to-realise").on('click', function () { ServiceListe.get(thatId).setRealisesFromPrevus(); });
        this.getElementPrevuToPrevu().on('click', function () { ServiceListe.get(thatId).setPrevusFromPrevus(); });
        this.init2();

        $("body").on("service-modify-message", function (event, data)
        {
            var serviceId = null;
            if ($("div .messenger, div .alert", event.div).length ? false : true) {
                event.div.modal('hide'); // ferme la fenêtre modale
                for (i in data) {
                    if (data[i].name == 'service[id]') {
                        serviceId = data[i].value;
                    }
                }
                if (serviceId) {
                    ServiceListe.get(thatId).onAfterSaisie(serviceId);
                }
            }
        });

        $("body").on("service-add-message", function (event, data)
        {
            var thatId = event.a.parents('div.service-liste').attr('id');
            var serviceId = null;
            if ($("div .messenger, div .alert", event.div).length ? false : true) { // si aucune erreur n'a été rencontrée
                event.div.modal('hide'); // ferme la fenêtre modale
                for (i in data) {
                    if (data[i].name == 'service[id]') {
                        serviceId = data[i].value;
                    }
                }
                if (serviceId) {
                    ServiceListe.get(thatId).onAfterSaisie(serviceId);
                }
            }
        });

        $("body").on("service-delete-message", function (event, data)
        {
            var thatId = event.a.parents('div.service-liste').attr('id');
            var serviceId = event.a.parents('tr.service-ligne').data('id');
            event.div.modal('hide'); // ferme la fenêtre modale
            ServiceListe.get(thatId).onAfterDelete(serviceId);
        });

        $("body").tooltip({
            selector: 'a.volume-horaire',
            placement: 'top',
            title: "Cliquez pour ouvrir/fermer le formulaire de modification..."
        });

        $("body").on('save-volume-horaire', function (event, data)
        {
            var thatId = event.a.parents('div.service-liste').attr('id');
            var serviceId = event.a.data('service');
            event.a.popover('hide');
            ServiceListe.get(thatId).onAfterSaisie(serviceId);
        });
    }

    this.getElementPrevuToPrevu = function () { return $("#" + this.id + " .prevu-to-prevu") };
}

ServiceListe.get = function (id)
{
    if (null == ServiceListe.instances) ServiceListe.instances = new Array();
    if (null == ServiceListe.instances[id]) ServiceListe.instances[id] = new ServiceListe(id);
    return ServiceListe.instances[id];
}





function ServiceForm()
{
    this.updating = false;

    this.onInterneExterneChange = function ()
    {
        if ('service-interne' == this.getInterneExterne()) {
            this.element.find('#element-externe').hide();
            this.getElementEtablissementId().val('');
            this.getElementEtablissementLabel().val('');
            this.element.find('#element-interne').show();
        } else {
            this.element.find('#element-interne').hide();
            this.getElementElementPedagogiqueId().val('');
            this.getElementElementPedagogiqueLabel().val('');
            this.element.find('#element-externe').show();
        }
        this.updateVolumesHoraires();
    }

    this.updateVolumesHoraires = function ()
    {
        var that = this;

        this.updating = true;
        this.updateVolumesHorairesSaisie();
        this.getElementVolumesHoraires().refresh({
            element: this.getElementElementPedagogiqueId().val(),
            etablissement: this.getElementEtablissementId().val(),
            'type-volume-horaire': this.getElementTypeVolumeHoraire().val()
        }, function ()
        {
            that.getElementVolumesHoraires().find('input.form-control').val('0');
            that.updating = false;
            that.initVolumesHoraires();
        });
    }

    this.updateVolumesHorairesSaisie = function ()
    {
        /* Volumes horaires en lecture seule si c'est en cours de mise à jour */
        var readOnly = this.updating;

        /* Volume horaires en lecture seule si on est en mode interne et qu'aucun élément n'est sélectionné */
        if ('service-interne' == this.getInterneExterne() && '' == this.getElementElementPedagogiqueId().val()) {
            readOnly = true;
        }
        /*        text = 'UPDATING = ' + (this.updating ? 'true' : 'false');
         text += "\nINTERNE = " + (this.getInterneExterne());
         text += "\nEP VIDE = " + ('' == this.getElementElementPedagogiqueId().val() ? 'true' : 'false');
         text += "\nrésultat RO = " + (readOnly ? 'true' : 'false');
         alert(text);*/

        this.getElementVolumesHoraires().find('input.form-control').prop('disabled', readOnly);
        this.getElementVolumesHoraires().find('button.prevu-to-realise').prop('disabled', readOnly);
    }

    this.prevuToRealise = function (periode)
    {
        var that = this;

        this.element.find("div.periode#" + periode + " input.form-control").each(function ()
        {
            var id = $(this).attr('name').replace(periode + '[', 'prev-').replace(']', '');
            var value = that.element.find("div.periode#" + periode + " #" + id).data('heures');
            $(this).val(value);
        });
    }

    this.init = function ()
    {
        var that = this;

        /* Détection de changement d'état du radio interne-externe */
        this.getElementInterneExterne().on('change', function ()
        {
            that.onInterneExterneChange();
        });

        /* Détection des changements d'éléments pédagogiques dans le formulaire de saisie */
        this.getElementElementPedagogiqueId().on("autocompleteselect", function ()
        {
            that.updateVolumesHoraires();
        });

        this.getElementElementPedagogiqueId().on("change", function ()
        {
            that.updateVolumesHoraires();
        });

        this.initVolumesHoraires();
    }

    this.initVolumesHoraires = function ()
    {
        var that = this;

        /* Détection de click sur les boutons prévu => réalisé */
        this.element.find("button.prevu-to-realise").on('click', function ()
        {
            var periode = $(this).parents('div.periode').attr('id');
            that.prevuToRealise(periode);
        });

        this.updateVolumesHorairesSaisie();
    }

    this.getInterneExterne = function ()
    {
        var result = this.element.find('input[name="service\\[interne-externe\\]"]:checked').val();
        return result == undefined ? 'service-interne' : result;
    }

    this.getElementInterneExterne = function () { return this.element.find('input[name="service\\[interne-externe\\]"]'); };
    this.getElementElementPedagogiqueId = function () { return this.element.find("input[name='service\\[element-pedagogique\\]\\[element\\]\\[id\\]']"); };
    this.getElementElementPedagogiqueLabel = function () { return this.element.find("input[name='service\\[element-pedagogique\\]\\[element\\]\\[label\\]']"); };
    this.getElementElementPedagogiqueListe = function () { return this.element.find("select#element-liste"); };
    this.getElementEtablissementId = function () { return this.element.find("input[name='service\\[etablissement\\]\\[id\\]']"); };
    this.getElementEtablissementLabel = function () { return this.element.find("input[name='service\\[etablissement\\]\\[label\\]']"); };
    this.getElementTypeVolumeHoraire = function () { return this.element.find("input[name='type-volume-horaire']"); };
    this.getElementVolumesHoraires = function () { return this.element.find('div#volumes-horaires'); };
}



ServiceFilter = function ()
{

}

ServiceFilter.initRecherche = function ()
{
    var structureAffName = 'form.service-recherche select[name=\"structure-aff\"]';
    var intervenantName = 'form.service-recherche input[name=\"intervenant[label]\"]';
    var changeIntervenant = function ()
    {
        var structure_aff = $("form.service-recherche select[name=\"structure-aff\"]");
        var type_intervenant = $('input[name=type-intervenant]:checked', 'form.service-recherche');
        var url = $(intervenantName).autocomplete("option", "source");
        var pi = url.indexOf('?');

        if (-1 !== pi) {
            url = url.substring(0, pi);
        }
        url += '?' + $.param({
                typeIntervenant: type_intervenant.val(),
                structure: structure_aff.val(),
                'having-services': 1
            });
        $(intervenantName).autocomplete("option", "source", url);

        if (type_intervenant.val() !== undefined && type_intervenant.data('intervenant-exterieur-id') == type_intervenant.val()) {
            $('#structure-aff-div').hide();
            structure_aff.val('');
        } else {
            $('#structure-aff-div').show();
        }
    }

    $(structureAffName).change(changeIntervenant);
    $('input[name=type-intervenant]', 'form.service-recherche').change(changeIntervenant);
}


