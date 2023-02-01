/***************************************************************************************************************************************************
 Propre à l'affichage du référentiel
 /***************************************************************************************************************************************************/

$.widget("ose.referentiels", {
    total: 0,
    calculTotaux: function () {
        var that = this;
        this.total = 0;

        this.element.find("table.service-referentiel td.sr-heures").each(function () {
            var value = $(this).data('value');
            that.total += value;
        });

        // on met à jour aussi les entêtes et les totaux
        this.element.find("table.service-referentiel #total-referentiel").html(Util.formattedHeures(this.total));
    },


    hasHeures: function () {
        return this.total > 0;
    },


    getHeures: function (serviceId) {
        return this.element.find("#referentiel-" + serviceId + "-ligne td.sr-heures").data('value');
    },


    getHeuresPrevues: function (serviceId) {
        return this.element.find("tr#referentiel-" + serviceId + "-ligne td.sr-heures").data('prevues');
    },


    onAfterChange: function () {
        var exHasHeures = this.hasHeures();
        var exHeures = this.total;

        this.init();

        if (this.hasHeures() !== exHasHeures) {
            this._trigger('heures-change-exists', null, this);
        }
        if (this.heures != exHeures) {
            this._trigger('heures-change', null, this);
        }

        this.element.find(".horodatage").each(function () {
            $(this).refresh();
        });
    },


    onAfterSaisie: function (serviceId) {
        var that = this;

        if (that.element.find("#referentiel-" + serviceId + "-ligne").length) { // simple modification
            that.element.find("#referentiel-" + serviceId + "-ligne").refresh({
                details: $('#referentiel-' + serviceId + '-volume-horaire-tr').css('display') == 'none' ? '0' : '1',
                params: that.params
            }, function () {
                that.onAfterChange();
                //Si total heure realisées sur la ligne égal à 0 alors on supprime la ligne
                heures = that.getHeures(serviceId);
                if (heures == 0) {
                    $('tr#referentiel-' + serviceId + '-ligne').remove();
                }
            });
            that.element.find("#referentiel-" + serviceId + "-volume-horaire-td").refresh();
        } else { // nouveau service
            var url = Url("referentiel/rafraichir-ligne/" + serviceId, {
                'only-content': 0,
                'details': 1,
                params: that.params
            });
            $.get(url, function (data) {
                //Si total heure realisées sur la ligne égal à 0 alors on supprime la ligne
                heures = that.getHeures(serviceId);
                if (heures == 0) {
                    $('tr#referentiel-' + serviceId + '-ligne').remove();
                }
                that.element.find("table.service-referentiel > tbody:last").append(data);
                that.onAfterChange();
            });
        }

    },

    onAfterDelete: function (serviceId) {
        if (this.params['in-realise'] && this.getHeuresPrevues(serviceId) > 0) { // si on est dans le réalisé alors les lignes apparaissent toujours, même si les heures réalisées ont été supprimées
            this.onAfterSaisie(serviceId);
        } else {
            this.element.find("#referentiel-" + serviceId + "-volume-horaire-tr").remove();
            this.element.find("#referentiel-" + serviceId + "-ligne").remove();
            this.onAfterChange();
        }
    },

    setRealisesFromPrevus: function () {
        var services = '';
        var that = this;

        this.element.find("table.service-referentiel tr.referentiel-ligne").each(function () {
            if (services != '') services += ',';
            services += $(this).data('id');
        });
        $.get(
            Url("referentiel/constatation"),
            {services: services},
            function (data) {
                console.log(data);
                if (data != 'OK') {
                    that.element.find("#referentiel-prevu-to-realise-modal").modal('hide');
                    that.element.find("#referentiel-prevu-to-realise-modal").after('<div style="margin-top:.5em">' + data + '</div>');
                } else {
                    window.location.reload();
                }
            }
        )
        ;
    },

    setPrevusFromPrevus: function () {
        var that = this;
        $.get(
            Url("referentiel/initialisation/" + this.getElementPrevuToPrevu().data('intervenant')),
            {},
            function (data) {
                if (data != 'OK') {
                    that.element.find("#referentiel-prevu-to-prevu-modal").modal('hide');
                    that.element.find("#referentiel-prevu-to-prevu-modal").after('<div style="margin-top:.5em">' + data + '</div>');
                } else {
                    window.location.reload();
                }
            }
        );
    },

    _create: function () {
        var that = this;
        this.params = this.element.data('params');


        this.element.find(".referentiel-prevu-to-realise").on('click', function () {
            that.setRealisesFromPrevus();
        });
        this.getElementPrevuToPrevu().on('click', function () {
            that.setPrevusFromPrevus();
        });

        $("body").on("service-referentiel-modify-message", function (event, data) {

            var serviceId = null;
            if ($("div .messenger, div .alert", event.div).length ? false : true) {
                event.div.modal('hide'); // ferme la fenêtre modale
            }

            for (i in data) {
                if (data[i].name == 'service[id]') {
                    serviceId = data[i].value;
                }
                if (data[i].name == 'service[idPrev]') {
                    serviceIdPrev = data[i].value;
                    if (serviceId != serviceIdPrev) {
                        that.onAfterSaisie(serviceIdPrev);

                    }
                }
            }

            if (serviceId) {
                heuresPrev = $("heures-realises-" + serviceId + " span").html();
                that.onAfterSaisie(serviceId);
            }
        });

        $("body").on("service-referentiel-add-message", function (event, data) {
            if ($("div .messenger, div .alert", event.div).length ? false : true) {
                event.div.modal('hide'); // ferme la fenêtre modale
            }
            for (i in data) {
                if (data[i].name == 'service[id]') {
                    serviceId = data[i].value;
                }
            }
            if (serviceId) {
                that.onAfterSaisie(serviceId);
            }
        });


        $("body").on('save-volume-horaire-referentiel', function (event, data) {
            var serviceId = event.a.data('service');
            event.a.popover('hide');
            that.onAfterSaisie(serviceId);
        });

        this.init();
    },

    init: function () {
        var that = this;

        this.element.find('.referentiel-delete').popAjax({
            submit: function (event, popAjax) {
                if (!popAjax.hasErrors()) {
                    var serviceId = popAjax.element.parents('tr.referentiel-ligne').data('id');
                    popAjax.hide();
                    that.onAfterDelete(serviceId);
                }
            }
        });

        this.calculTotaux();
    }
    ,

    getElementPrevuToPrevu: function () {
        return this.element.find(".referentiel-prevu-to-prevu")
    }
})
;


$.widget("ose.serviceReferentielForm", {

    prevuToRealise: function () {
        this.element.find("input.fonction-referentiel-heures", this.element).val(
            Util.formattedHeures(this.element.find("#rappel-heures-prevu", this.element).data('heures'), false)
        );
    },


    _create: function () {
        var that = this;

        this.element.find("button.referentiel-prevu-to-realise", this.element).on('click', function () {
            that.prevuToRealise();
        });

        this.element.find('select.fonction-referentiel-fonction').change(function () {
            that.majDisplay();
        });
        that.majDisplay();
    },


    majDisplay: function () {
        this.majDisplayStructure();
        this.majDisplayFormation();
    },


    majDisplayFormation: function () {
        var currentFonction = this.element.find('select.fonction-referentiel-fonction').val();
        var aPreciser = this.element.data('fonctions')['etape-requise'];
        var divFormation = this.getDivFormationElement();

        if (Util.inArray(currentFonction, aPreciser)) {
            divFormation.show();
        } else {
            divFormation.hide();
        }

        return this;
    },


    majDisplayStructure: function () {
        var currentFonction = this.element.find('select.fonction-referentiel-fonction').val();
        var structures = this.element.data('fonctions')['structures'];

        $('option', this.getStructureElement()).attr('disabled', false);
        if (structures[currentFonction] != undefined) {
            var structure = structures[currentFonction];

            this.getStructureElement().val(structure);
            $('option:not(:selected)', this.getStructureElement()).attr('disabled', true);
        }
        this.getStructureElement().selectpicker('destroy');
        this.getStructureElement().selectpicker();
    },


    getDivFormationElement: function () {
        return this.element.find('.fonction-referentiel-formation').parent();
    },


    getStructureElement: function () {
        return this.element.find('select.fonction-referentiel-structure');
    }

})
;