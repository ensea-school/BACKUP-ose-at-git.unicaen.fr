/**
 * Permet de rechercher et mettre en évidence un terme en ajoutant une <span> autour.
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
 * Installe une demande de confirmation sur le lien ou bouton spécifié.
 *
 * @param object target
 * @param string message
 */
function askConfirmation(target, message)
{
    var msg;
    if (message.length) {
        msg = message;
    } else if ($(target).attr('title')) {
        msg = $(target).attr('title');
    } else {
        msg = 'effectuer cette opération';
    }

    msg = "Êtes-vous sûr(e) de vouloir " + msg.substr(0, 1).toLowerCase() + msg.substr(1) + " ?";

    return confirm(msg);
}


/**
 * Recherche parmi les classes CSS d'un élément la DERNIÈRE contenant ou commençant par le motif spécifié.
 *
 * @param element Élément concerné
 * @param prefix Motif que l'on recherche dans les noms de classe de l'élément
 * @param contains <code>true</code> si le nom de la classe doit contenir le motif,
 * <code>false</code> si le nom de la classe doit commencer par le motif
 * @param substring <code>true</code> pour ne retourner que ce qui suit le motif dans la classe trouvée,
 * <code>false</code> pour retourner la classe complète
 * @return string|null La DERNIÈRE classe trouvée (une sous-chaîne si demandé) ou null si aucune classe ne correspond
 */
function getClass(element, prefix, contains, substring)
{
    if (!$(element).attr('class')) {
        return '';
    }
    var classes = $(element).attr('class').split(' ').reverse();
    var classe = jQuery.grep(classes, function (elementOfArray, indexInArray)
    {
        return contains ?
            elementOfArray.indexOf(prefix) > -1 :
            elementOfArray.indexOf(prefix) === 0;
    });
    return classe.length ?
        (substring ? classe[0].substr(classe[0].indexOf(prefix) + prefix.length) : classe[0]) :
        '';
}

var atoggle, menu, content, origwidth;


function setMenuVisible(visible)
{
    if (visible) {
        content.css('width', origwidth + '%');
        menu.children().not(atoggle).show();
    } else {
        menu.children().not(atoggle).hide();
        content.css('width', '97%');
    }
}


/**
 * Remplace les caractères accentués par leur équivalent, en respectant la casse.
 *
 * @param string text
 * @returns string
 */
function replaceAccents(text)
{
    var rules = {
        a: "àáâãäå",
        A: "ÀÁÂ",
        e: "èéêë",
        E: "ÈÉÊË",
        i: "ìíîï",
        I: "ÌÍÎÏ",
        o: "òóôõöø",
        O: "ÒÓÔÕÖØ",
        u: "ùúûü",
        U: "ÙÚÛÜ",
        y: "ÿ",
        c: "ç",
        C: "Ç",
        n: "ñ",
        N: "Ñ"
    };

    function getJSONKey(key)
    {
        for (acc in rules) {
            if (rules[acc].indexOf(key) > -1) {
                return acc;
            }
        }
    }

    regstring = "";
    for (acc in rules) {
        regstring += rules[acc];
    }
    reg = new RegExp("[" + regstring + "]", "g");

    return text.replace(reg, function (t)
    {
        return getJSONKey(t);
    });
}

//console.log(texte = "àAAÀAAÁÂÒÓÔÕÖØòÒÓÔÕ-ÖØòó_ôõöøÈÉÊËèéêëÇçÒÓÔÕÖØòÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ");
//console.log(replaceAccents(texte));

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

/**
 * Installe devant chacun des éléments d'une liste une case à cocher.
 * Installe aussi à la suite du conteneur des éléments (targetElements.last().parent()) 2 liens permettant de
 * "Cocher tout" et de "Décocher tout".
 *
 * @param jQuery targetElements Liste d'objets jQuery pour lesquels on veut ajouter une case à cocher à chacun
 * @param string checkboxName Nom à donner aux cases à cocher, ex: "intervenant" => <input name="intervenant[]" .../>
 * @param boolean initiallyChecked Cocher ou non les cases à cocher par défaut ?
 * @returns La liste des cases à cocher créées (objets jQuery correspondant aux <input type="checkbox">)
 */
function installCheckboxes(targetElements, checkboxName, initiallyChecked)
{
    $.each(targetElements, function (index, element)
    {
        $(element).prepend(
            $("<input/>")
                .attr("type", "checkbox")
                .attr("name", checkboxName + "[]")
                .attr('value', $(element).data("id"))
                .attr("class", "check-" + checkboxName)
                .prop('checked', initiallyChecked ? true : false)
        );
    });

    var cocherTout = $("<a href=# class=checkall-" + checkboxName + ">Cocher tout</a>");
    var decocherTout = $("<a href=# class=uncheckall-" + checkboxName + ">Décocher tout</a>");

    targetElements.last().parent().after(
        $("<p/>").append(
            $("<small/>").append(cocherTout, " / ", decocherTout)
        )
    );

    cocherTout.click(function () { $("input.check-" + checkboxName).prop('checked', true).trigger("change"); });
    decocherTout.click(function () { $("input.check-" + checkboxName).prop('checked', false).trigger("change"); });

    return $("input.check-" + checkboxName);
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


Chrono = {
    deb: undefined,

    top: function ()
    {
        if (this.deb !== undefined) {
            var end = new Date();
            var diff = end - this.deb;

            var minutes = parseInt(diff / 1000 / 60);
            diff = diff - (minutes * 1000 * 60);
            var secondes = parseInt(diff / 1000);
            var millisecondes = diff - (secondes * 1000);

            console.log('TopChrono : ' + minutes + "m " + secondes + "s " + millisecondes + 'ms');
        }

        this.deb = new Date();
    },
}