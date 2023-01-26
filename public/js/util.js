/* Tunning d'Axios pour gérer l'interconnexion avec le serveur avec gestion des alertes */
axios.interceptors.request.use(config => {
    if (config.submitter) {
        let msg = config.msg ? config.msg : 'Action en cours';
        if (config.popover != undefined) {
            config.popover.dispose();
        }
        config.popover = new bootstrap.Popover(config.submitter, {
            content: "<div class=\"spinner-border text-primary\" role=\"status\">\n" +
                "  <span class=\"visually-hidden\">Loading...</span>\n" +
                "</div> " + msg,
            html: true,
            trigger: 'focus'
        });
        config.popover.show();
    }
    return config;
});

axios.interceptors.response.use(response => {
    response.messages = response.data.messages;
    response.data = response.data.data;
    response.hasErrors = response.messages && response.messages.error && response.messages.error.length > 0 ? true : false;

    if (response.config.popover) {
        var popover = response.config.popover;

        let content = '';
        for (ns in response.messages) {
            for (mid in response.messages[ns]) {
                content += '<div class="alert fade show alert-' + (ns == 'error' ? 'danger' : ns) + '" role="alert">' + response.messages[ns][mid] + '</div>';
            }
        }

        // S'il y a un truc à afficher
        if (content) {
            popover._config.content = content;
            popover.setContent();
            setTimeout(() => {
                popover.dispose();
            }, 3000)
        } else {
            // la popover est masquée si tout est fini
            popover.dispose();
        }
    }
    if (response.messages) {
        Util.alerts(response.messages);
    }

    return response;
}, (error) => {
    var text = $("<div>").html(error.response.data);

    text.find('i.fas').hide();

    Util.alert(text.find('.alert').html(), 'error');
});

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';




/**
 * Affiche une alerte temporaire.
 *
 * @param string message
 * @param string severity 'info', 'success', 'warning' ou 'error'
 * @param int duration Durée d'affichage de l'alerte en ms
 * @returns void
 *
 * @deprecated
 */
function alertFlash(message, severity, duration)
{
    Util.alert(message, severity);
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




Util = {

    alerts: function (messages)
    {
        for (s in messages) {
            for (m in messages[s]) {
                Util.alert(messages[s][m], s);
            }
        }
    },



    alert: function (message, severity)
    {
        var alertClasses = {
            info: 'info',
            success: 'success',
            warning: 'warning',
            error: 'danger'
        };
        var iconClasses = {
            info: 'info-circle',
            success: 'check-circle',
            warning: 'exclamation-circle',
            error: 'exclamation-triangle'
        };
        var alertClass = 'alert-' + alertClasses[severity];
        var divId = "alert-div-" + Math.floor((Math.random() * 100000) + 1);

        var alertDiv = $(
            '<div id="' + divId + '" class="alert navbar-fixed-bottom" role="alert" style="display: none">' +
            '    <button type="button" class="btn-close float-md-end" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '    <div class="container">' +
            '        <p class="text-center"><span class="icon fas fa-' + iconClasses[severity] + '"></span> <span class="message"></span></p>' +
            '    </div>' +
            '</div>'
        );



        alertDiv.addClass(alertClass);
        $("p .message", alertDiv).html(message);

        $('body').append(alertDiv);
        alertDiv.slideToggle(500, function ()
        {
            if ('error' != severity) {
                window.setTimeout(function ()
                {
                    alertDiv.slideToggle(500, function ()
                    {
                        $(this).removeClass(alertClass)
                    });
                }, 3000);
            }
        });
    },



    url: function (route, params, query)
    {
        let baseUrl = $('body').data('base-url');

        // Remplacement des paramètres de routes par leurs valeurs
        if (params) {
            for (var p in params) {
                route = route.replace(':' + p, params[p]);
            }
        }

        // traitement de la requête GET
        let getArgs = query ? $.param(query) : null;

        // Construction et retour de l'URL
        return baseUrl + route + (getArgs ? '?' + getArgs : '');
    },



    formattedHeures: function (heures, html)
    {
        heures = parseFloat(heures);

        if (false === html) {
            var snd0 = ',00';
            var sn = '';
            var snf = '';
        } else {
            var snd0 = '<span class="number-dec-00">,00</span>';
            var sn = '<span class="number number-' + ((heures < 0) ? 'negatif' : 'positif') + '">';
            var snf = '</span>';
        }

        heures = Math.round(heures * 100) / 100;
        var parts = heures.toString().split(".");
        if (undefined === parts[1]) {
            parts[1] = snd0;
        } else {
            parts[1] = ',' + parts[1];
        }
        return sn + parts[0] + parts[1] + snf;
    },



    json: {

        count: function (tab)
        {
            var key, result = 0;
            for (key in tab) {
                if (tab.hasOwnProperty(key)) {
                    result++;
                }
            }
            return result;
        },

        first: function (tab)
        {
            for (var key in tab) {
                return tab[key];
            }
        }

    },



    changementAnnee: function (annee)
    {
        $.get(
            Util.url('changement-annee/:annee', {annee: annee}),
            {},
            function ()
            {
                //Préférable pour éviter de re-soumettre des posts lors d'un changement d'année
                window.location = window.location.href;
                //window.location.reload();
            }
        );
    },



    filterSelectPicker: function (select, values)
    {
        var ul = select.parent().find('ul');
        var shown = 0;
        var lastShown = null;

        select.find('option').each(function ()
        {
            if (values === 'all' || Util.inArray(this.value, values) || this.value == '') {
                $(this).show();
                shown++;
                lastShown = this.value;
            } else {
                if (select.val() == this.value) {
                    select.selectpicker('val', '');
                }
                $(this).hide();
            }
        });

        select.selectpicker('destroy');
        select.selectpicker();
        if (1 == shown) {
            select.selectpicker('val', lastShown);
        }
    },



    inArray: function (needle, haystack, strict)
    {
        for (var i in haystack) {
            if (strict) {
                if (haystack[i] === needle) return true;
            } else {
                if (haystack[i] == needle) return true;
            }
        }
        return false;
    },



    fractions: {
        0.333333: '1/3',
        0.166667: '1/6',
        0.142857: '1/7',
        0.111111: '1/9',
        0.666667: '2/3',
        0.285714: '2/7',
        0.222222: '2/9',
        0.428571: '3/7',
        1.333333: '4/3',
        0.571429: '4/7',
        0.444444: '4/9',
        1.666667: '5/3',
        0.833333: '5/6',
        0.714286: '5/7',
        0.555556: '5/9',
        0.857143: '6/7',
        2.333333: '7/3',
        1.166667: '7/6',
        0.777778: '7/9',
        2.666667: '8/3',
        1.142857: '8/7',
        0.888889: '8/9',
        1.285714: '9/7',
    },



    /**
     *
     * @param float value
     *
     * @return string
     */
    floatToString: function (value)
    {
        var test = Math.round(value * 1000000) / 1000000;
        if (undefined !== this.fractions[test]) {
            return this.fractions[test];
        }
        var locale = 'fr';
        var options = {minimumFractionDigits: 0, maximumFractionDigits: 2, useGrouping: false};
        var formatter = new Intl.NumberFormat(locale, options);

        return formatter.format(value);
    },



    stringToFloat: function (value)
    {
        if (null === value || '' === value || undefined === value) return null;

        if (value.indexOf('/') !== -1) {
            value = value.split('/');
            value = Util.stringToFloat(value[0]) / Util.stringToFloat(value[1]);
        } else {
            value = parseFloat(value.replace(',', '.'));
        }

        return value;
    },



    nl2br: function (str, is_xhtml)
    {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

};