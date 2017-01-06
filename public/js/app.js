$(function ()
{
    WidgetInitializer.add('selectpicker', 'selectpicker', function(){
        WidgetInitializer.includeJs(Url('vendor/bootstrap-select-1.9.4/dist/js/bootstrap-select.min.js'));
        WidgetInitializer.includeCss(Url('vendor/bootstrap-select-1.9.4/dist/css/bootstrap-select.min.css'));
    });

    WidgetInitializer.add('intervenant-recherche', 'intervenantRecherche', function(){
        WidgetInitializer.includeJs(Url('intervenant-recherche/widget.js'));
        WidgetInitializer.includeCss(Url('intervenant-recherche/widget.css'));
    });

    WidgetInitializer.add('jstree', 'jstree', function ()
    {
        WidgetInitializer.includeJs(Url('vendor/vakata-jstree-3.3.3/dist/jstree.min.js'));
        WidgetInitializer.includeCss(Url('vendor/vakata-jstree-3.3.3/dist/themes/default/style.min.css'));
    });

    WidgetInitializer.add('table-sort', 'tableSort', function ()
    {
        WidgetInitializer.includeJs(Url('vendor/DataTables-1.10.12/media/js/jquery.dataTables.min.js'));
        WidgetInitializer.includeJs(Url('vendor/DataTables-1.10.12/media/js/dataTables.bootstrap.min.js'));
        WidgetInitializer.includeCss(Url('vendor/DataTables-1.10.12/media/css/dataTables.bootstrap.min.css'));

        WidgetInitializer.includeJs(Url('table-sort/widget.js'));
    });

    /* Services */
    WidgetInitializer.add('service-liste', 'serviceListe', function(){
        WidgetInitializer.includeJs(Url('js/service.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });
    WidgetInitializer.add('service-form', 'serviceForm', function(){
        WidgetInitializer.includeJs(Url('js/service.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });
    WidgetInitializer.add('service-filtres', 'serviceFiltres', function(){
        WidgetInitializer.includeJs(Url('js/service.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });

    /* Service référentiel */
    WidgetInitializer.add('service-referentiel-liste', 'serviceReferentielListe', function(){
        WidgetInitializer.includeJs(Url('js/service-referentiel.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });
    WidgetInitializer.add('service-referentiel-form', 'serviceReferentielForm', function(){
        WidgetInitializer.includeJs(Url('js/service-referentiel.js'));
        WidgetInitializer.includeCss(Url('css/service.css'));
    });

    /* Indicateurs */
    WidgetInitializer.add('indicateur', 'indicateur', function(){
        WidgetInitializer.includeJs(Url('js/indicateur.js'));
        WidgetInitializer.includeCss(Url('css/indicateur.css'));
    });

    /* Pièces jointes */
    WidgetInitializer.add('piece-jointe', 'pieceJointe', function(){
        WidgetInitializer.includeJs(Url('js/piece_jointe.js'));
        WidgetInitializer.includeCss(Url('css/piece_jointe.css'));
    });

    /* Offre de formation */
    WidgetInitializer.add('element-pedagogique-recherche', 'elementPedagogiqueRecherche', function(){
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-centre-cout', 'etapeCentreCout', function(){
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-modulateurs', 'etapeModulateurs', function(){
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-saisie', 'etapeSaisie', function(){
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });
    WidgetInitializer.add('element-pedagogique-saisie', 'elementPedagogiqueSaisie', function(){
        WidgetInitializer.includeJs(Url('js/offre-formation.js'));
    });

    /* Droits */
    WidgetInitializer.add('droits-tbl', 'droitsTbl', function(){
        WidgetInitializer.includeJs(Url('js/droits.js'));
        WidgetInitializer.includeCss(Url('css/droits.css'));
    });
    WidgetInitializer.add('affectation-form', 'affectationForm', function(){
        WidgetInitializer.includeJs(Url('js/droits.js'));
        WidgetInitializer.includeCss(Url('css/droits.css'));
    });


    // installation de tooltip Bootstrap sur les icônes d'information (i)
    $(".info-icon").tooltip();

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
            var sn = '<span class="number number-' + ( (heures < 0) ? 'negatif' : 'positif' ) + '">';
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
                window.location.reload();
            }
        );
    }
};

