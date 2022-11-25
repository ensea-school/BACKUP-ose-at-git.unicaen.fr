/**
 *
 * @constructor
 */

$.widget("ose.intervenantRecherche", {

    rechercher: function (critere, stopFunc)
    {
        var that = this;
        var disabled = that.getElementCritere().autocomplete( "option", "disabled" );
        if (critere.length > 1 && !disabled) {
            that.getElementCritere().autocomplete("disable");
            that.getElementRecherche().refresh({critere: critere}, function (response, status, xhr)
            {
                if (status == "error") {
                    var msg = "Désolé mais une erreur est survenue: ";
                    that.getElementRecherche().html(msg + xhr.status + " " + xhr.statusText + xhr.responseText);
                }
                that.getElementCritere().autocomplete("enable");
                stopFunc();
            });
        }else{
            stopFunc();
        }
    },

    _create: function ()
    {
        var that = this;

        this.getElementCritere().autocomplete({
            source: function (event, stopFunc)
            {
                that.rechercher(event.term, stopFunc);
            },
			minLength: 2
        });

        this.getElementCritere().focus();
    },

    getElementCritere: function () { return this.element.find("#critere"); },
    getElementRecherche: function () { return this.element.find('.recherche'); },
});
