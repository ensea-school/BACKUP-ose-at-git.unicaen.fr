
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
    unicaenVue.flashMessenger.toast(message, severity);
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

    formCheckSubmit(formElement)
    {
        const btnId = 'UtilFormCheckSubmitButtonHidden';
        let btn = formElement.querySelector('#'+btnId);
        if (!btn){
            btn = document.createElement("button");
            btn.type = 'submit';
            btn.id = btnId;
            btn.style='display:none';
            formElement.appendChild(btn);
        }
        btn.click();
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
        },

        indexById: function (tab, id)
        {
            for (var key in tab) {
                if (tab[key].id && tab[key].id === id) {
                    return key;
                }
            }
            return null;
        }

    },



    changementAnnee: function (annee)
    {
        $.get(
            unicaenVue.url('changement-annee/:annee', {annee: annee}),
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
    floatToString: function (value, maximumFractionDigits)
    {
        if (undefined == maximumFractionDigits){
            maximumFractionDigits = 2;
        }

        var test = Math.round(value * 1000000) / 1000000;
        if (undefined !== this.fractions[test]) {
            return this.fractions[test];
        }
        var locale = 'fr';
        var options = {minimumFractionDigits: 0, maximumFractionDigits: maximumFractionDigits, useGrouping: false};
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



    dateToString: function (date)
    {
        if (date === undefined) {
            return undefined;
        }
        if (typeof date === 'string'){
            date = new Date(date);
        }

        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        const dateString = `${day}/${month}/${year}`;

        return dateString;
    },


    FORMAT_DATE: 0,
    FORMAT_DATETIME: 1,
    FORMAT_TIME: 2,



    nl2br: function (str, is_xhtml)
    {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

};