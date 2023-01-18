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
        WidgetInitializer.includeJs(Util.url('table-sort/widget.js'));

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


    $(document).ajaxSend((event, xhr, settings) => {
        if (settings.submitter) {
            let msg = settings.msg ? settings.msg : 'Action en cours';
            if (settings.popover === undefined) {
                console.log(settings.submitter);
                settings.popover = new bootstrap.Popover(settings.submitter, {
                    content: "<div class=\"spinner-border text-primary\" role=\"status\">\n" +
                        "  <span class=\"visually-hidden\">Loading...</span>\n" +
                        "</div> " + msg,
                    html: true
                });
                settings.popover.show();
            }
        }
    });

    $(document).ajaxSuccess((event, xhr, settings) => {
        // Si une popover est lancée pour informer sur le'état de la requête
        if (settings.popover) {
            var popover = settings.popover;

            // Si la réponse est en JSON, QUE C'EST ok et qu'il y a un truc à afficher
            if (xhr.responseJSON && xhr.responseJSON.error) {
                popover._config.content = xhr.responseJSON.error;
                popover.setContent();
                let id = $(settings.submitter).attr('aria-describedby');
                $('#' + id).find('.popover-body').addClass('alert alert-danger');
            } else if (xhr.responseJSON && xhr.responseJSON.msg || settings.successMsg) {
                popover._config.content = xhr.responseJSON.msg ? xhr.responseJSON.msg : settings.successMsg;
                popover.setContent();
                let id = $(settings.submitter).attr('aria-describedby');
                $('#' + id).find('.popover-body').addClass('alert alert-success');
                setTimeout(() => {
                    popover.hide();
                }, 2000)
            } else {
                // la popover est masquée si tout est fini
                popover.hide();
            }
        }

        // correction d'un bug de bootstrap-select à la MAJ AJAX d'une page
        $('.selectpicker').selectpicker('render');
    });

    $(document).ajaxError((event, xhr, settings) => {
        if (settings.popover) {
            var popover = settings.popover;
            popover._config.content = xhr.responseText;
            popover.setContent();
        } else {
            if (!(typeof settings.error === 'function')) {
                alertFlash(xhr.responseText, 'error', 3000);
            }
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