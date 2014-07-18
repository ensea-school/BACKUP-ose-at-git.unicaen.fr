/**
 * unicaen.js
 * 
 * Javascript commun à toutes les applis.
 */
$(function() {
    /**
     * Détection de réponse "403 Unauthorized" aux requêtes AJAX pour rediriger vers 
     * la page de connexion.
     */
    $(document).ajaxComplete(function(event, xhr, settings) {
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
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } 
        else {
            $('.scrollup').fadeOut();
        }
    });
    $('.scrollup').click(function() {
        $("html, body").animate({ scrollTop: 0 }, 300);
        return false;
    });

    ajaxPopoverInit();
    AjaxModalListener.install();
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
    this.eventListener    = $("body");
    this.modalContainerId = dialogDivId ? dialogDivId : "modal-div-gjksdgfkdjsgffsd";
    
    this.getModalDialog = function() 
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
            modal.attr('id', this.modalContainerId).appendTo("body").modal({ show: false });
        }
        return modal;
    };
    this.extractNewModalContent = function(data) 
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
            $(":header", newModalContent.filter(".modal-body")).filter(function() { return $(this).html() === title; }).remove();
        }
        return newModalContent;
    }
    this.getDialogBody = function() 
    { 
        return $("div.modal-body", this.getModalDialog()); 
    };
    this.getForm = function() 
    { 
        return $("form", this.getDialogBody()); 
    };
    
    /**
     * Interception des clics sur les liens adéquats pour affichage de la fenêtre modale
     */
    this.anchorClickListener = function(e) 
    {
        var anchor      = $(e.currentTarget);
        var modalDialog = this.getModalDialog();
        
        // transmet à la DIV le lien cliqué (car fournit l'événement à déclencher à la soumission du formulaire)
        modalDialog.data('a', anchor);
        
        // requête AJAX pour obtenir le nouveau contenu de la fenêtre modale
        $.get(anchor.attr('href'), { modal: 1 }, $.proxy(function(data) {
            // remplacement du contenu de la fenêtre modale
            $(".modal-content", modalDialog.modal('show')).html(this.extractNewModalContent(data));
        }, this));
        
        e.preventDefault();
    };

   /**
     * Interception des clics sur les liens inclus dans les modales pour rafraichir la modale au lieu de la page
     */
    this.innerAnchorClickListener = function(e)
    {
        var anchor      = $(e.currentTarget);
        var modalDialog = this.getModalDialog();

        // requête AJAX pour obtenir le nouveau contenu de la fenêtre modale
        $.get(anchor.attr('href'), { modal: 1 }, $.proxy(function(data) {
            // remplacement du contenu de la fenêtre modale
            $(".modal-content", modalDialog.modal('show')).html(this.extractNewModalContent(data));
        }, this));

        e.preventDefault();
        //e.stopPopagation();
    };

    this.btnPrimaryClickListener = function(e) 
    {
        var form = this.getForm();
        if (form.length) {
            form.submit();
            e.preventDefault();
        }
    };
    
    this.formSubmitListener = function(e)
    {
        var modalDialog = this.getModalDialog();
        var dialogBody  = this.getDialogBody().css('opacity', '0.5');
        var form        = $(e.target);
        var postData    = $.merge([{ name: 'modal', value: 1 }], form.serializeArray()); // paramètre "modal" indispensable
        var url         = form.attr('action');
        var isRedirect  = url.indexOf("redirect=") > -1 || $("input[name=redirect]").val();
        
        // requête AJAX de soumission du formulaire
        $.post(url, postData, $.proxy(function(data) {
            // mise à jour du "content" de la fenêtre modale seulement
            $(".modal-content", modalDialog).html(this.extractNewModalContent(data));
            // tente de déterminer si le formulaire éventuel contient des erreurs de validation
            var terminated = !isRedirect && ($(".input-error, .has-error, .has-errors, .alert.alert-danger", modalDialog).length ? false : true);
            if (terminated) {
                // recherche de l'id de l'événement à déclencher parmi les data du lien cliqué
                var modalEventName = modalDialog.data('a').data('event');
                if (modalEventName) {
                    var args  = this.getForm().serializeArray();
                    var event = jQuery.Event(modalEventName, { div: modalDialog, a: modalDialog.data('a') });
//                        console.log("Triggering '" + event.type + "' event...");
//                        console.log("Event object : ", event);
//                        console.log("Trigger args : ", args);
                    this.eventListener.trigger(event, [ args ]);
                }
            }
            dialogBody.css('opacity', '1.0');
        }, this));
        
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
AjaxModalListener.install = function(dialogDivId) 
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
AjaxModalListener.uninstall = function() 
{
    if (null !== AjaxModalListener.singleton) {
        AjaxModalListener.singleton.stop();
    }
    
    return AjaxModalListener.singleton;
};
/**
 * Démarrage du mécanisme d'ouverture de fenêtre modale.
 */
AjaxModalListener.prototype.start = function() 
{
    // interception des clics sur les liens adéquats pour affichage de la fenêtre modale
    this.eventListener.on("click", "a.ajax-modal", $.proxy(this.anchorClickListener, this));

    // interception des clics sur les liens adéquats pour affichage de la fenêtre modale
    this.eventListener.on("click", "#" + this.modalContainerId + " a", $.proxy(this.innerAnchorClickListener, this));

    // le formulaire éventuel est soumis lorsque le bouton principal de la fenêtre modale est cliqué
    this.eventListener.on("click", "#" + this.modalContainerId + " .btn-primary", $.proxy(this.btnPrimaryClickListener, this));

    // interception la soumission classique du formulaire pour le faire à la sauce AJAX
    this.eventListener.on("submit", "#" + this.modalContainerId + " form", $.proxy(this.formSubmitListener, this));

    // force le contenu de la fenêtre modale à être "recalculé" à chaque ouverture
    this.eventListener.on('hidden.bs.modal', "#" + this.modalContainerId, function(e) {
        $(e.target).removeData('bs.modal');
    });
    
    return this;
};
/**
 * Arrêt du mécanisme d'ouverture de fenêtre modale.
 */
AjaxModalListener.prototype.stop = function() 
{
    this.eventListener
            .off("click", "a.ajax-modal", $.proxy(this.anchorClickListener, this))
            .off("click", "#" + this.modalContainerId + " .btn-primary", $.proxy(this.btnPrimaryClickListener, this))
            .off("submit", "#" + this.modalContainerId + " form", $.proxy(this.formSubmitListener, this))
            .off('hidden.bs.modal', "#" + this.modalContainerId);
    
    return this;
};
    




/***************************************************************************************************************************************************
    Popover
/***************************************************************************************************************************************************/

function ajaxPopoverInit(){
    jQuery.fn.popover.Constructor.prototype.replace = function () {
        var $tip = this.tip()

        var placement = typeof this.options.placement == 'function' ?
            this.options.placement.call(this, $tip[0], this.$element[0]) :
            this.options.placement

        var autoToken = /\s?auto?\s?/i
        placement = placement.replace(autoToken, '') || 'top'

        this.options.container ? $tip.appendTo(this.options.container) : $tip.insertAfter(this.$element)

        var pos          = this.getPosition()
        var actualWidth  = $tip[0].offsetWidth
        var actualHeight = $tip[0].offsetHeight

        var $parent = this.$element.parent()

        var orgPlacement = placement
        var docScroll    = document.documentElement.scrollTop || document.body.scrollTop
        var parentWidth  = this.options.container == 'body' ? window.innerWidth  : $parent.outerWidth()
        var parentHeight = this.options.container == 'body' ? window.innerHeight : $parent.outerHeight()
        var parentLeft   = this.options.container == 'body' ? 0 : $parent.offset().left

        placement = placement == 'bottom' && pos.top   + pos.height  + actualHeight - docScroll > parentHeight  ? 'top'    :
                    placement == 'top'    && pos.top   - docScroll   - actualHeight < 0                         ? 'bottom' :
                    placement == 'right'  && pos.right + actualWidth > parentWidth                              ? 'left'   :
                    placement == 'left'   && pos.left  - actualWidth < parentLeft                               ? 'right'  :
                    placement

        $tip
          .removeClass(orgPlacement)
          .addClass(placement)

        var calculatedOffset = this.getCalculatedOffset(placement, pos, actualWidth, actualHeight)

        this.applyPlacement(calculatedOffset, placement)
    }

    $("body").popover({
        selector: 'a.ajax-popover',
        html: true,
        trigger: 'click',
        content: 'Chargement...',
    }).on('shown.bs.popover', ".ajax-popover", function (e) {
        var target = $(e.target);

        var content = $.ajax({
                url: target.attr('href'),
                async: false
            }).responseText;

        div = $("div.popover").last(); // Recherche la dernière division créée, qui est le conteneur du popover
        div.data('a', target); // On lui assigne le lien d'origine
        div.html( content );
        target.popover('replace'); // repositionne le popover en fonction de son redimentionnement
        div.find("form:not(.filter) :input:first").focus(); // donne le focus automatiquement au premier élément de formulaire trouvé qui n'est pas un filtre
    });

    $("body").on("click", "a.ajax-popover", function(){ // Désactive le changement de page lors du click
        return false;
    });

    $("body").on("click", "div.popover .fermer", function(e){ // Tout élément cliqué qui contient la classe .fermer ferme le popover
        div = $(e.target).parents('div.popover');
        div.data('a').popover('hide');
    });

    $("body").on("submit", "div.popover div.popover-content form", function(e) {
        var form = $(e.target);
        var div = $(e.target).parents('div.popover');
        $.post(
            form.attr('action'),
            form.serialize(),
            function(data) {
                div.html(data);
                var terminated = $(".input-error, .has-error, .has-errors, .alert", $(data)).length ? false : true;
                if (terminated) {
                    // recherche de l'id de l'événement à déclencher parmi les data de la DIV
                    var modalEventName = div.data('a').data('event');
                    var args           = form.serializeArray();
                    var event = jQuery.Event(modalEventName, { a: div.data('a'), div: div });
                    $("body").trigger(event, [ args ]);
                }
            }
        );
        e.preventDefault();
    });
}
