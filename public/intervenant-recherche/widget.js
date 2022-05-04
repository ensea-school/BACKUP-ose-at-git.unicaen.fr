/**
 *
 * @constructor
 */
$.widget("ose.intervenantRecherche", {

    rechercher: function (critere, stopFunc)
    {
        var that = this;

        if (critere.length > 1) {
            that.getElementLoading().show();
            that.getElementRecherche().refresh({critere: critere}, function (response, status, xhr)
            {
                if (status == "error") {
                    var msg = "Désolé mais une erreur est survenue: ";
                    that.getElementRecherche().html(msg + xhr.status + " " + xhr.statusText + xhr.responseText);
                }
                that.getElementLoading().hide();
                that.getElementCritere().autocomplete();
                stopFunc();
            });
        }
    },

    _create: function ()
    {
        var that = this;

        this.getElementCritere().autocomplete({
            source: function (event, stopFunc)
            {
                that.rechercher(event.term, stopFunc);
            }
        });

        this.getElementCritere().focus();
    },

    getElementCritere: function () { return this.element.find("#critere"); },
    getElementRecherche: function () { return this.element.find('.recherche'); },
    getElementLoading: function () { return this.element.find('#intervenant-recherche-loading'); },
});