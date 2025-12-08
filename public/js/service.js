/***************************************************************************************************************************************************
 Propre à l'affichage des services
 /***************************************************************************************************************************************************/

$.widget("ose.enseignements", {
    totaux: {},
    total: 0,

    showHideTypesIntervention: function () {
        var count = 0;
        for (var i in this.totaux) {
            if (this.totaux[i] != 0) {
                count++;
                this.element.find("table.service tr th.ti"+i).show(); // entête
                this.element.find("table.service tr.service-ligne td.type-intervention.ti"+i).show();
                this.element.find("table.service tfoot tr td.ti"+i).show(); // total
            } else {
                this.element.find("table.service tr th.ti"+i).hide(); // entête
                this.element.find("table.service tr.service-ligne td.type-intervention.ti"+i).hide();
                this.element.find("table.service tfoot tr td.ti"+i).hide(); // total
            }
        }
        this.element.find("table.service #total-general").attr('colspan', count);
        if (count == 0) {
            this.element.find("table.service tr th.type-intervention").hide(); // entête
            this.element.find("table.service tfoot").hide();
        } else {
            this.element.find("table.service tfoot").show();
        }
    },


    calculTotaux: function () {
        var that = this;
        this.totaux = {};
        this.total = 0;

        this.element.find("table.service tr.service-ligne td.type-intervention").each(function () {
            var typeInterventionId = $(this).data('type-intervention-id');
            var value = $(this).data('value');
            if (that.totaux[typeInterventionId] == undefined) that.totaux[typeInterventionId] = 0;

            that.totaux[typeInterventionId] += value;
            that.total += value;
        });

        // on met à jour aussi les entêtes et les totaux
        for (var ti in this.totaux) {
            var heures = this.totaux[ti];
            this.element.find("table.service tfoot tr td.ti"+ti).html(Util.formattedHeures(heures));
        }
        this.element.find("table.service #total-general").html(Util.formattedHeures(this.total));
    },


    showHideDetails: function (serviceId, action) {
        var tr = this.element.find("#service-"+serviceId+"-volume-horaire-tr");
        var button = this.element.find("#service-"+serviceId+"-ligne td.actions .service-details-button");
        if (undefined === action) {
            if (tr.css('display') === 'none') {
                action = 'show';
            } else {
                action = 'hide';
            }
        }
        if (action === 'show') {
            button.html('<i class="fas fa-chevron-up"></i>');
            tr.show(200);
        } else {
            button.html('<i class="fas fa-chevron-down"></i>');
            tr.hide(200);
        }
    },


    showAllDetails: function () {
        var that = this;
        this.element.find(".service-ligne").each(function () {
            if ($(this).is(':visible')) {
                that.showHideDetails($(this).data('id'), 'show');
            }
        });
    },


    hideAllDetails: function () {
        var that = this;
        this.element.find(".service-ligne").each(function () {
            if ($(this).is(':visible')) {
                that.showHideDetails($(this).data('id'), 'hide');
            }
        });
    },


    hasHeures: function () {
        return this.total > 0;
    },


    onAfterChange: function () {
        var exHasHeures = this.hasHeures();
        var exHeures = this.total;

        this.init2();
        if (this.hasHeures() !== exHasHeures) {
            this._trigger('heures-change-exists', null, this);
        }
        if (this.heures != exHeures) {
            this._trigger('heures-change', null, this);
        }


        this.element.find(".horodatage").each(function () {
            $(this).refresh();
        });

        if ($("#service-resume").length > 0) { // Si on est dans le résumé (si nécessaire)
            $("#service-resume").refresh();
        }
    },


    onAfterSaisie: function (serviceId) {
        var that = this;
        if (that.element.find("#service-"+serviceId+"-ligne").length) { // simple modification
            that.element.find("#service-"+serviceId+"-ligne").refresh({
                details: that.element.find('#service-'+serviceId+'-volume-horaire-tr').css('display') == 'none' ? '0' : '1',
                params: that.params
            }, function () {
                that.onAfterChange();
            });
            that.element.find("#service-"+serviceId+"-volume-horaire-td div#vhl").refresh();
        } else { // nouveau service
            var url = unicaenVue.url("enseignement/rafraichir-ligne/:service", {service: serviceId}, {
                'only-content': 0,
                'details': 1,
                params: that.params
            });
            $.get(url, function (data) {
                that.element.find("table:first > tbody:last").append(data);
                that.onAfterChange();
            });
        }
    },


    onAfterDelete: function (serviceId) {
        if (this.params['in-realise']) { // si on est dans les services réalisés alors les lignes apparaissent toujours, même si les heures réalisées ont été supprimées
            this.onAfterSaisie(serviceId);
        } else {
            this.element.find("#service-"+serviceId+"-volume-horaire-tr").remove();
            this.element.find("#service-"+serviceId+"-ligne").remove();
            this.onAfterChange();
        }
    },


    setRealisesFromPrevus: function () {
        var services = '';
        var that = this;
        this.element.find("table.service tr.service-ligne").each(function () {
            if (services != '') services += ',';
            services += $(this).data('id');
        });
        $.get(
            unicaenVue.url("enseignement/constatation"),
            {services: services},
            function (data) {
                if (data != 'OK') {
                    that.element.find("#prevu-to-realise-modal").modal('hide');
                    that.element.find("#prevu-to-realise-modal").after('<div style="margin-top:.5em">'+data+'</div>');
                } else {
                    window.location.reload();
                }
            }
        );
    },


    setPrevusFromPrevus: function () {
        var that = this;
        that.element.find('#prevu-to-prevu-attente').show();
        $.get(
            unicaenVue.url("enseignement/initialisation/:intervenant", {intervenant: this.getElementPrevuToPrevu().data('intervenant')}),
            {},
            function (data) {
                if (data != 'OK') {
                    that.element.find("#prevu-to-prevu-modal").modal('hide');
                    that.element.find("#prevu-to-prevu-modal").after('<div style="margin-top:.5em">'+data+'</div>');
                } else {
                    window.location.reload();
                }
            }
        );
    },


    init2: function () {
        var that = this;
        this.element.find(".service-details-button").off();
        this.element.find(".service-details-button").on('click', function () {
            that.showHideDetails($(this).parents('.service-ligne').data('id'));
        });

        //Uniquement pour le mode semestriel
        this.element.find("table.service tr.service-ligne").not('.mode-calendaire').each(function () {
            var id = $(this).data('id');
            var totalHeures = 0;
            $(this).find('td.heures').each(function () {
                totalHeures += $(this).data('value');
            });
            //On affiche toutes les lignes de services meme celle avec 0H
            if (totalHeures = 0) {
                $(this).hide();
                that.element.find("table.service tr#service-"+id+"-volume-horaire-tr").hide();
            } else {
                $(this).show();
            }
        });

        this.element.find('.service-delete').popAjax({
            submit: function (event, popAjax) {
                if (!popAjax.hasErrors()) {
                    var serviceId = popAjax.element.parents('tr.service-ligne').data('id');
                    popAjax.hide();
                    that.onAfterDelete(serviceId);
                }
            }
        });


        this.calculTotaux();
        this.showHideTypesIntervention();

    },


    _create: function () {
        var that = this;

        this.params = this.element.data('params');

        this.element.find(".service-show-all-details").on('click', function () {
            that.showAllDetails();
        });
        this.element.find(".service-hide-all-details").on('click', function () {
            that.hideAllDetails();
        });
        this.element.find(".prevu-to-realise").on('click', function () {
            that.setRealisesFromPrevus();
        });
        this.getElementPrevuToPrevu().on('click', function () {
            that.setPrevusFromPrevus();
        });
        this.init2();

        $("body").on("service-modify-message", function (event, data) {
            var serviceId = null;
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

        $("body").on("service-add-message", function (event, data) {
            var thatId = event.a.parents('div.enseignements').attr('id');
            var serviceId = null;
            if ($("div .messenger, div .alert", event.div).length ? false : true) { // si aucune erreur n'a été rencontrée
                event.div.modal('hide'); // ferme la fenêtre modale
            }
            for (i in data) {
                if (data[i].name == 'service[id]') {
                    serviceId = data[i].value;
                }
            }
            //that.onAfterSaisie(serviceId);
        });

        $("body").on('enseignement-after-saisie', function(event, serviceId){
            that.onAfterSaisie(serviceId);
        });

        /* this.element.find('a.volume-horaire').tooltip({
             selector: 'a.volume-horaire',
             placement: 'top',
             title: "Cliquez pour ouvrir/fermer le formulaire de modification..."
         });*/

        $("body").on('save-volume-horaire', function (event, popAjax) {
            var serviceId = popAjax.element.data('service');
            if ($("div .alert-warning", event.div).length ? false : true) { // si aucune erreur n'a été rencontrée
                popAjax.hide();
            }
            that.onAfterSaisie(serviceId);
        });
    }
    ,


    getElementPrevuToPrevu: function () {
        return this.element.find(".prevu-to-prevu")
    }
})
;


$.widget("ose.serviceForm", {

    updating: false,

    onInterneExterneChange: function () {
        if ('service-interne' == this.getInterneExterne()) {
            this.element.find('#element-externe').hide();
            this.getElementEtablissementId().val('');
            this.getElementEtablissementLabel().val('');
            this.element.find('#element-interne').show();
        } else {
            this.getElementEtablissementId().val('');
            this.getElementEtablissementLabel().val('');
            this.element.find('#element-interne').hide();
            this.getElementElementPedagogiqueId().val('');
            this.getElementElementPedagogiqueLabel().val('');
            this.getElementElementPedagogiqueListe().selectpicker('val', '');
            this.element.find('#element-externe').show();
        }
        this.updateVolumesHoraires();
    },

    updateVolumesHoraires: function () {
        var that = this;

        this.updating = true;
        this.updateVolumesHorairesSaisie();
        this.getElementVolumesHoraires().refresh({
            element: this.getElementElementPedagogiqueId().val(),
            etablissement: this.getElementEtablissementId().val()
        }, function () {
            that.getElementVolumesHoraires().find('input.form-control').val('0');
            that.updating = false;
            that.initVolumesHoraires();
        });
    },

    updateVolumesHorairesSaisie: function () {
        /* Volumes horaires en lecture seule si c'est en cours de mise à jour */
        var readOnly = this.updating;

        /* Volume horaires en lecture seule si on est en mode interne et qu'aucun élément n'est sélectionné */
        if ('service-interne' == this.getInterneExterne() && '' == this.getElementElementPedagogiqueId().val()) {
            readOnly = true;
        }

        this.getElementVolumesHoraires().find('input.form-control').prop('disabled', readOnly)
        this.getElementVolumesHoraires().find('button.prevu-to-realise').prop('disabled', readOnly);
    },

    prevuToRealise: function (periode) {
        var that = this;

        this.element.find("div.periode#"+periode+" input.form-control").each(function () {
            var id = $(this).attr('name').replace(periode+'[', 'prev-').replace(']', '');
            var value = that.element.find("div.periode#"+periode+" #"+id).data('heures');
            $(this).val(value);
        });
    },

    _create: function () {
        var that = this;

        /* Détection de changement d'état du radio interne-externe */
        this.getElementInterneExterne().on('change', function () {
            that.onInterneExterneChange();
        });

        /* Détection des changements d'éléments pédagogiques dans le formulaire de saisie */
        this.getElementElementPedagogiqueId().on("autocompleteselect", function () {
            that.updateVolumesHoraires();
        });

        this.getElementElementPedagogiqueId().on("change", function () {
            that.updateVolumesHoraires();
        });

        this.getElementEtablissementId().on("change", function () {
            that.updateVolumesHoraires();
        });

        this.getElementSubmit().on('click', function () {
            that.element.find("input[type='submit']").hide();
            that.element.find("#waiting-save-volume-horaire").show();
        });

        this.initVolumesHoraires();
    },

    initVolumesHoraires: function () {
        var that = this;

        /* Détection de click sur les boutons prévu => réalisé */
        this.element.find("button.prevu-to-realise").on('click', function () {
            var periode = $(this).parents('div.periode').attr('id');
            that.prevuToRealise(periode);
        });

        this.updateVolumesHorairesSaisie();
    },

    getInterneExterne: function () {
        var result = this.element.find('input[name="service\\[interne-externe\\]"]:checked').val();
        return result == undefined ? 'service-interne' : result;
    },

    getElementInterneExterne: function () {
        return this.element.find('input[name="service\\[interne-externe\\]"]');
    },
    getElementElementPedagogiqueId: function () {
        return this.element.find("input[name='service\\[element-pedagogique\\]\\[element\\]\\[id\\]']");
    },
    getElementElementPedagogiqueLabel: function () {
        return this.element.find("input[name='service\\[element-pedagogique\\]\\[element\\]\\[label\\]']");
    },
    getElementElementPedagogiqueListe: function () {
        return this.element.find("select#element-liste");
    },
    getElementEtablissementId: function () {
        return this.element.find("input[name='service\\[etablissement\\]\\[id\\]']");
    },
    getElementEtablissementLabel: function () {
        return this.element.find("input[name='service\\[etablissement\\]\\[label\\]']");
    },
    getElementTypeVolumeHoraire: function () {
        return this.element.find("input[name='type-volume-horaire']");
    },
    getElementSubmit: function () {
        return this.element.find("input[type='submit']");
    },
    getElementTypeVolumeHoraire: function () {
        return this.element.find("input[name='type-volume-horaire']");
    },

    getElementVolumesHoraires: function () {
        return this.element.find('div#volumes-horaires');
    }
});


$.widget("ose.serviceFiltres", {

    _create: function () {
        var that = this;

        this.getElementStructureAff().change(function () {
            that.changeIntervenant();
        });
        this.getElementTypeIntervenant().change(function () {
            that.changeIntervenant();
        });
        this.changeIntervenant();
    },

    changeIntervenant: function () {
        var url = this.getElementIntervenant().autocomplete("option", "source");
        var pi = url.indexOf('?');

        if (-1 !== pi) {
            url = url.substring(0, pi);
        }
        url += '?'+$.param({
            typeIntervenant: this.getElementTypeIntervenantSelected().val(),
            structure: this.getElementStructureAff().val(),
            'having-services': 1
        });
        this.getElementIntervenant().autocomplete("option", "source", url);

        if (this.getElementTypeIntervenantSelected().val() !== undefined && this.getElementTypeIntervenantSelected().data('intervenant-exterieur-id') == this.getElementTypeIntervenantSelected().val()) {
            this.element.find('#structure-aff-div').hide();
            this.getElementStructureAff().val('');
        } else {
            this.element.find('#structure-aff-div').show();
        }
    },

    getElementStructureAff: function () {
        return this.element.find('form.service-recherche select[name=\"structure-aff\"]');
    },
    getElementIntervenant: function () {
        return this.element.find('form.service-recherche input[name=\"intervenant[label]\"]')
    },
    getElementTypeIntervenant: function () {
        return this.element.find('form.service-recherche input[name=type-intervenant]')
    },
    getElementTypeIntervenantSelected: function () {
        return this.element.find('form.service-recherche input[name=type-intervenant]:checked')
    }
});
