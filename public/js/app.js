$(function ()
{
    WidgetInitializer.add('selectpicker', 'selectpicker', function () {
        WidgetInitializer.includeJs(Url('vendor/bootstrap-select-1.14.0/js/bootstrap-select.min.js'));
        WidgetInitializer.includeCss(Url('vendor/bootstrap-select-1.14.0/css/bootstrap-select.min.css'));
        $('.selectpicker').data('liveSearchNormalize', true); // insensible aux accents
    });

    WidgetInitializer.add('intervenant-recherche', 'intervenantRecherche', function () {
        WidgetInitializer.includeJs(Url('intervenant-recherche/widget.js'));
        WidgetInitializer.includeCss(Url('intervenant-recherche/widget.css'));
    });

    WidgetInitializer.add('jstree', 'jstree', function ()
    {
        WidgetInitializer.includeJs(Url('vendor/vakata-jstree-3.3.8/dist/jstree.min.js'));
        WidgetInitializer.includeCss(Url('vendor/vakata-jstree-3.3.8/dist/themes/default/style.min.css'));
    });

    WidgetInitializer.add('table-sort', 'tableSort', function ()
    {
        WidgetInitializer.includeJs(Url('vendor/DataTables-1.10.12/media/js/jquery.dataTables.min.js'));
        WidgetInitializer.includeJs(Url('vendor/DataTables-1.10.12/media/js/dataTables.bootstrap.min.js'));
        WidgetInitializer.includeCss(Url('vendor/DataTables-1.10.12/media/css/dataTables.bootstrap.min.css'));

        WidgetInitializer.includeJs(Url('table-sort/widget.js'));

        (function () {

            function removeAccents(data)
            {
                if (data.normalize) {
                    // Use I18n API if avaiable to split characters and accents, then remove
                    // the accents wholesale. Note that we use the original data as well as
                    // the new to allow for searching of either form.
                    return data + ' ' + data
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '');
                }

                return data;
            }

            var searchType = jQuery.fn.DataTable.ext.type.search;

            searchType.string = function (data) {
                return !data ?
                    '' :
                    typeof data === 'string' ?
                        removeAccents(data) :
                        data;
            };

            searchType.html = function (data) {
                return !data ?
                    '' :
                    typeof data === 'string' ?
                        removeAccents(data.replace(/<.*?>/g, '')) :
                        data;
            };

        }());
    });

    /* Services */
    WidgetInitializer.add('enseignements', 'enseignements', function () {
        WidgetInitializer.includeJs(Url('js/service.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });
    WidgetInitializer.add('service-form', 'serviceForm', function () {
        WidgetInitializer.includeJs(Url('js/service.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });
    WidgetInitializer.add('service-filtres', 'serviceFiltres', function () {
        WidgetInitializer.includeJs(Url('js/service.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });

    /* Service référentiel */
    WidgetInitializer.add('referentiels', 'referentiels', function () {
        WidgetInitializer.includeJs(Url('js/service-referentiel.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });
    WidgetInitializer.add('service-referentiel-form', 'serviceReferentielForm', function () {
        WidgetInitializer.includeJs(Url('js/service-referentiel.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });


    /* Pièces jointes */
    //  WidgetInitializer.add('piece-jointe', 'pieceJointe', function(){
    //      WidgetInitializer.includeJs(Url('js/piece_jointe.js'));
    //      WidgetInitializer.includeCss(Url('css/piece_jointe.css'));
    //  });

    /* Offre de formation */
    WidgetInitializer.add('element-pedagogique-recherche', 'elementPedagogiqueRecherche', function () {
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-centre-cout', 'etapeCentreCout', function () {
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-taux-mixite', 'etapeTauxMixite', function () {
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-modulateurs', 'etapeModulateurs', function () {
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-saisie', 'etapeSaisie', function () {
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });
    WidgetInitializer.add('element-pedagogique-saisie', 'elementPedagogiqueSaisie', function () {
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });

    /* Charges d'enseignement */
    WidgetInitializer.add('chargens', 'chargens', function () {
        WidgetInitializer.includeJs(Url('vendor/go.js'));
        WidgetInitializer.includeJs(Url('js/chargens.js'));
        WidgetInitializer.includeCss(Url('css/chargens.css'));
    });
    WidgetInitializer.add('chargens-filtre', 'chargensFiltre', function () {
        WidgetInitializer.includeJs(Url('js/chargens.js'));
        WidgetInitializer.includeCss(Url('css/chargens.css'));
    });

    /* Droits */
    WidgetInitializer.add('droits-tbl', 'droitsTbl', function () {
        WidgetInitializer.includeJs(Url('js/droits.js'));
        WidgetInitializer.includeCss(Url('css/droits.css'));
    });
    WidgetInitializer.add('affectation-form', 'affectationForm', function () {
        WidgetInitializer.includeJs(Url('js/droits.js'));
        WidgetInitializer.includeCss(Url('css/droits.css'));
    });

    /* DateTime Picker */
    WidgetInitializer.add('bootstrap-datetimepicker', 'bootstrapDatetimepicker', function () {
        WidgetInitializer.includeJs(Url('vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'));
        WidgetInitializer.includeCss(Url('vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'));
    });

    // installation de tooltip Bootstrap sur les icônes d'information (i)
    $(".info-icon").tooltip();

});

$(document).ajaxError(function (event, request, settings) {
    if (!(typeof settings.error === 'function')) {
        alertFlash(request.responseText, 'error', 3000);
    }

});

function Url(route, data)
{
    var getArgs = data ? $.param(data) : null;
    return Url.getBase() + route + (getArgs ? '?' + getArgs : '');
}

Url.getBase = function ()
{
    return $('body').data('base-url');
}

Util = {
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
            Url('changement-annee/' + annee),
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
        var ul = select.prev().find('ul');
        var shown = 0;
        var lastShown = null;

        select.find('option').each(function ()
        {
            var li = ul.find("li[data-original-index='" + this.index + "']");

            if (values === 'all' || Util.inArray(this.value, values) || this.value == '') {
                li.show();
                shown++;
                lastShown = this.value;
            } else {
                if (select.val() == this.value) {
                    select.selectpicker('val', '');
                }
                li.hide();
            }

        });

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