
$(function ()
{
    //$(document).ajaxError(function (event, jqxhr, settings, exception)
    //{
    //    if ($('body').hasClass('development')) {
    //        errorDialog.show('Une erreur ' + jqxhr.status + '(' + jqxhr.statusText + ') est survenue', jqxhr.responseText);
    //    }
    //    console.log(jqxhr);
    //});

    // installation de tooltip Bootstrap sur les icônes d'information (i)
    $(".info-icon").tooltip();
    WidgetInitializer.add('selectpicker', 'selectpicker');
});

function errorDialog() {}
errorDialog.show = function (title, text)
{
    if (undefined === errorDialog.sequence) {
        errorDialog.sequence = 1;
    } else {
        errorDialog.sequence += 1;
    }

    $(document.body).append(
        '<div id="error-dialog-' + errorDialog.sequence + '" class="scr-center">'
        + '<div class="alert alert-danger alert-dismissable">'
        + '<button type="button" class="close" onclick="document.getElementById(\'error-dialog-' + errorDialog.sequence + '\').style.display=\'none\';" data-dismiss="alert" aria-hidden="true">&times;</button>'
        + '<h1>' + title + '</h1>' + text
        + '<br /><hr /><button type="button" onclick="document.getElementById(\'error-dialog-' + errorDialog.sequence + '\').style.display=\'none\';" class="btn btn-danger">Fermer</button>'
        + '</div>'
        + '</div>');
}

function Url(route, data)
{
    var getArgs = data ? $.param(data) : null;
    return Url.getBase() + route + (getArgs ? '?' + getArgs : '');
}

Url.getBase = function ()
{
    sc = document.getElementsByTagName("script");
    for (idx = 0; idx < sc.length; idx++) {
        s = sc.item(idx);
        if (s.src && s.src.match(/js\/app\.js(\?.*)/)) {
            return s.src.replace(/()js\/app\.js(\?.*)/, '$1');
        }
    }
}

Util = {
    formattedHeures: function (heures)
    {
        heures = parseFloat(heures);
        var hclass = (heures < 0) ? 'negatif' : 'positif';

        heures = Math.round(heures * 100) / 100;
        var parts = heures.toString().split(".");
        if (undefined === parts[1]) {
            parts[1] = '<span class="heures-dec-00">,00</span>';
        } else {
            parts[1] = ',' + parts[1];
        }
        return '<span class="heures heures-' + hclass + '">' + parts[0] + parts[1] + '</span>';
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

    }
};


function changementAnnee(annee)
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

/**
 *
 * @constructor
 */
$.widget("ose.intervenantRecherche", {

    rechercher: function( critere )
    {
        var that = this;


        if (critere.length > 1) {
            if (that.getElementRecherche().find('.loading').length == 0){
                that.getElementRecherche().prepend('<div class="loading"></div>');
            }
            that.getElementRecherche().refresh({critere: critere}, function( response, status, xhr ){
                if ( status == "error" ) {
                    var msg = "Sorry but there was an error: ";
                    that.getElementRecherche().html( msg + xhr.status + " " + xhr.statusText + xhr.responseText );
                }
            });
        }
    },

    _create: function ()
    {
        var that = this;

        this.getElementCritere().autocomplete({
            source: function( event, ui ) {
                that.rechercher(event.term);
                return {};
            }
        });

        this.getElementCritere().focus();
    },

    getElementCritere: function(){ return this.element.find( "#critere" ); },
    getElementRecherche : function () { return this.element.find('.recherche'); },
});

$(function ()
{
    WidgetInitializer.add('intervenant-recherche', 'intervenantRecherche');
});

