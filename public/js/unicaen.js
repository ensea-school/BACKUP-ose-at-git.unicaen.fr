/**
 * unicaen.js
 *
 * Javascript commun à toutes les applis.
 */
$(function ()
{
    /**
     * Détection de réponse "403 Unauthorized" aux requêtes AJAX pour rediriger vers
     * la page de connexion.
     */
    $(document).ajaxComplete(function (event, xhr, settings)
    {
        if (xhr.status === 403) {
            alert("Opération non autorisée ou session expirée.");
            xhr.abort();
        }
    });

    AjaxModalListener.install();

    /* Utilisation du WidgetInitializer et de l'intranavigator */
    WidgetInitializer.install();
    IntraNavigator.install();
});



/**
 * Autocomplete jQuery amélioré :
 * - format de données attendu pour chaque item { id: "", value: "", label: "", extra: "" }
 * - un item non sléctionnable s'affiche lorsqu'il n'y a aucun résultat
 *
 * @param Array options Options de l'autocomplete jQuery +
 *                      {
 *                          elementDomId: "Id DOM de l'élément caché contenant l'id de l'item sélectionné (obligatoire)",
 *                          noResultItemLabel: "Label de l'item affiché lorsque la recherche ne renvoit rien (optionnel)"
 *                      }
 * @returns description self
 */
$.fn.autocompleteUnicaen = function (options)
{
    var defaults = {
        elementDomId: null,
        noResultItemLabel: "Aucun résultat trouvé.",
        autoFocus: true
    };
    var opts = $.extend(defaults, options);
    if (!opts.elementDomId) {
        alert("Id DOM de l'élément invisible non spécifié.");
    }
    var select = function (event, ui)
    {
        // un item sans attribut "id" ne peut pas être sélectionné (c'est le cas de l'item "Aucun résultat")
        if (ui.item.id) {
            $(event.target).val(ui.item.label);
            $('#' + opts.elementDomId).val(ui.item.id);
            $('#' + opts.elementDomId).trigger("change", [ui.item]);
        }
        return false;
    };
    var response = function (event, ui)
    {
        if (!ui.content.length) {
            ui.content.push({label: opts.noResultItemLabel});
        }
    };
    var element = this;
    element.autocomplete($.extend({select: select, response: response}, opts))
        // on doit vider le champ caché lorsque l'utilisateur tape le moindre caractère (touches spéciales du clavier exclues)
        .keypress(function (event)
        {
            if (event.which === 8 || event.which >= 32) { // 8=backspace, 32=space
                var lastVal = $('#' + opts.elementDomId).val();
                $('#' + opts.elementDomId).val(null);
                if (null === lastVal) $('#' + opts.elementDomId).trigger("change");
            }
        })
        // on doit vider le champ caché lorsque l'utilisateur vide l'autocomplete (aucune sélection)
        // (nécessaire pour Chromium par exemple)
        .keyup(function ()
        {
            if (!$(this).val().trim().length) {
                var lastVal = $('#' + opts.elementDomId).val();
                $('#' + opts.elementDomId).val(null);
                $('#' + opts.elementDomId).trigger("change");
                if (null === lastVal) $('#' + opts.elementDomId).trigger("change");
            }
        })
        // ajoute de quoi faire afficher plus d'infos dans la liste de résultat de la recherche
        .data("ui-autocomplete")._renderItem = function (ul, item)
    {
        var template = item.template ? item.template : '<span id=\"{id}\">{label} <span class=\"extra\">{extra}</span></span>';
        var markup = template
            .replace('{id}', item.id ? item.id : '')
            .replace('{label}', item.label ? item.label : '')
            .replace('{extra}', item.extra ? item.extra : '');
        markup = '<a id="autocomplete-item-' + item.id + '">' + markup + "</a>";
        var li = $("<li></li>").data("item.autocomplete", item).append(markup).appendTo(ul);
        // mise en évidence du motif dans chaque résultat de recherche
        element.val().split(' ').filter(v => v).forEach(v => highlight(v, li, 'sas-highlight'));
        // si l'item ne possède pas d'id, on fait en sorte qu'il ne soit pas sélectionnable
        if (!item.id) {
            li.on("click", function () { return false; });
        }
        return li;
    };
    return this;
};

/**
 * Permet de rechercher et mettre en évidence un terme en ajoutant une <span> autour.
 * Utilisé uniquement par autocompleteUnicaen
 *
 * @param string term Terme à mettre en évidence
 * @param object base Conteneur dans lequel rechercher
 * @param string class Classe CSS de la balise <span> entourant le terme trouvé, 'highlight' par défaut
 */
function highlight(term, base, cssClass)
{
    if (!term) {
        return;
    }
    cssClass = cssClass || 'highlight';
    base = base || document.body;
    RegExp.escape = function (text)
    { // Note: if you don't care for (), you can remove it..
        return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
    }
    var re = new RegExp("(" + RegExp.escape(term) + ")", "gi");
    $("*", base).contents().each(function (i, el)
    {
        if (el.nodeType === 3) {
            var data = el.data;
            data = data.replace(re, function (arg, match)
            {
                return '<span class="' + cssClass + '">' + match + '</span>';
            });
            if (data) {
                var wrapper = $("<span>").html(data);
                $(el).before(wrapper.contents()).remove();
            }
        }
    });
}




$.widget("unicaen.formAdvancedMultiCheckbox", {

    height: function (height)
    {
        if (height === undefined) {
            return this.getItemsDiv().css('max-height');
        } else {
            this.getItemsDiv().css('max-height', height);
        }
    },

    overflow: function (overflow)
    {
        if (overflow === undefined) {
            return this.getItemsDiv().css('overflow');
        } else {
            this.getItemsDiv().css('overflow', overflow);
        }
    },

    selectAll: function ()
    {
        this.getItems().prop("checked", true);
    },

    selectNone: function ()
    {
        this.getItems().prop("checked", false);
    },

    _create: function ()
    {
        var that = this;
        this.getSelectAllBtn().on('click', function () { that.selectAll(); });
        this.getSelectNoneBtn().on('click', function () { that.selectNone(); });
    },

    //@formatter:off
    getItemsDiv     : function() { return this.element.find('div#items');           },
    getItems        : function() { return this.element.find("input[type=checkbox]");},
    getSelectAllBtn : function() { return this.element.find("a.btn.select-all");    },
    getSelectNoneBtn: function() { return this.element.find("a.btn.select-none");   }
    //@formatter:on

});

$(function ()
{
    WidgetInitializer.add('form-advanced-multi-checkbox', 'formAdvancedMultiCheckbox');
});




/**
 * Installation d'un mécanisme d'ouverture de fenêtre modale Bootstrap 3 lorsqu'un lien
 * ayant la classe CSS 'modal-action' est cliqué.
 * Et de gestion de la soumission du formulaire éventuel se trouvant dans la fenêtre modale.
 *
 * @param dialogDivId Id DOM éventuel de la div correspondant à la fenêtre modale
 */
function AjaxModalListener(dialogDivId)
{
    this.eventListener = $("body");
    this.modalContainerId = dialogDivId ? dialogDivId : "modal-div-gjksdgfkdjsgffsd";
    this.modalEventName = undefined;

    this.getModalDialog = function ()
    {
        var modal = $("#" + this.modalContainerId);
        if (!modal.length) {
            var modal =
                $('<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" />').append(
                    $('<div class="modal-dialog" />').append(
                        $('<div class="modal-content" />').append(
                            $('<div class="modal-body">Patientez, svp...<div>')
                        )
                    )
                );
            modal.attr('id', this.modalContainerId).appendTo("body").modal({show: false});
        }
        return modal;
    };
    this.extractNewModalContent = function (data)
    {
        var selector = '.modal-header, .modal-body, .modal-footer';
        // seuls les header, body et footer nous intéressent
        var newModalContent = $(data).filter(selector);
        if (!newModalContent.length) {
            newModalContent = $('<div class="modal-body" />');
        }
        // les var_dump, notice, warning, error PHP s'affichent n'importe où, on remet tout ça dans le body
        $(data).filter(':not(' + selector + ')').prependTo(newModalContent.filter(".modal-body"));
        // suppression de l'éventuel titre identique présent dans le body
        if (title = $(".modal-title", newModalContent).html()) {
            $(":header", newModalContent.filter(".modal-body")).filter(function () { return $(this).html() === title; }).remove();
        }
        return newModalContent;
    }
    this.getDialogBody = function ()
    {
        return $("div.modal-body", this.getModalDialog());
    };
    this.getDialogFooter = function ()
    {
        return $("div.modal-footer", this.getModalDialog());
    };
    this.getForm = function ()
    {
        return $("form", this.getDialogBody());
    };
    this.getSubmitButton = function ()
    {
        return $("#" + this.modalContainerId + " .btn-primary");
    };

    /**
     * Fonction lancée à l'ouverture de la fenêtre modale
     */
    this.modalShownListener = function (e)
    {
        // déplacement du bouton submit dans le footer
//        this.getSubmitButton().prependTo(this.getDialogFooter());
        // Réglage du focus sur le champ de formulaire ayant l'attribut 'autofocus'
        $('[autofocus]', e.target).focus();
    };

    /**
     * Interception des clics sur les liens adéquats pour affichage de la fenêtre modale
     */
    this.anchorClickListener = function (e)
    {
        var anchor = $(e.currentTarget);
        var url = anchor.attr('href');
        var modalDialog = this.getModalDialog();

        if (url && url !== "#") {
            // transmet à la DIV le lien cliqué (car fournit l'événement à déclencher à la soumission du formulaire)
            modalDialog.data('a', anchor);
            this.modalEventName = anchor.data('event');

            // requête AJAX pour obtenir le nouveau contenu de la fenêtre modale
            IntraNavigator.loadBegin();
            $.get(url, {modal: 1}, $.proxy(function (data)
            {
                // remplacement du contenu de la fenêtre modale
                $(".modal-content", modalDialog.modal('show')).html(this.extractNewModalContent(data));
                IntraNavigator.loadEnd();

            }, this));
        }

        e.preventDefault();
    };

    /**
     * Interception des clics sur les liens inclus dans les modales pour rafraichir la modale au lieu de la page
     */
    this.innerAnchorClickListener = function (e)
    {
        if (IntraNavigator.embeds(e.currentTarget)) {
            return; // L'IntraNavigator se charge de tout, il n'y a rien à faire
        }

        var anchor = $(e.currentTarget);
        var url = anchor.attr('href');
        var modalDialog = this.getModalDialog();

        if (anchor.attr('target') === '_blank') {
            return;
        }

        if (url && url !== "#") {
            this.modalEventName = anchor.data('event');

            // requête AJAX pour obtenir le nouveau contenu de la fenêtre modale
            $.get(url, {modal: 1}, $.proxy(function (data)
            {
                // remplacement du contenu de la fenêtre modale
                $(".modal-content", modalDialog.modal('show')).html(this.extractNewModalContent(data));

            }, this));
        }

        e.preventDefault();
    };

    this.btnPrimaryClickListener = function (e)
    {
        var form = this.getForm();

        if (IntraNavigator.embeds(form)) {
            return; // L'IntraNavigator se charge de tout, il n'y a rien à faire
        }

        if (form.length) {
            form.submit();
            e.preventDefault();
        }
    };

    this.formSubmitListener = function (e)
    {
        if (IntraNavigator.embeds(e.target)) {
            return; // L'IntraNavigator se charge de tout, il n'y a rien à faire
        }

        var that = this;
        var modalDialog = this.getModalDialog();
        var dialogBody = this.getDialogBody().css('opacity', '0.5');
        var form = $(e.target);
        var postData = new FormData(form[0]);
        postData.append("modal", "1");

        var url = form.attr('action');
        var isRedirect = url.indexOf("redirect=") > -1 || $("input[name=redirect]").val();

        // requête AJAX de soumission du formulaire
        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function (data) {
                // mise à jour du "content" de la fenêtre modale seulement
                $(".modal-content", modalDialog).html(that.extractNewModalContent(data));

                // tente de déterminer si le formulaire éventuel contient des erreurs de validation
                var terminated = !isRedirect && ($(".input-error, .has-error, .has-errors, .alert.alert-danger", modalDialog).length ? false : true);
                if (terminated) {
                    // recherche de l'id de l'événement à déclencher parmi les data du lien cliqué
                    //var modalEventName = modalDialog.data('a').data('event');
                    if (that.modalEventName) {
                        var args = that.getForm().serializeArray();
                        var event = jQuery.Event(that.modalEventName, {div: modalDialog, a: modalDialog.data('a')});
//                        console.log("Triggering '" + event.type + "' event...");
//                        console.log("Event object : ", event);
//                        console.log("Trigger args : ", args);
                        that.eventListener.trigger(event, [args]);
                    }
                }
                dialogBody.css('opacity', '1.0');
            }
        });

        e.preventDefault();
    };
}

/**
 * Instance unique.
 */
AjaxModalListener.singleton = null;
/**
 * Installation du mécanisme d'ouverture de fenêtre modale.
 */
AjaxModalListener.install = function (dialogDivId)
{
    if (null === AjaxModalListener.singleton) {
        AjaxModalListener.singleton = new AjaxModalListener(dialogDivId);
        AjaxModalListener.singleton.start();
    }

    return AjaxModalListener.singleton;
};
/**
 * Désinstallation du mécanisme d'ouverture de fenêtre modale.
 */
AjaxModalListener.uninstall = function ()
{
    if (null !== AjaxModalListener.singleton) {
        AjaxModalListener.singleton.stop();
    }

    return AjaxModalListener.singleton;
};
/**
 * Démarrage du mécanisme d'ouverture de fenêtre modale.
 */
AjaxModalListener.prototype.start = function ()
{
    // interception des clics sur les liens adéquats pour affichage de la fenêtre modale
    this.eventListener.on("click", "a.ajax-modal", $.proxy(this.anchorClickListener, this));

    // interception des clics sur les liens adéquats pour affichage de la fenêtre modale
    this.eventListener.on("click", "#" + this.modalContainerId + " a:not([download])", $.proxy(this.innerAnchorClickListener, this));

    // le formulaire éventuel est soumis lorsque le bouton principal de la fenêtre modale est cliqué
    if (this.getSubmitButton().length) {
        this.eventListener.on("click", this.getSubmitButton().selector, $.proxy(this.btnPrimaryClickListener, this));
    }

    // interception la soumission classique du formulaire pour le faire à la sauce AJAX
    this.eventListener.on("submit", "#" + this.modalContainerId + " form", $.proxy(this.formSubmitListener, this));

    // force le contenu de la fenêtre modale à être "recalculé" à chaque ouverture
    this.eventListener.on('hidden.bs.modal', "#" + this.modalContainerId, function (e)
    {
        $(e.target).removeData('bs.modal');
    });

    this.eventListener.on('shown.bs.modal', "#" + this.modalContainerId, $.proxy(this.modalShownListener, this));

    return this;
};
/**
 * Arrêt du mécanisme d'ouverture de fenêtre modale.
 */
AjaxModalListener.prototype.stop = function ()
{
    this.eventListener
        .off("click", "a.ajax-modal", $.proxy(this.anchorClickListener, this))
        .off("click", this.getSubmitButton().selector, $.proxy(this.btnPrimaryClickListener, this))
        .off("submit", "#" + this.modalContainerId + " form", $.proxy(this.formSubmitListener, this))
        .off('hidden.bs.modal', "#" + this.modalContainerId);

    return this;
};