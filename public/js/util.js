/**
 * Définition d'une nouvelle fonction jQuery permettant de sérializer un formulaire
 * ou des éléments de formulaire au format tableau compatible JSON.
 * Même utilisation que "serializeArray()".
 */
(function ($)
{
    /**
     * Rafraichit un élément en fonction d'une url donnée.
     * Se base sur l'attribut data-url de l'élément
     * Si l'attribut data-url n'est pas renseigné alors il ne se passe rien
     *
     * @param array|FormElement|null    data    (json) à transmettre
     * @param function                  onEnd   Fonction de callback à passer, si besoin. S'exécute une fois le rafraichissement terminé
     * @returns Element
     */
    $.fn.refresh = function (data, onEnd)
    {
        var that = $(this);
        var url = this.data('url');
        if (data instanceof jQuery) {
            data = data.serialize();
        }
        if ("" !== url && undefined !== url) {
            that.load(url, data, onEnd);
        }
        return that;
    }

})(jQuery);



/**
 * Ajoute/remplace un paramètre GET à une URL.
 *
 * @param String uri
 * @param String key
 * @param String value
 * @returns String
 */
function updateQueryStringParameter(uri, key, value)
{
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}

/**
 * Affiche une alerte temporaire.
 *
 * @param string message
 * @param string severity 'info', 'success', 'warning' ou 'error'
 * @param int duration Durée d'affichage de l'alerte en ms
 * @returns void
 */
function alertFlash(message, severity, duration)
{
    var alertClasses = {
        info: 'info',
        success: 'success',
        warning: 'warning',
        error: 'danger'
    };
    var iconClasses = {
        info: 'info-sign',
        success: 'ok-sign',
        warning: 'warning-sign',
        error: 'exclamation-sign'
    };
    var alertClass = 'alert-' + alertClasses[severity];
    var divId = "alert-div-" + Math.floor((Math.random() * 100000) + 1);

    var alertDiv = $(
        '<div id="' + divId + '" class="alert fade in navbar-fixed-bottom" role="alert" style="display: none;">' +
        '    <div class="container">' +
        '        <p class="text-center"><span class="icon glyphicon"></span> <span class="message"></span></p>' +
        '    </div>' +
        '</div>'
    ).appendTo("body");

    alertDiv.addClass(alertClass);
    $("p .message", alertDiv).html(message);
    $("p .icon", alertDiv).addClass('glyphicon-' + iconClasses[severity]);

    alertDiv.slideToggle(500, function ()
    {
        window.setTimeout(function ()
        {
            alertDiv.slideToggle(500, function ()
            {
                $(this).removeClass(alertClass)
            });
        }, duration);
    });
}

/**
 * Lancement périodique d'une requête dans le seul but de rafraîchir la session de l'utilisateur.
 *
 * @param url URL de la requête
 * @param refreshTimeInMs Période en millisecondes
 */
function refreshSession(url, refreshTimeInMs)
{
    window.setInterval(function ()
    {
        $.get(url, {ts: Date.now()}); // le timestamp empêche simplement la mise en cache par le navigateur
    }, refreshTimeInMs);
}



Formatter = {

    stringToFloat: function (value, dec, sep)
    {
        if (dec === undefined) dec = ',';
        if (sep === undefined) sep = ' ';

        if (!value) return 0;
        value = value.replace(dec, '.').replace(/\s/g, "");
        if (isNaN(value)) return 0;
        return parseFloat(value);
    },



    floatToString: function (value, dec, sep, precision, decFixed)
    {
        var moins = '';

        if (dec === undefined) dec = ',';
        if (sep === undefined) sep = ' ';
        if (precision === undefined) precision = 2;
        if (decFixed === undefined) decFixed = false;

        if (value < 0) {
            moins = '-';
            value = value * -1;
        }

        g = Math.floor(value);

        var r = (Math.round((value - g) * 100) / 100).toFixed(precision).substr(-precision);
        if (!decFixed) {
            r = r.replace(/0+$/, ''); // pas de zéro après
            if ('' === r) {
                dec = '';
            }
        }

        var ng = '';

        g = g.toString();

        var ii = 0;
        for (var i = g.length; i >= 0; i--) {
            ng = g.charAt(i) + ng;
            if (ii == 3 && i > 0) {
                ng = sep + ng;
                ii = 0;
            }
            ii++;
        }

        return moins + ng + dec + r;
    }

};



function waitPop(button, msg)
{
    return new WaitPop(button, msg);
}

class WaitPop {
    constructor(button, content)
    {
        if (!content) {
            content = "Enregistrement en cours ..."
        }
        var popover = new bootstrap.Popover(event.submitter, {
            content: content
        });
        popover.show();
    }

    ok(msg)
    {
        // Corps de la méthode
    }

    error(msg)
    {

    }
}