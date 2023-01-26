$(function ()
{
    WidgetInitializer.add('intervenant-recherche', 'intervenantRecherche', function () {
        WidgetInitializer.includeJs(Util.url('js/intervenant-recherche.js'));
    });

    WidgetInitializer.add('jstree', 'jstree', function ()
    {
        WidgetInitializer.includeJs(Util.url('vendor/vakata-jstree-3.3.8/dist/jstree.min.js'));
        WidgetInitializer.includeCss(Util.url('vendor/vakata-jstree-3.3.8/dist/themes/default/style.min.css'));
    });

    WidgetInitializer.add('table-sort', 'tableSort', function ()
    {
        WidgetInitializer.includeJs(Util.url('vendor/DataTables-1.12.1/js/jquery.dataTables.min.js'));
        WidgetInitializer.includeJs(Util.url('vendor/DataTables-1.12.1/js/dataTables.bootstrap5.min.js'));
        WidgetInitializer.includeJs(Util.url('js/table-sort.js'));

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
        WidgetInitializer.includeJs(Util.url('js/service-referentiel.js'));
    });
    WidgetInitializer.add('service-referentiel-form', 'serviceReferentielForm', function () {
        WidgetInitializer.includeJs(Util.url('js/service-referentiel.js'));
    });


    /* Offre de formation */
    WidgetInitializer.add('element-pedagogique-recherche', 'elementPedagogiqueRecherche', function () {
        WidgetInitializer.includeJs(Util.url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-centre-cout', 'etapeCentreCout', function () {
        WidgetInitializer.includeJs(Util.url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-taux-mixite', 'etapeTauxMixite', function () {
        WidgetInitializer.includeJs(Util.url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-modulateurs', 'etapeModulateurs', function () {
        WidgetInitializer.includeJs(Util.url('js/offre-formation.js'));
    });
    WidgetInitializer.add('etape-saisie', 'etapeSaisie', function () {
        WidgetInitializer.includeJs(Util.url('js/offre-formation.js'));
    });
    WidgetInitializer.add('element-pedagogique-saisie', 'elementPedagogiqueSaisie', function () {
        WidgetInitializer.includeJs(Util.url('js/offre-formation.js'));
    });


    /* Charges d'enseignement */
    WidgetInitializer.add('chargens', 'chargens', function () {
        WidgetInitializer.includeJs(Util.url('vendor/go.js'));
        //   WidgetInitializer.includeJs(Util.url('js/chargens.js'));
    });
    WidgetInitializer.add('chargens-filtre', 'chargensFiltre', function () {
        //     WidgetInitializer.includeJs(Util.url('js/chargens.js'));
    });

    /* Droits */
    WidgetInitializer.add('droits-tbl', 'droitsTbl', function () {
        WidgetInitializer.includeJs(Util.url('js/droits.js'));
    });
    WidgetInitializer.add('affectation-form', 'affectationForm', function () {
        WidgetInitializer.includeJs(Util.url('js/droits.js'));
    });

    // installation de tooltip Bootstrap sur les icônes d'information (i)
    $(".info-icon").tooltip();

    // Bootstrap Select insensible aux accents
    $('.selectpicker').data('liveSearchNormalize', true);
    $('.selectpicker').data('size', 'auto');


    $(document).ajaxSuccess((event, xhr, settings) => {
        if (xhr.responseJSON && xhr.responseJSON.messages && xhr.responseJSON.data) {
            let messages = xhr.responseJSON.messages;
            xhr.responseJSON = xhr.responseJSON.data;

            Util.alerts(messages);
        }

        // correction d'un bug de bootstrap-select à la MAJ AJAX d'une page
        $('.selectpicker').selectpicker('render');
    });

    $(document).ajaxError((event, xhr, settings) => {
        if (!(typeof settings.error === 'function')) {
            Util.alert(xhr.responseText, 'error');
        }
    });

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

});