/**
 * elementPedagogiqueRecherche
 */
$.widget("ose.elementPedagogiqueRecherche", {

    updateValues: function ()
    {
        var structureId = this.getStructureElement().val();
        var niveauId = this.getNiveauElement().val();
        var etapeId = this.getFormationElement().val();
        var elementId = this.getElementId();

        var niveauxValues = [];
        for (nId in this.relations[structureId ? structureId : 'ALL']) {
            niveauxValues.push(nId);
        }

        var etapesValues = this.relations[structureId ? structureId : 'ALL'][niveauId ? niveauId : 'ALL'];
        if ($.inArray(etapeId, etapesValues) == -1) {
            etapeId = "";
        }

        Util.filterSelectPicker(this.getNiveauElement(), niveauxValues);
        Util.filterSelectPicker(this.getFormationElement(), etapesValues);

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
            this.setElementState('search');
            this.getElementAutocompleteElement().autocomplete("option", "source", url);
        }
    },

    updateElementValue: function ()
    {
        var id = this.getElementListeElement().val();
        var label = this.getElementListeElement().find(":selected").text();
        var lastVal = this.element.find('input#element').val();

        this.element.find('input#element').val(id);
        if (lastVal != id) this.element.find('input#element').trigger("change");
        this.getElementAutocompleteElement().val(label);
    },

    setElementState: function (state)
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
    },

    populateElements: function (data)
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
            var option = $('<option>');
            option.val(data[i].id);
            option.text(data[i].label);
            option.data('subtext', data[i].extra);
            select.append(option);
        }

        if (Util.json.count(data) == 1) {
            value = data[i].id;
        }

        select.val(value);
        select.selectpicker('destroy');
        select.selectpicker();
        this.setElementState('liste');
        this.updateElementValue();
    },

    _create: function ()
    {
        var that = this;

        this.relations = this.element.data('relations');
        console.log(this.relations);
        this.getElementAutocompleteElement().autocomplete();
        this.getElementElement().hide();

        this.getStructureElement().change(function () { that.updateValues(); });
        this.getNiveauElement().change(function () { that.updateValues(); });
        this.getFormationElement().change(function () { that.updateValues(); });
        this.getElementListeElement().change(function () { that.updateElementValue(); });
        this.updateValues();
    },

    getElementId: function ()
    {
        return this.getElementElement().attr('value');
    },

    getStructureElement: function ()
    {
        return this.element.find('select#structure');
    },

    getNiveauElement: function ()
    {
        return this.element.find('select#niveau');
    },

    getFormationElement: function ()
    {
        return this.element.find('select#formation');
    },

    getElementElement: function ()
    {
        return this.element.find('input#element');
    },
    getElementListeElement: function ()
    {
        return this.element.find('select#element-liste');
    },

    getElementAutocompleteElement: function ()
    {
        return this.element.find('input#element-autocomplete');
    }

})
;





/**
 * etapeCentreCout
 */
$.widget("ose.etapeCentreCout", {

    _create: function ()
    {
        var that = this;

        this.element.find("button.form-set-value").click(function (e)
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

/**
 * etapeTauxRemu
 */
$.widget("ose.etapeTauxRemu", {

    _create: function ()
    {
        var that = this;

        this.element.find("button.form-set-value").click(function (e)
        {
            console.log("test");

            var value = that.getElementEtapeSelect('tauxRemu').val();
            that.setFormValues('tauxRemu', value);
            e.stopPropagation();
        });
    },

    setFormValues: function (type, value)
    {
        this.getElementElementSelects(type).each(function ()
        {
            var canSetValue = value == "" || $(this).find("option[value=" + value + "]").length > 0;
            if (canSetValue) {
                $(this).selectpicker('val', value);
            }
        });
    },

    getElementEtapeSelect: function (type)
    {
        return this.element.find('select[name="' + type + '"]');
    },

    getElementElementSelects: function (type)
    {
        return this.element.find('select[name$="\\[' + type + '\\]"]');
    }

});


/**
 * etapeTauxMixite
 */
$.widget("ose.etapeTauxMixite", {

    _create: function ()
    {
        var that = this;

        this.element.find("button.form-set-value").click(function (e)
        {
            var typeHeuresCode = $(this).data('code');
            var value = that.getElementEtapeElement(typeHeuresCode).val();
            that.setFormValues(typeHeuresCode, value);
            e.stopPropagation();
        });
    },

    setFormValues: function (typeHeuresCode, value)
    {
        this.getElementElementElements(typeHeuresCode).each(function ()
        {
            $(this).val(value);
        });
    },

    getElementEtapeElement: function (typeHeuresCode)
    {
        return this.element.find('input[name="' + typeHeuresCode + '"]');
    },

    getElementElementElements: function (typeHeuresCode)
    {
        return this.element.find('input[name$="\\[' + typeHeuresCode + '\\]"]');
    }

});




/**
 * etapeModulateurs
 */
$.widget("ose.etapeModulateurs", {

    _create: function ()
    {
        var that = this;

        this.getElementElementSelects()
        this.element.find("button.form-set-value").click(function ()
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





/**
 * etapeSaisie
 */
$.widget("ose.etapeSaisie", {

    updateQueryStringParameter: function (uri, key, value)
    {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return uri + separator + key + "=" + value;
        }
    },

    onAjouter: function (event)
    {
        window.location = this.updateQueryStringParameter(window.location.href, "etape", this.getId());
    },

    onModifier: function (event)
    {
        window.location = this.updateQueryStringParameter(window.location.href, "etape", this.getId());
    },

    getId: function ()
    {
        return this.element.find('input[name=id]').val();
    }
});

$(function ()
{
    $("body").on("etape-ajouter", function (event, data)
    {
        event.div.modal('hide'); // ferme la fenêtre modale
        event.div.find('.etape-saisie').etapeSaisie();
        event.div.find('.etape-saisie').etapeSaisie('onAjouter', event);
    });
    $("body").on("etape-modifier", function (event, data)
    {
        event.div.modal('hide'); // ferme la fenêtre modale
        event.div.find('.etape-saisie').etapeSaisie();
        event.div.find('.etape-saisie').etapeSaisie('onModifier', event);
    });
    $("body").on("etape-supprimer", function (event, data)
    {
        event.div.modal('hide'); // ferme la fenêtre modale
        window.location.reload();
    });
});





/**
 * elementPedagogiqueSaisie
 */
$.widget("ose.elementPedagogiqueSaisie", {

    onAjouter: function (event)
    {
        window.location.reload();
    },

    onModifier: function (event)
    {
        window.location.reload();
    },

    onSynchronisation: function (event)
    {
        window.location.reload();
    },

});

$(function ()
{
    $("body").on("element-pedagogique-ajouter", function (event, data)
    {
        event.div.modal('hide'); // ferme la fenêtre modale
        event.div.find('.element-pedagogique-saisie').elementPedagogiqueSaisie();
        event.div.find('.element-pedagogique-saisie').elementPedagogiqueSaisie('onAjouter', event);
    });
    $("body").on("element-pedagogique-modifier", function (event, data)
    {
        event.div.modal('hide'); // ferme la fenêtre modale
        event.div.find('.element-pedagogique-saisie').elementPedagogiqueSaisie();
        event.div.find('.element-pedagogique-saisie').elementPedagogiqueSaisie('onModifier', event);
    });
    $("body").on("element-pedagogique-synchronisation", function (event, data)
    {
        event.div.modal('hide'); // ferme la fenêtre modale
        event.div.find('.element-pedagogique-synchronisation').elementPedagogiqueSaisie();
        event.div.find('.element-pedagogique-synchronisation').elementPedagogiqueSaisie('onSynchronisation', event);
    });
    $("body").on("element-pedagogique-supprimer", function (event, data)
    {
        event.div.modal('hide'); // ferme la fenêtre modale
        window.location.reload();
    });
});