$(function ()
{
    WidgetInitializer.add('intervenant-recherche', 'intervenantRecherche', function () {
        WidgetInitializer.includeJs(Url('js/intervenant-recherche.js'));
    });

    WidgetInitializer.add('jstree', 'jstree', function ()
    {
        WidgetInitializer.includeJs(Url('vendor/vakata-jstree-3.3.8/dist/jstree.min.js'));
        WidgetInitializer.includeCss(Url('vendor/vakata-jstree-3.3.8/dist/themes/default/style.min.css'));
    });

    WidgetInitializer.add('table-sort', 'tableSort', function ()
    {
        WidgetInitializer.includeJs(Url('vendor/DataTables-1.12.1/js/jquery.dataTables.min.js'));
        WidgetInitializer.includeJs(Url('vendor/DataTables-1.12.1/js/dataTables.bootstrap5.min.js'));
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
    WidgetInitializer.add('enseignements', 'enseignements');
    WidgetInitializer.add('service-form', 'serviceForm');
    WidgetInitializer.add('service-filtres', 'serviceFiltres');

    /* Service référentiel */
    WidgetInitializer.add('referentiels', 'referentiels', function () {
        WidgetInitializer.includeJs(Url('js/service-referentiel.js'));
    });
    WidgetInitializer.add('service-referentiel-form', 'serviceReferentielForm', function () {
        WidgetInitializer.includeJs(Url('js/service-referentiel.js'));
    });


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
        //   WidgetInitializer.includeJs(Url('js/chargens.js'));
    });
    WidgetInitializer.add('chargens-filtre', 'chargensFiltre', function () {
        //     WidgetInitializer.includeJs(Url('js/chargens.js'));
    });

    /* Droits */
    WidgetInitializer.add('droits-tbl', 'droitsTbl', function () {
        WidgetInitializer.includeJs(Url('js/droits.js'));
    });
    WidgetInitializer.add('affectation-form', 'affectationForm', function () {
        WidgetInitializer.includeJs(Url('js/droits.js'));
    });

    // installation de tooltip Bootstrap sur les icônes d'information (i)
    $(".info-icon").tooltip();

    // Bootstrap Select insensible aux accents
    $('.selectpicker').data('liveSearchNormalize', true);
    $('.selectpicker').data('size', 'auto');
});

$(document).ajaxSuccess(function () {
    // correction d'un bug de bootstrap-select à la MAJ AJAX d'une page
    $('.selectpicker').selectpicker('render');

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

    userProfileStructureChange: function (select)
    {
        var roleInput = $(select).parent().find('input.user-profile-select-input');
        if (!roleInput.attr("checked")) {
            roleInput.attr("checked", "checked");
        }
        var event = new Event('change', {bubbles: true});
        roleInput[0].dispatchEvent(event);
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