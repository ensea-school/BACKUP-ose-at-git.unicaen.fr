
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
        } else {
            dateMiseEnPaiementElement.prop('disabled', true);
        }

        dateMiseEnPaiementElement.val(dates[periodeId]);
    },



    _create: function ()
    {
        var that = this;
        this.getPeriodeElement().change(function () {
            that.onPeriodeChange()
        });
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