function ElementPedagogiqueRecherche(id)
{
    this.id = id;
    this.element = $(".element-pedagogique-recherche#" + this.id);
    this.relations = this.element.data('relations');

    this.updateValues = function ()
    {
        var structureId = this.getStructureElement().val();
        var niveauId = this.getNiveauElement().val();
        var etapeId = this.getFormationElement().val();
        var elementId = this.getElementId();
        var lastEtapeId = etapeId;

        var niveauxValues = [];
        for (nId in this.relations[structureId ? structureId : 'ALL']) {
            niveauxValues.push(nId);
        }
        var etapesValues = this.relations[structureId ? structureId : 'ALL'][niveauId ? niveauId : 'ALL'];
        if ($.inArray(etapeId, etapesValues) == -1) {
            etapeId = "";
        }

        this.filterSelect(this.getNiveauElement(), niveauxValues);
        this.filterSelect(this.getFormationElement(), etapesValues);

        var query = {
            structure: structureId,
            niveau: niveauId,
            etape: etapeId,
            elementPedagogique: elementId
        };
        var url = this.element.data('default-url') + '?' + $.param(query);

        var that = this;
        if (etapeId != "") {
            this.setElementState('wait');
            $.get(url, function (data)
            {
                that.populateElements(data);
            });
        } else {
            this.getElementAutocompleteElement().autocomplete("option", "source", url);
            this.setElementState('search');
        }
    }

    this.updateElementValue = function ()
    {
        var id = this.getElementListeElement().val();
        var label = this.getElementListeElement().find(":selected").text();
        var lastVal = this.element.find('input#element').val();

        this.element.find('input#element').val(id);
        if (lastVal != id) this.element.find('input#element').trigger("change");
        this.getElementAutocompleteElement().val(label);
    }

    this.filterSelect = function (select, values)
    {
        var ul = select.next().find('ul');
        select.find('option').each(function ()
        {

            var li = ul.find("li[data-original-index='" + this.index + "']");

            if (this.index == 0 || $.inArray(this.value, values) !== -1) {
                li.show();
            } else {
                if (select.val() == this.value) {
                    select.selectpicker('val', '');
                }
                li.hide();
            }

        });
    }

    this.setElementState = function (state)
    {
        switch (state) {
            case 'liste':
                this.element.find("#ep-liste").show();
                this.element.find("#ep-wait").hide();
                this.element.find("#ep-search").hide();
                break;
            case 'wait':
                this.element.find("#ep-liste").hide();
                this.element.find("#ep-wait").show();
                this.element.find("#ep-search").hide();
                break;
            case 'search':
                this.element.find("#ep-liste").hide();
                this.element.find("#ep-wait").hide();
                this.element.find("#ep-search").show();
                break;
        }
    }

    this.populateElements = function (data)
    {
        var select = this.getElementListeElement();
        var value = this.getElementId();

        select.empty();
        if (Util.json.count(data) > 1) {
            select.append(
                $('<option>').text('(Aucun enseignement sélectionné)').val('')
            );
        }
        for (var i in data) {
            select.append(
                $('<option>').text(data[i].label).val(data[i].id)
            );
        }
        select.val(value);
        select.selectpicker('refresh');
        this.setElementState('liste');
        this.updateElementValue();
    }

    this.getElementId = function ()
    {
        return this.element.find('input#element').attr('value');
    }

    this.getStructureElement = function ()
    {
        return this.element.find('select#structure');
    }

    this.getNiveauElement = function ()
    {
        return this.element.find('select#niveau');
    }

    this.getFormationElement = function ()
    {
        return this.element.find('select#formation');
    }

    this.getElementElement = function ()
    {
        return this.element.find('select#element');
    }

    this.getElementListeElement = function ()
    {
        return this.element.find('select#element-liste');
    }

    this.getElementAutocompleteElement = function ()
    {
        return this.element.find('input#element-autocomplete');
    }

    this.init = function ()
    {
        var that = this;

        this.getStructureElement().change(function () { that.updateValues(); });
        this.getNiveauElement().change(function () { that.updateValues(); });
        this.getFormationElement().change(function () { that.updateValues(); });
        this.getElementListeElement().change(function () { that.updateElementValue(); });
        this.updateValues();
    }

}

/**
 *
 * @param {string} id
 * @returns {PaiementMiseEnPaiementForm}
 */
ElementPedagogiqueRecherche.get = function (id)
{
    if (null == ElementPedagogiqueRecherche.instances) ElementPedagogiqueRecherche.instances = new Array();
    if (null == ElementPedagogiqueRecherche.instances[id]) ElementPedagogiqueRecherche.instances[id] = new ElementPedagogiqueRecherche(id);
    return ElementPedagogiqueRecherche.instances[id];
}





/**
 * etapeCentreCout
 */
$.widget("ose.etapeCentreCout", {

    _create: function ()
    {
        var that = this;

        this.element.find("button.form-set-value").click( function (e)
        {
            var typeHeuresCode = $(this).data('code');
            var value = that.getElementEtapeSelect(typeHeuresCode).val();
            that.setFormValues(typeHeuresCode, value);
            e.stopPropagation();
        });
    },

    setFormValues: function (typeHeuresCode, value)
    {
        this.getElementElementSelects(typeHeuresCode).each(function ()
        {
            var canSetValue = value == "" || $(this).find("option[value=" + value + "]").length > 0;
            if (canSetValue) {
                $(this).selectpicker('val', value);
            }
        });
    },

    getElementEtapeSelect: function (typeHeuresCode)
    {
        return this.element.find('select[name="' + typeHeuresCode + '"]');
    },

    getElementElementSelects: function (typeHeuresCode)
    {
        return this.element.find('select[name$="\\[' + typeHeuresCode + '\\]"]');
    }

});

$(function ()
{
    WidgetInitializer.add('etape-centre-cout', 'etapeCentreCout');
});





/**
 * etapeModulateurs
 */
$.widget("ose.etapeModulateurs", {

    _create: function ()
    {
        var that = this;

        this.getElementElementSelects()
        this.element.find("button.form-set-value").click( function ()
        {
            var typeModulateurCode = $(this).data('code');
            var value = that.getElementEtapeSelect(typeModulateurCode).val();
            that.setFormValues(typeModulateurCode, value);
            return false;
        });
    },

    setFormValues: function (typeModulateurCode, value)
    {
        this.getElementElementSelects(typeModulateurCode).val(value);
    },

    getElementEtapeSelect: function (typeModulateurCode)
    {
        return this.element.find('select[name="' + typeModulateurCode + '"]');
    },

    getElementElementSelects: function (typeModulateurCode)
    {
        return this.element.find('select[name$="\\[' + typeModulateurCode + '\\]"]');
    }

});

$(function ()
{
    WidgetInitializer.add('etape-modulateurs', 'etapeModulateurs');
});





/**
 * etapeSaisie
 */
$.widget("ose.etapeSaisie", {

    onAjouter: function(event)
    {
        window.location = updateQueryStringParameter(window.location.href, "etape", this.getId());
    },

    onModifier: function(event)
    {
        window.location = updateQueryStringParameter(window.location.href, "etape", this.getId());
    },

    getId: function()
    {
        return this.element.find('input[name=id]').val();
    }
});

$(function ()
{
    WidgetInitializer.add('etape-saisie', 'etapeSaisie');
    $("body").on("etape-ajouter", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        event.div.find('.etape-saisie').etapeSaisie();
        event.div.find('.etape-saisie').etapeSaisie('onAjouter', event);
    });
    $("body").on("etape-modifier", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        event.div.find('.etape-saisie').etapeSaisie();
        event.div.find('.etape-saisie').etapeSaisie('onModifier', event);
    });
    $("body").on("etape-supprimer", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        window.location.reload();
    });
});





/**
 * elementPedagogiqueSaisie
 */
$.widget("ose.elementPedagogiqueSaisie", {

    onAjouter: function(event)
    {
        window.location.reload();
    },

    onModifier: function(event)
    {
        window.location.reload();
    },

});

$(function ()
{
    WidgetInitializer.add('element-pedagogique-saisie', 'elementPedagogiqueSaisie');
    $("body").on("element-pedagogique-ajouter", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        event.div.find('.element-pedagogique-saisie').elementPedagogiqueSaisie();
        event.div.find('.element-pedagogique-saisie').elementPedagogiqueSaisie('onAjouter', event);
    });
    $("body").on("element-pedagogique-modifier", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        event.div.find('.element-pedagogique-saisie').elementPedagogiqueSaisie();
        event.div.find('.element-pedagogique-saisie').elementPedagogiqueSaisie('onModifier', event);
    });
    $("body").on("element-pedagogique-supprimer", function(event, data) {
        event.div.modal('hide'); // ferme la fenêtre modale
        window.location.reload();
    });
});