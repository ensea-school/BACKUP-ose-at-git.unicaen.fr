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
            if (confirm("Votre session a expiré, vous devez vous reconnecter.\n\nCliquez sur OK pour être redirigé(e) vers la page de connexion...")) {
                var pne = window.location.pathname.split('/');
                var url = "/" + (pne[0] ? pne[0] : pne[1]) + "/auth/connexion?redirect=" + $(location).attr('href');
                $(location).attr('href', url);
            }
        }
    });

    /**
     * Installation d'un lien permettant de remonter en haut de la page.
     * Ce lien apparaît lorsque c'est nécessaire.
     */
    if ($(window).scrollTop() > 100) {
        $('.scrollup').fadeIn();
    }
    $(window).scroll(function ()
    {
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });
    $('.scrollup').click(function ()
    {
        $("html, body").animate({scrollTop: 0}, 300);
        return false;
    });

    AjaxModalListener.install();

    /* Utilisation du WidgetInitializer et de l'intranavigator */
    WidgetInitializer.install();
    IntraNavigator.install();
});



/**
 * Système d'initialisation automatique de widgets
 *
 */
WidgetInitializer = {

    /**
     * Liste des widgets déclarés (format [className => widgetName])
     * className = Nom de la classe CSS qui déclenche l'association
     * widgetName = Nom du widget (sans le namespace)
     */
    widgets: {},

    use: function (className)
    {
        if (!this.widgets[className]) {
            console.log('ATTENTION : Widget ' + className + ' non déclaré!!');
            return;
        }

        var widgetName = this.widgets[className].widgetName;
        var onInitialize = this.widgets[className].onInitialize;
        var widgets = $('.' + className);

        if (widgets.length > 0) {
            if (undefined != onInitialize && !WidgetInitializer.widgets[className].initialized) {
                onInitialize();
                WidgetInitializer.widgets[className].initialized = true;
            }
            if (widgetName) {
                widgets.each(function ()
                {
                    $(this)[widgetName]($(this).data('widget'));
                });
            }
        }
    },

    /**
     * Ajoute un nouveau Widget à l'initializer
     *
     * @param string className
     * @param string widgetName
     */
    add: function (className, widgetName, onInitialize)
    {
        if (typeof widgetName === "object") {
            $.widget("wi." + className, widgetName);
            widgetName = className;
        }

        WidgetInitializer.widgets[className] = {
            widgetName: widgetName,
            onInitialize: onInitialize,
            initialized: false
        };
        this.use(className);
    },

    /**
     * Lance automatiquement l'association de tous les widgets déclarés avec les éléments HTMl de classe correspondante
     */
    run: function ()
    {
        for (className in this.widgets) {
            this.use(className);
        }
    },

    /**
     * Installe le WidgetInitializer pour qu'il se lance au chargement de la page ET après chaque requête AJAX
     */
    install: function ()
    {
        var that = this;

        this.run();
        $(document).ajaxSuccess(function ()
        {
            that.run();
        });
    },

    includeCss: function (fileName)
    {
        if (!$("link[href='" + fileName + "']").length) {
            var link = '<link rel="stylesheet" type="text/css" href="' + fileName + '">';
            $('head').append(link);
        }
    },

    includeJs: function (fileName)
    {
        if (!$("script[src='" + fileName + "']").length) {
            var script = '<script type="text/javascript" src="' + fileName + '">' + '</script>';
            $('body').append(script);
        }
    }
};



IntraNavigator = {
    getElementToRefresh: function (element)
    {
        return $($(element).parents('.intranavigator').get(0));
    },

    refreshElement: function (element, data, isSubmit)
    {
        element.html(data);
        $("body").trigger('intranavigator-refresh', {element: element, isSubmit: isSubmit});
    },

    embeds: function (element)
    {
        return $(element).parents('.intranavigator').length > 0;
    },

    add: function (element)
    {
        if (!$(element).hasClass('intranavigator')) {
            $(element).addClass('intranavigator');
            //IntraNavigator.run();
        }
    },

    waiting: function (element, message)
    {
        if ($(element).find('.intramessage').length == 0) {
            var msg = message ? message : 'Chargement';
            msg += ' <span class="loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
            msg = '<div class="alert alert-success intramessage" role="alert">' + msg + '</div>';
            $(element).append(msg);
        } else {
            $(element).find('.intramessage').show();
        }
    },

    endWaiting: function ()
    {
        $('.intramessage').hide();
    },

    formSubmitListener: function (e)
    {
        var form = $(e.target);
        var postData = form.serializeArray(); // paramètre "modal" indispensable
        var url = form.attr('action');
        var elementToRefresh = IntraNavigator.getElementToRefresh(form);

        if (elementToRefresh) {
            // requête AJAX de soumission du formulaire
            IntraNavigator.waiting(elementToRefresh, 'Veuillez patienter s\'il vous plaît...');
            $.post(url, postData, $.proxy(function (data)
            {
                IntraNavigator.refreshElement(elementToRefresh, data, true);
            }, this));
        }
        e.preventDefault();
    },

    innerAnchorClickListener: function (e)
    {
        var anchor = $(e.currentTarget);
        var url = anchor.attr('href');
        var elementToRefresh = IntraNavigator.getElementToRefresh(anchor);

        if (elementToRefresh && url && url !== "#") {
            // requête AJAX pour obtenir le nouveau contenu de la fenêtre modale
            IntraNavigator.waiting(elementToRefresh, 'Chargement');
            $.get(url, {}, $.proxy(function (data)
            {
                IntraNavigator.refreshElement(elementToRefresh, data, true);
            }, this));
        }

        e.preventDefault();
    },

    /*btnPrimaryClickListener: function (e)
     {
     var form = IntraNavigator.getElementToRefresh(e.target).find('form');
     if (form.length) {
     form.submit();
     e.preventDefault();
     }
     },*/

    /**
     * Lance automatiquement l'association de tous les widgets déclarés avec les éléments HTMl de classe correspondante
     */
    run: function ()
    {
        var submitSelector = '.intranavigator form:not(.no-intranavigation)';
        var clickSelector = '.intranavigator a:not(.pop-ajax):not(.ajax-modal):not(.no-intranavigation):not(.no-intranavigation a)';

        /* TODO: trouver une meilleure solution que d'utiliser la classe CSS "no-intranavigation" pour désactiver l'intra-navigation ?*/

        $('body').off("submit", submitSelector, IntraNavigator.formSubmitListener);
        $('body').off("click", clickSelector, IntraNavigator.innerAnchorClickListener);
        //$('body').off("click", ".intranavigator .btn-primary", IntraNavigator.btnPrimaryClickListener);

        $('body').one("submit", submitSelector, IntraNavigator.formSubmitListener);
        $('body').one("click", clickSelector, IntraNavigator.innerAnchorClickListener);

        //$('body').one("click", ".intranavigator .btn-primary", IntraNavigator.btnPrimaryClickListener);
        // Réglage du focus sur le champ de formulaire ayant l'attribut 'autofocus'
        $('.intranavigator [autofocus]').focus();
    },

    /**
     * Installe le WidgetInitializer pour qu'il se lance au chargement de la page ET après chaque requête AJAX
     */
    install: function ()
    {
        var that = this;

        this.run();
        $(document).ajaxSuccess(function ()
        {
            that.run();
            that.endWaiting();
        });
    }
};



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
        highlight(element.val(), li, 'sas-highlight');
        // si l'item ne possède pas d'id, on fait en sorte qu'il ne soit pas sélectionnable
        if (!item.id) {
            li.click(function () { return false; });
        }
        return li;
    };
    return this;
};



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
            $.get(url, {modal: 1}, $.proxy(function (data)
            {
                // remplacement du contenu de la fenêtre modale
                $(".modal-content", modalDialog.modal('show')).html(this.extractNewModalContent(data));

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
    this.eventListener.on("click", this.getSubmitButton().selector, $.proxy(this.btnPrimaryClickListener, this));

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
 * TabAjax
 */
$.widget("unicaen.tabAjax", {

    /**
     * Permet de retourner un onglet, y compris à partir de son ID
     *
     * @param string|a tab
     * @returns {*}
     */
    getTab: function (tab)
    {
        if (typeof tab === 'string') {
            return this.element.find('.nav-tabs a[aria-controls="' + tab + '"]');
        } else {
            return tab; // par défaut on présuppose que le lien "a" a été transmis!!
        }
    },

    getIsLoaded: function (tab)
    {
        tab = this.getTab(tab);
        return tab.data('is-loaded') == '1';
    },

    setIsLoaded: function (tab, isLoaded)
    {
        tab = this.getTab(tab);
        tab.data('is-loaded', isLoaded ? '1' : '0');

        this._trigger('loaded', null, tab);

        return this;
    },

    getForceRefresh: function (tab)
    {
        return this.getTab(tab).data('force-refresh') ? true : false;
    },

    setForceRefresh: function (tab, forceRefresh)
    {
        this.getTab(tab).data('force-refresh', forceRefresh);
        return this;
    },

    select: function (tab)
    {
        var that = this;

        tab = this.getTab(tab);
        if (tab.attr('href')[0] !== '#' && (!this.getIsLoaded(tab) || this.getForceRefresh(tab))) {
            var loadurl = tab.attr('href'),
                tid = tab.attr('data-target');

            that.element.find(".tab-pane" + tid).html("<div class=\"loading\">&nbsp;</div>");
            IntraNavigator.add(that.element.find(".tab-pane" + tid));
            $.get(loadurl, function (data)
            {
                that.element.find(".tab-pane" + tid).html(data);
                that.setIsLoaded(tab, true);
            });
        }
        tab.tab('show');
        this._trigger("change");
        return this;
    },

    getContent: function (tab)
    {
        var that = this;

        tab = this.getTab(tab);
        tid = tab.attr('data-target');
        return that.element.find(".tab-pane" + tid).html();
    },

    getSelected: function ()
    {
        var sel = this.element.find('.tab-pane.active').attr('id');

        return sel;
    },

    _create: function ()
    {
        var that = this;
        this.element.find('.nav-tabs a').on('click', function (e)
        {
            e.preventDefault();
            that.select($(this));
            return false;
        });
        if (!that.getContent(that.getSelected())) {
            that.select(that.getSelected());
        }
    },

});

$(function ()
{
    WidgetInitializer.add('tab-ajax', 'tabAjax');
});




/**
 *
 * @constructor
 */
$.widget("unicaen.popAjax", {

    popDiv: undefined,
    inChange: false,

    options: {
        url: undefined,
        content: undefined,
        confirm: false,
        confirmButton: '<i class="fa-solid fa-check"></i> OK',
        cancelButton: '<i class="fa-solid fa-xmark"></i> Annuler',
        animation: true,
        delay: 200,
        placement: 'auto',
        submitEvent: undefined,
        submitClose: false,
        submitReload: false,
        minWidth: '100px',
        maxWidth: '600px',
        minHeight: '50px',
        maxHeight: 'none',
        loadingTitle: 'Chargement...',
        loadingContent: '<div class="loading"></div>',
        title: undefined,
        autoShow: false
    },



    _create: function ()
    {
        var that = this;


        this.element.click(function ()
        {
            that.showHide();

            return false;
        });

        $('html').click(function (e)
        {
            that.htmlClick(e);
        });

        $("body").on('intranavigator-refresh', function (event, args)
        {
            if (that && that.popDiv && $(args.element).parents(that.popDiv).length > 0) {
                that.afterRefresh(args.isSubmit);
            }
        });

        this.initOptions();
        var attr = this.element.attr('href');
        if (typeof attr !== typeof undefined && attr !== false) {
            this.options.url = this.element.attr('href');
        }

        if (this.options.autoShow) {
            setTimeout(function () { that.show(); }, 100);
        }
    },



    initOptions: function ()
    {
        var optionsKeys = {
            url: 'url',
            content: 'content',
            confirm: 'confirm',
            confirmButton: 'confirm-button',
            cancelButton: 'cancel-button',
            animation: 'animation',
            delay: 'delay',
            placement: 'placement',
            submitEvent: 'submit-event',
            submitClose: 'submit-close',
            submitReload: 'submit-reload',
            minWidth: 'min-width',
            maxWidth: 'max-width',
            minHeight: 'min-height',
            maxHeight: 'max-height',
            loadingTitle: 'loading-title',
            loadingContent: 'loading-content',
            title: 'title',
            autoShow: 'auto-show'
        };

        for (var k in optionsKeys) {
            if (typeof this.element.data(optionsKeys[k]) !== 'undefined') {
                this.options[k] = this.element.data(optionsKeys[k]);
            }
        }
    },



    showHide: function ()
    {
        if (this.shown()) {
            this.hide();
        } else {
            this.show();
        }
        return this;
    },



    htmlClick: function (e)
    {
        if (!this.popDiv) return true;

        var p = this.popDiv[0].getBoundingClientRect();

        var horsZonePop = e.clientX < p.left || e.clientX > p.left + p.width
            || e.clientY < p.top || e.clientY > p.top + p.height;

        var horsElementFils = $(e.target).parents('.popover-content,.ui-autocomplete').length == 0;

        if (horsZonePop) {
            if (horsElementFils) { // il ne faut pas que l'élément soit dans le popover
                this.hide();
            }
        }
    },



    shown: function ()
    {
        return this.popDiv != undefined;
    },



    show: function ()
    {
        this.inChange = true;
        if (this.options.animation) {
            this.makePopDiv().fadeIn(this.options.delay);
        } else {
            this.makePopDiv().show();
        }
        this.inChange = false;
        this.posPop();

        this._trigger('show', null, this);

        return this;
    },



    hide: function ()
    {
        if (this.popDiv) {
            if (this.options.animation) {
                this.popDiv.fadeOut(this.options.delay, function () { $(this).remove(); });
            } else {
                this.popDiv.hide();
                this.popDiv.remove();
            }

            this.popDiv = undefined;
        }

        this._trigger('hide', null, this);

        return this;
    },



    afterRefresh: function (isSubmit)
    {
        this.extractTitle(); // on rafraichit le titre, éventuellement
        if (isSubmit && !this.errorsInContent()) {
            if (this.options.submitEvent) {
                $("body").trigger(this.options.submitEvent, this);
            }
            if (this.options.submitClose) {
                this.hide();
            }
            if (this.options.submitReload) {
                window.location.reload();
            }

            this._trigger('submit', null, this);
        }
        this._trigger('change', null, this);
    },



    errorsInContent: function ()
    {
        var that = this;

        if (!this.popDiv) return false;

        var errs = this.popDiv.find('.popover-content')
            .find('.input-error, .has-error, .has-errors, .alert.alert-danger').length;

        return errs > 0;
    },



    makeConfirmBox: function (content)
    {
        var c = '<form action="' + this.options.url + '" method="post">' + content +
            '<div class="btn-goup" style="text-align:right;padding-top: 10px" role="group">';
        if (this.options.cancelButton) {
            c += '<button type="button" class="btn btn-default pop-ajax-hide">' + this.options.cancelButton + '</button>';
        }
        if (this.options.confirmButton && this.options.cancelButton) {
            c += '&nbsp;';
        }
        if (this.options.confirmButton) {
            c += '<button type="submit" class="btn btn-primary">' + this.options.confirmButton + '</button>';
        }
        c += '</div>' +
            '</form>';

        return c;
    },



    makePopDiv: function ()
    {
        var that = this;

        if (this.options.content !== undefined) {
            var title = this.options.title;
            var content = this.options.content;
            if (this.options.confirm) {
                content = this.makeConfirmBox(content);
            }
        } else {
            var title = this.options.loadingTitle;
            var content = this.options.loadingContent;
        }

        if (undefined == this.popDiv) {
            this.popDiv = $('<div></div>');
            this.popDiv.addClass('popover pop-ajax-div');
            this.popDiv.css({
                'min-width': this.options.minWidth,
                'max-width': this.options.maxWidth,
                'min-height': this.options.minHeight,
                'max-height': this.options.maxHeight,
                'position': 'absolute',
                'left': '-80000px',
                'top': '-80000px'
            });

            var contentDiv = '<div class="arrow"></div>';
            if (title) {
                contentDiv += '<h3 class="popover-title">' + title + '</h3>';
            } else {
                contentDiv += '<h3 class="popover-title" style="display:none"></h3>';
            }
            contentDiv += '<div class="popover-content intranavigator">' + content + '</div>';

            this.popDiv.html(contentDiv);
            this.popDiv.appendTo("body");
            this.popDiv.find('.pop-ajax-hide').click(function () { that.hide();});
            IntraNavigator.run(); // navigateur interne!!

            if (this.options.content !== undefined) {
                this._trigger('change', null, this);
                this.posPop();
            } else {
                $.get(this.options.url)
                    .done(function (res)
                    {
                        if (that.options.confirm) {
                            res = that.makeConfirmBox(res);
                        }
                        that.populate(res);
                    })
                    .fail(function (err)
                    {
                        msg = '<div class="alert alert-danger">Erreur ' + err.status + ' : ' + err.statusText + '</div>';

                        that.populate(msg + "\n" + err.responseText);
                    });
            }

            this.getContent().bind('DOMNodeInserted DOMNodeRemoved', function ()
            {
                that.posPop();
            });
        }

        return this.popDiv;
    },



    populate: function (content)
    {
        var that = this;
        var pc = this.getContent();

        this.inChange = true;

        if (pc) {
            pc.hide();

            pc.html(content);
            pc.find('.pop-ajax-hide').click(function () { that.hide();});
            this.extractTitle();
            this._trigger('change', null, this);

            pc.show();
        }
        this.inChange = false;
        this.posPop();
        this.posPop();
        this.posPop(); // on répête 3 fois car il est un peu dûr d'oreille...
    },



    extractTitle: function ()
    {
        var pc = this.getContent();

        var title = pc.find('h1,.popover-title,.page-header');

        if (title.length > 0) {
            this.popDiv.find('.popover-title').html(title.html()).show();
            title.remove();
        } else if (this.options.title) {
            this.popDiv.find('.popover-title').html(this.options.title).show();
        } else {
            this.popDiv.find('.popover-title').hide();
        }
    },



    posPop: function ()
    {
        if (this.inChange) return;
        if (!this.popDiv) return;

        /* Position de l'élément qui ouvre le popover */
        var aPos = this.element[0].getBoundingClientRect();

        /* Espace d'affichage */
        var doc = {
            left: $(window).scrollLeft(),
            top: $(window).scrollTop(),
            width: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
            height: window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
        };

        /* position du popover */
        var pop = {
            left: 0,
            top: 0,
            width: this.popDiv.width(),
            height: this.popDiv.height()
        };

        var placement = this.options.placement;

        if (placement == 'auto') {
            if (aPos.right + pop.width <= doc.width - 2) placement = 'right';
            if ((aPos.left - pop.width >= 2) && (aPos.left + (aPos.width / 2) < (doc.width / 2))) placement = 'left';
            if (aPos.top - pop.height >= 2) placement = 'top';
            if ((aPos.bottom + pop.height <= doc.height - 2) && (aPos.top + (aPos.height / 2) < (doc.height / 2))) placement = 'bottom';
        }

        this.popDiv.removeClass('bottom');
        this.popDiv.removeClass('top');
        this.popDiv.removeClass('left');
        this.popDiv.removeClass('right');
        this.popDiv.addClass(placement);
        switch (placement) {
            case 'bottom':
                pop.left = aPos.left + (aPos.width / 2) - (pop.width / 2);
                pop.top = aPos.bottom;
                break;
            case 'top':
                pop.left = aPos.left + (aPos.width / 2) - (pop.width / 2);
                pop.top = aPos.top - pop.height;
                break;
            case 'left':
                pop.left = aPos.left - pop.width;
                pop.top = aPos.top + (aPos.height / 2) - (pop.height / 2);
                break;
            case 'right':
                pop.left = aPos.right;
                pop.top = aPos.top + (aPos.height / 2) - (pop.height / 2);
                break;
        }

        if (pop.left + pop.width > doc.width - 2) pop.left = doc.width - 2 - pop.width;
        if (pop.top + pop.height > doc.height - 2) pop.top = doc.height - 2 - pop.height;

        if (pop.left < 2) pop.left = 2;
        if (pop.top < 2) pop.top = 2;

        this.popDiv.css({left: doc.left + pop.left, top: doc.top + pop.top});

        switch (placement) {
            case 'bottom':
            case 'top':
                var l = pop.left > aPos.left ? pop.left : aPos.left;
                var r = (pop.left + pop.width) < (aPos.right) ? (pop.left + pop.width) : aPos.right;

                var pos = ((r - l) / 2) + l - pop.left;
                if (pos < 20) pos = 20;
                if (pos > (pop.width - 20)) pos = pop.width - 20;

                this.popDiv.find('.arrow').css({left: pos});
                break;
            case 'left':
            case 'right':
                var t = pop.top > aPos.top ? pop.top : aPos.top;
                var h = (pop.top + pop.height) < aPos.bottom ? (pop.top + pop.height) : aPos.bottom;

                var pos = ((h - t) / 2) + t - pop.top;
                if (pos < 20) pos = 20;
                if (pos > (pop.height - 20)) pos = pop.height - 20;

                this.popDiv.find('.arrow').css({top: pos});
                break;
        }
        return this;
    },



    getContent: function ()
    {
        if (!this.popDiv) return undefined;
        return this.popDiv.find('.popover-content');
    },



    setContent: function (content)
    {
        this.options.content = content;
        return this;
    }

});

$(function ()
{
    WidgetInitializer.add('pop-ajax', 'popAjax');
});