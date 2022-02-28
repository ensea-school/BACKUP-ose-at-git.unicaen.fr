/**
 * Formulaire de demande de mise en paiement
 *
 * @constructor
 * @this {DemandeMiseEnPaiement}
 * @param {string} id
 * @returns {DemandeMiseEnPaiement}
 */
function DemandeMiseEnPaiement(id)
{
    this.id = id;
    this.element = $(".demande-mise-en-paiement#" + this.id);
    this.params = this.element.data('params');
    this.misesEnPaiementListes = {};
    this.miseEnPaiementSequence = 1;
    this.changes = {};

    this.showError = function (serviceElement, errorStr)
    {
        var out = '<div class="alert alert-danger alert-dismissible" role="alert">'
            + '<i class="fa fa-exclamation"></i> '
            + errorStr
            + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>'
            + '</div>';
        serviceElement.find(".breadcrumb").after(out);
    }



    /**
     *
     *
     * @returns {undefined}
     */
    this.demanderToutesHeuresEnPaiement = function ()
    {
        this.element.find(".heures-non-dmep:visible").click();
    }



    this.changeUpdate = function (miseEnPaiementId, propertyName, newValue)
    {
        if (this.changes[miseEnPaiementId] == undefined) {
            this.changes[miseEnPaiementId] = {};
        }
        this.changes[miseEnPaiementId][propertyName] = newValue;
    }



    this.changeInsert = function (miseEnPaiementId, properties)
    {
        this.changes[miseEnPaiementId] = properties;
    }



    this.changeDelete = function (miseEnPaiementId)
    {
        if (0 === miseEnPaiementId.indexOf('new-')) {
            delete this.changes[miseEnPaiementId];
        } else {
            this.changes[miseEnPaiementId] = 'removed';
        }
    }



    /**
     *
     * @returns {Boolean}
     */
    this.valider = function ()
    {
        var result = true;
        var services = {};
        for (var id in this.misesEnPaiementListes) {
            if (!this.misesEnPaiementListes[id].valider()) result = false;

            var sapEl = this.misesEnPaiementListes[id].getServiceAPayerElement();
            if (undefined == services[sapEl.attr("id")]) {
                services[sapEl.attr("id")] = {
                    total: 0,
                    dmep: 0,
                    mep: 0
                };
            }
            services[sapEl.attr("id")].total += this.misesEnPaiementListes[id].getHeuresTotal();
            services[sapEl.attr("id")].mep += this.misesEnPaiementListes[id].getHeuresMEP();
            services[sapEl.attr("id")].dmep += this.misesEnPaiementListes[id].getHeuresDMEP();
        }
        totalServices = 0;
        totalDmep = 0;
        for (var id in services) {
            totalServices += Math.round(services[id].total * 100);
            totalDmep += Math.round((services[id].mep + services[id].dmep) * 100);
            if (Math.round((services[id].mep + services[id].dmep) * 100) > Math.round(services[id].total * 100) && services[id].dmep > 0) {
                this.showError(
                    this.element.find('.service-a-payer#' + id),
                    'Le nombre d\'heures mises en paiement ou demandées dépasse le nombre heures disponibles'
                );
                result = false;
            }
        }

        if (totalDmep > totalServices) {
            alert('Le nombre d\'heures demandées en paiement dépasse le nombre total d\'HETD à payer : il y en a ' + ((totalDmep - totalServices) / 100) + ' de trop');
            result = false;
        }

        return result;
    }



    /**
     *
     * @returns {boolean}
     */
    this.sauvegarder = function ()
    {
        if (!this.valider()) {
            alert('Enregistrement impossible');
            return false;
        }
        this.element.find("form input[name='changements']").val(JSON.stringify(this.changes));
        return true;
    }



    /**
     * Initialisation
     *
     * @returns {undefined}
     */
    this.init = function ()
    {
        var that = this;

        this.element.find(".mise-en-paiement-liste").each(function ()
        {
            var id = $(this).attr('id');

            that.misesEnPaiementListes[id] = new MiseEnPaiementListe(that, $(this));
            that.misesEnPaiementListes[id].init();
        });

        this.element.find(".toutes-heures-non-dmep").on("click", function ()
        {
            that.demanderToutesHeuresEnPaiement();
        });

        this.element.find("form").on("submit", function ()
        {
            return that.sauvegarder();
        });
    }
}

/**
 *
 * @param {string} id
 * @returns {DemandeMiseEnPaiement}
 */
DemandeMiseEnPaiement.get = function (id)
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
function MiseEnPaiementListe(demandeMiseEnPaiement, element)
{
    this.demandeMiseEnPaiement = demandeMiseEnPaiement;
    this.id = element.attr('id');
    this.element = element;
    this.params = element.data('params');
    this.validation = true;
    this.initializing = true;



    /**
     * Détermine si la liste est en lecture seule ou non
     *
     * @returns {boolean}
     */
    this.isReadOnly = function ()
    {
        return this.element.hasClass('read-only');
    }


    this.valider = function ()
    {
        var that = this;

        this.validation = true;

        if (this.isReadOnly()) return true; // pas de validation puisque c'est en lecture seule!!

        if (this.params['heures-non-dmep'] < 0) {
            this.showError('Trop d\'heures de paiement ont été demandées.');
        }

        this.element.find("select[name='centre-cout']").each(function ()
        {
            if ($(this).val() == '') {
                that.showError('Centre de coûts à définir.');
            }
        });

        this.element.find("select[name='domaine-fonctionnel']").each(function ()
        {
            if ($(this).val() == '') {
                that.showError('Domaine fonctionnel à définir.');
            }
        });

        return this.validation;
    }



    this.showError = function (errorStr)
    {
        var out = '<div class="alert alert-danger alert-dismissible" role="alert">'
            + '<i class="fa fa-exclamation"></i> '
            + errorStr
            + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>'
            + '</div>';
        this.element.parents('.type-heures').prepend(out);
        this.validation = false;
    }



    /**
     *
     * @param {string} id
     * @returns {DemandeMiseEnPaiement}
     */
    this.removeMiseEnPaiement = function (id)
    {
        miseEnPaiement = this.element.find(".mise-en-paiement#" + id);
        miseEnPaiement.empty();
        this.params['demandes-mep'][id] = 'removed';
        this.demandeMiseEnPaiement.changeDelete(id);
        this.updateHeuresRestantes();
        this.onChange();
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
    this.addMiseEnPaiement = function (id, heures, centreCoutId, domaineFonctionnelId, focus)
    {
        var that = this;
        var isNew = undefined === id;

        if (isNew) {
            id = 'new-' + this.demandeMiseEnPaiement.miseEnPaiementSequence++;
            this.params['demandes-mep'][id] = {
                heures: heures,
                'centre-cout-id': centreCoutId,
                'domaine-fonctionnel-id': domaineFonctionnelId,
                'read-only': false,
                'validation': null
            };
        }

        this.element.append(this.renderMiseEnPaiement(id));

        WidgetInitializer.includeJs(Url('vendor/bootstrap-select-1.9.4/dist/js/bootstrap-select.min.js'));
        WidgetInitializer.includeCss(Url('vendor/bootstrap-select-1.9.4/dist/css/bootstrap-select.min.css'));
        $('.selectpicker').selectpicker();

        if (isNew) {
            var mepParams = jQuery.extend({}, this.params['mep-defaults']);
            mepParams['heures'] = this.params['demandes-mep'][id]['heures'];
            mepParams['centre-cout-id'] = this.params['demandes-mep'][id]['centre-cout-id'];
            mepParams['domaine-fonctionnel-id'] = this.params['demandes-mep'][id]['domaine-fonctionnel-id'];
            this.demandeMiseEnPaiement.changeInsert(id, mepParams);
        }

        /* Connexion des événements */
        var heuresElement = this.element.find(".mise-en-paiement#" + id + " input[name='heures']");
        heuresElement.on('change', function ()
        {
            that.onHeuresChange($(this));
        });

        this.element.find(".mise-en-paiement#" + id + " select[name='centre-cout']").on('change', function ()
        {
            that.onCentreCoutChange($(this));
        });

        this.element.find(".mise-en-paiement#" + id + " select[name='domaine-fonctionnel']").on('change', function ()
        {
            that.onDomaineFonctionnelChange($(this));
        });

        this.element.find(".mise-en-paiement#" + id + " .action-delete").on('click', function ()
        {
            that.removeMiseEnPaiement(id);
        });

        /* Finalisation */
        if (focus) heuresElement.focus();
        this.updateHeuresRestantes();
        this.onChange();
        return this;
    }



    this.renderMiseEnPaiement = function (id)
    {
        var data = this.params['demandes-mep'][id];

        var out = '<tr class="mise-en-paiement" id="' + id + '"><td class="nombre" style="vertical-align:middle">';
        out += this.renderHeures(data);
        out += '</td><td style="vertical-align:middle">';
        out += this.renderCentreCout(data);

        if (0 < Util.json.count(this.params['domaines-fonctionnels'])) {
            out += '</td><td style="vertical-align:middle">';
            out += this.renderDomaineFonctionnel(data);
        }

        out += '</td><td style="vertical-align:middle;text-align:center">';
        out += this.renderActions(data);
        out += '</td></tr>';

        return out;
    }



    this.renderHeures = function (data)
    {
        var out;
        var max = this.params['heures-total'] - this.params['heures-mep'];

        if (data['read-only']) {
            out = Util.formattedHeures(data['heures']);
        } else {
            out = '<input name="heures" class="form-control input-sm" step="any" min="0" max="' + max + '" value="' + data['heures'] + '" type="number" />';
        }

        return out;
    }



    this.renderCentreCout = function (data)
    {
        var outC = '';

        ccCount = Util.json.count(this.params['centres-cout']);
        if (ccCount == 1 || data['read-only']) {
            if (data['validation'] != undefined) {
                outC += '<abbr title="Validé par ' + data['validation']['utilisateur'] + ' le ' + data['validation']['date'] + '">';
            }
            outC += '&nbsp;' + Util.json.first(this.params['centres-cout'])['libelle'];
            if (data['validation'] != undefined) {
                outC += ' </span></abbr>';
            }
        } else if (ccCount > 1) {
            outC = '<select name="centre-cout" class="selectpicker" data-width="100%" data-live-search="true">';
            if (undefined == data['centre-cout-id']) {
                outC += '<option value="" selected="selected">&Agrave; préciser ...</option>';
            }
            for (var ccId in this.params['centres-cout']) {
                var children = this.centreCoutGetChildren(ccId);
                if (Util.json.count(children) > 0) {
                    outC += '<optgroup label="' + this.params['centres-cout'][ccId]['libelle'] + '">';
                    for (var cccId in children) {
                        outC += this.renderCentreCoutOption(this.params['centres-cout'][cccId], cccId, data['centre-cout-id']);
                    }
                    outC += this.renderCentreCoutOption(this.params['centres-cout'][ccId], ccId, data['centre-cout-id']);
                    outC += '</optgroup>';
                } else if (this.params['centres-cout'][ccId]['parent'] == null) {
                    outC += this.renderCentreCoutOption(this.params['centres-cout'][ccId], ccId, data['centre-cout-id']);
                }
            }
            outC += '</select>';
        } else {
            outC = '<div class="alert alert-danger" role="alert">Aucun centre de coûts ne correspond. Saisie impossible.</div>';
        }

        return outC;
    }



    this.renderCentreCoutOption = function (params, value, selVal)
    {
        var selected = value == selVal ? ' selected="selected"' : '';
        var bad = params['bad'] != undefined;
        var badText = '<span title=\'Centre de coûts supprimé ou incohérent\' class=\'label label-danger\'>' + params['libelle'] + '</span>';
        var badVal = bad ? 'data-content="' + badText + '"' : '';
        return '<option value="' + value + '"' + selected + badVal + '>' + params['libelle'] + '</option>';
    }



    this.renderDomaineFonctionnel = function (data)
    {
        var outDF = '';

        ;
        if (0 == Util.json.count(this.params['domaines-fonctionnels'])) {
            return '';
        }

        outDF = '<select name="domaine-fonctionnel" class="selectpicker" data-width="100%" data-live-search="true">';
        if (undefined == data['domaine-fonctionnel-id']) {
            outDF += '<option value="" selected="selected">&Agrave; préciser ...</option>';
        }
        for (var dfId in this.params['domaines-fonctionnels']) {
            var selected = dfId == data['domaine-fonctionnel-id'] ? ' selected="selected"' : '';
            outDF += '<option value="' + dfId + '"' + selected + '>' + this.params['domaines-fonctionnels'][dfId] + '</option>';
        }
        outDF += '</select>';

        return outDF;
    }


    this.renderActions = function (data)
    {
        var outA;

        if (data['read-only']) {
            if (data['validation'] != undefined) {
                outA = '<i class="fa fa-check-circle" title="Validé par ' + data['validation']['utilisateur'] + ' le ' + data['validation']['date'] + '">';
            } else {
                outA = '';
            }
        } else {
            outA = '<a role="button" class="action-delete" title="Supprimer la ligne"><i class="fa fa-times"></i></a>';
        }

        return outA;
    }



    /**
     *
     * @param {integer} centreCoutId
     * @returns {undefined}
     */
    this.centreCoutGetChildren = function (centreCoutId)
    {
        var result = {};
        for (var ccId in this.params['centres-cout']) {
            if (this.params['centres-cout'][ccId]['parent'] == centreCoutId) {
                result[ccId] = this.params['centres-cout'][ccId];
            }
        }
        return result;
    }



    /**
     *
     * @param {Object} element
     */
    this.onHeuresChange = function (element)
    {
        var miseEnPaiementId = element.parents('.mise-en-paiement').attr('id');
        var heures = Formatter.stringToFloat(element.val());

        this.demandeMiseEnPaiement.changeUpdate(miseEnPaiementId, 'heures', heures);
        if (heures > 0) {
            this.params['demandes-mep'][miseEnPaiementId]['heures'] = heures;
            this.updateHeuresRestantes();
            this.onChange();
        } else {
            this.removeMiseEnPaiement(miseEnPaiementId);
        }
    }



    /**
     *
     * @param {Object} element
     * @returns {undefined}
     */
    this.onCentreCoutChange = function (element)
    {
        var miseEnPaiementId = element.parents('.mise-en-paiement').attr('id');
        var centreCoutId = element.val();
        this.params['demandes-mep'][miseEnPaiementId]['centre-cout-id'] = centreCoutId;
        this.demandeMiseEnPaiement.changeUpdate(miseEnPaiementId, 'centre-cout-id', centreCoutId);
        this.onChange();
    }



    /**
     *
     * @param {Object} element
     * @returns {undefined}
     */
    this.onDomaineFonctionnelChange = function (element)
    {
        var miseEnPaiementId = element.parents('.mise-en-paiement').attr('id');
        var domaineFonctionnelId = element.val();
        this.demandeMiseEnPaiement.changeUpdate(miseEnPaiementId, 'domaine-fonctionnel-id', domaineFonctionnelId);
    }



    /**
     *
     * @returns {undefined}
     */
    this.onAddHeuresRestantes = function ()
    {
        if (this.params['heures-non-dmep'] < 0) {
            alert('Il est impossible d\'ajouter des HETD négatifs.');
        } else {
            this.addMiseEnPaiement(undefined, this.params['heures-non-dmep'], this.params['default-centre-cout'], this.params['default-domaine-fonctionnel'], true);
        }
    }



    /**
     *
     * @returns {undefined}
     */
    this.updateHeuresRestantes = function ()
    {
        this.params['heures-dmep'] = 0;
        for (var miseEnPaiementId in this.params['demandes-mep']) {
            if (this.params['demandes-mep'][miseEnPaiementId] !== 'removed') {
                this.params['heures-dmep'] += this.params['demandes-mep'][miseEnPaiementId]['heures'];
            }
        }
        this.params['heures-non-dmep'] = this.params['heures-total'] - this.params['heures-mep'] - this.params['heures-dmep'];
        if (this.params['heures-total'] < this.params['heures-mep']) {
            this.params['heures-non-dmep'] += this.params['heures-mep'] - this.params['heures-total'];
        }
        this.params['heures-non-dmep'] = Math.round(this.params['heures-non-dmep'] * 100) / 100;

        this.element.find('.heures-non-dmep').html(Util.formattedHeures(this.params['heures-non-dmep']));

        if (Math.round((this.params['heures-dmep'] + this.params['heures-mep']) * 100) / 100 > this.params['heures-total']) {
            this.element.addClass('bg-danger');
            if (0 == this.params['heures-non-dmep']) {
                this.element.find('.heures-non-dmep').parents('tr').hide();
            } else {
                this.element.find('.heures-non-dmep').parents('tr').show();
            }
        } else {
            this.element.removeClass('bg-danger');
            if (0 == this.params['heures-non-dmep']) {
                this.element.find('.heures-non-dmep').parents('tr').hide();
                this.element.addClass('bg-success');
            } else {
                this.element.find('.heures-non-dmep').parents('tr').show();
                this.element.removeClass('bg-success');
            }
        }
    }



    /**
     * Initialisation des lignes du formulaire à partir des données
     *
     * @returns {undefined}
     */
    this.populate = function ()
    {
        for (var miseEnPaiementId in this.params['demandes-mep']) {
            this.addMiseEnPaiement(miseEnPaiementId);
        }
        this.updateHeuresRestantes();
    }



    /**
     * Initialisation
     *
     * @returns {undefined}
     */
    this.init = function ()
    {
        var that = this;
        this.element.find('.heures-non-dmep').on('click', function ()
        {
            that.onAddHeuresRestantes();
        });
        this.populate();
        this.initializing = false;
    }



    this.onChange = function ()
    {
        if (!this.initializing) {
            $('.dmep-budget').dmepBudget('changed');
        }
    }



    this.getHeuresTotal = function ()
    {
        return this.params['heures-total'];
    }


    this.getHeuresDMEP = function ()
    {
        return this.params['heures-dmep'];
    }


    this.getHeuresMEP = function ()
    {
        return this.params['heures-mep'];
    }


    this.getServiceAPayerElement = function ()
    {
        return this.element.parents("div.service-a-payer");
    }
}





/**
 * paiementMiseEnPaiementRechercheForm
 */
$.widget("ose.paiementMiseEnPaiementRechercheForm", {

    onTypeIntervenantChange: function ()
    {
        var structureElement = this.getStructureElement()
        if (structureElement) {
            structureElement.val('');
        }
        this.onStructureChange();
    },

    onStructureChange: function ()
    {
        var periodeElement = this.getPeriodeElement()
        if (periodeElement) {
            periodeElement.val('');
        }
        this.onPeriodeChange();
    },

    onPeriodeChange: function ()
    {
        this.intervenantsSelectNone();
        this.getSuiteElement().click();
    },

    onIntervenantsChange: function ()
    {
        if (this.getIntervenantsElement().is(':visible')) {
            this.getSuiteElement().hide();
            if (this.getIntervenantsElement().val() == null) {
                this.hideActions();
            } else {
                this.showActions();
            }
        } else {
            this.getSuiteElement().show();
            this.hideActions();
        }
    },

    hideActions: function ()
    {
        this.getAfficherElement().hide();
        this.getExporterPdfElement().hide();
        this.getExporterCsvEtat().hide();
        this.getExporterCsvWinpaie().hide();
    },

    showActions: function ()
    {
        this.getAfficherElement().show();
        this.getExporterPdfElement().show();
        this.getExporterCsvEtat().show();
        this.getExporterCsvWinpaie().show();
    },

    intervenantsSelectAll: function ()
    {
        this.getIntervenantsElement().find("option").prop("selected", "selected");
        this.onIntervenantsChange();
    },

    intervenantsSelectNone: function ()
    {
        this.getIntervenantsElement().val([]);
        this.onIntervenantsChange();
    },





    _create: function ()
    {
        var that = this;

        var $radios = this.element.find('input:radio[name=type-intervenant]');
        if ($radios.is(':checked') === false) {
            $radios.filter('[value=""]').attr('checked', true);
        }

        this.getTypeIntervenantElement().change(function () { that.onTypeIntervenantChange() });
        this.getStructureElement().change(function () { that.onStructureChange() });
        this.getPeriodeElement().change(function () { that.onPeriodeChange() });
        this.getIntervenantsElement().change(function () { that.onIntervenantsChange() });

        $("body").on("mise-en-paiement-form-submit", function (event, data)
        {
            if ($("div .messenger, div .alert", event.div).length ? false : true) {

                document.location.href = event.a.data('url-redirect');
            }
        });
        this.onIntervenantsChange();
    },

    getTypeIntervenantElement: function ()
    {
        return this.element.find('[name="type-intervenant"]');
    },

    getStructureElement: function ()
    {
        return this.element.find('[name="structure"]');
    },

    getPeriodeElement: function ()
    {
        return this.element.find('[name="periode"]');
    },

    getIntervenantsElement: function ()
    {
        return this.element.find('[name="intervenants[]"]');
    },

    getSuiteElement: function ()
    {
        return this.element.find('[name="suite"]');
    },

    getAfficherElement: function ()
    {
        return this.element.find('[name="afficher"]');
    },

    getExporterPdfElement: function ()
    {
        return this.element.find('[name="exporter-pdf"]');
    },

    getExporterCsvEtat: function ()
    {
        return this.element.find('[name="exporter-csv-etat"]');
    },

    getExporterCsvWinpaie: function ()
    {
        return this.element.find('[name="exporter-csv-winpaie"]');
    },

    getEtat: function ()
    {
        return this.element.parents('.filter').data('etat');
    }

});

$(function ()
{
    WidgetInitializer.add('paiement-mise-en-paiement-recherche-form', 'paiementMiseEnPaiementRechercheForm');
});





$.widget("ose.paiementMiseEnPaiementForm", {

    onPeriodeChange: function ()
    {
        var periodeId = this.getPeriodeElement().val();
        var dates = this.element.data('dates-mise-en-paiement');
        var periodePaiementTardifId = this.element.data('periode-paiement-tardif-id');
        var dateMiseEnPaiementElement = this.getDateMiseEnPaiementElement();

        if (periodeId == periodePaiementTardifId) {
            dateMiseEnPaiementElement.prop('disabled', false);
            dateMiseEnPaiementElement.datepicker(); // pour le rafraichissement!!
        } else {
            dateMiseEnPaiementElement.prop('disabled', true);
        }

        dateMiseEnPaiementElement.val(dates[periodeId]);
    },



    _create: function ()
    {
        var that = this;
        this.getPeriodeElement().change(function () { that.onPeriodeChange() });
    },



    getPeriodeElement: function ()
    {
        return this.element.find('[name="periode"]');
    },



    getDateMiseEnPaiementElement: function ()
    {
        return this.element.find('[name="date-mise-en-paiement"]');
    }
});

$(function ()
{
    WidgetInitializer.add('paiement-mise-en-paiement-form', 'paiementMiseEnPaiementForm');
});





$.widget("ose.dmepBudget", {

    oriData: undefined,
    depassement: false,


    _create: function ()
    {
        var that = this;

        this.oriData = this.getData();
        this.getElementsEnveloppes().each(function ()
        {
            that.setDiffValue($(this).data('type-ressource'), $(this).data('structure'), 0);
        });

        this.updateBlocageDepassement();

        setTimeout(function () { that.update() }, 5000);
    },



    update: function ()
    {
        var that = this;
        var updateUrl = this.element.data('update-url');
        var diffData = this.getDiffData();

        data = $.getJSON(updateUrl, function (data) {

            that.getElementsEnveloppes().each(function ()
            {
                var structureId = $(this).data('structure');
                var typeRessourceId = $(this).data('type-ressource');

                if (data[structureId] !== undefined && data[structureId][typeRessourceId] !== undefined) {
                    var value = data[structureId][typeRessourceId];
                    var diffVal = 0;
                    if (diffData[structureId] !== undefined && diffData[structureId][typeRessourceId] !== undefined) {
                        diffVal = diffData[structureId][typeRessourceId];
                    }

                    $(this).data('dotation', value['dotation']);
                    $(this).data('usage', value['usage']);

                    that.setDiffValue(typeRessourceId, structureId, diffVal); // MAJ
                }

            });

            that.updateBlocageDepassement();
        });

        setTimeout(function () { that.update() }, 5000);
    },



    changed: function ()
    {
        var that = this;
        var data = this.getDiffData();

        this.getElementsEnveloppes().each(function ()
        {
            var structureId = $(this).data('structure');
            var typeRessourceId = $(this).data('type-ressource');
            var value = 0;

            if (data[structureId] !== undefined && data[structureId][typeRessourceId] !== undefined) {
                value = data[structureId][typeRessourceId];
            }

            if (!that.setDiffValue(typeRessourceId, structureId, value)) {
                depassement = true;
            }
        });

        this.updateBlocageDepassement();
    },



    getDiffData: function ()
    {
        var data = this.getData();

        for (structureId in this.oriData) {
            for (typeRessourceId in this.oriData[structureId]) {
                if (!data[structureId]) {
                    data[structureId] = {};
                }
                if (!data[structureId][typeRessourceId]) {
                    data[structureId][typeRessourceId] = 0;
                }
                data[structureId][typeRessourceId] -= this.oriData[structureId][typeRessourceId];
            }
        }
        return data;
    },



    getData: function ()
    {
        var data = {};

        $('.demande-mise-en-paiement .mise-en-paiement').each(function ()
        {
            var id = $(this).attr('id');
            var params = $(this).parents('.mise-en-paiement-liste').data('params');
            var structureId = params['structure-id'];
            var centreCoutId = params['demandes-mep'][id]['centre-cout-id'];
            var typeRessourceId = undefined;
            var heures = params['demandes-mep'][id]['heures'];

            if (centreCoutId) {
                typeRessourceId = params['centres-cout'][centreCoutId]['type-ressource-id'];

                if (!data[structureId]) {
                    data[structureId] = {};
                }
                if (!data[structureId][typeRessourceId]) {
                    data[structureId][typeRessourceId] = 0;
                }
                data[structureId][typeRessourceId] += heures;
            }
        });

        return data;
    },



    setDiffValue: function (typeRessourceId, structureId, value)
    {
        var progress = this.getElementEnveloppe(typeRessourceId, structureId);
        var bar = progress.find('.progressbar');
        var dotation = progress.data('dotation');
        var usage = progress.data('usage') + value;
        var restant = dotation - usage;
        var percent = 100 - Math.round(usage * 10000 / dotation) / 100;
        if (restant <= 0) {
            percent = 0;
        }
        if (percent < 0) percent = 0;

        bar.removeClass('progress-bar-success progress-bar-info progress-bar-warning progress-bar-danger active');
        if (percent < 10) {
            bar.addClass('progress-bar-warning active');
        } else {
            bar.addClass('progress-bar-success');
        }
        bar.attr('aria-valuenow', percent);
        bar.attr('style', 'width:' + percent + '%');
        if (restant >= 0) {
            bar.html('<span class="restant">' + Formatter.floatToString(restant) + '</span><span class="label">HETD disponibles</span>');
        } else {
            bar.html('<span class="restant deficit">' + Formatter.floatToString(restant * -1) + '</span><span class="label deficit">HETD de déficit</span>');
        }
        if (restant < 0) this.depassement = true;

        return restant >= 0;
    },



    updateBlocageDepassement: function ()
    {
        if (this.depassement) {
            $('.demande-mise-en-paiement .sauvegarde').hide();
            $('.demande-mise-en-paiement .depassement-budget').show();
        } else {
            $('.demande-mise-en-paiement .sauvegarde').show();
            $('.demande-mise-en-paiement .depassement-budget').hide();
        }
        this.depassement = false;
    },



    getElementEnveloppe: function (typeRessourceId, structureId)
    {
        return this.element.find('.enveloppe[data-type-ressource=' + typeRessourceId + '][data-structure=' + structureId + ']');
    },



    getElementsEnveloppes: function ()
    {
        return this.element.find('.enveloppe');
    }
});

$(function ()
{
    WidgetInitializer.add('dmep-budget', 'dmepBudget');
});
