/**
 * Transforme une durée (en millisecondes) en une chaîne de caractères 
 * exprimée en années/mois/jours/heures/minutes/descondes/millisecondes.
 * @param milliseconds Nombre de millisecondes
 * @return string ex: "8 jours 22 heures 45 minutes"
 */
function durationInWords(milliseconds) {
    var l = {
        milliseconds: "%d milliseconde",
        seconds: "%d seconde",
        minutes: "%d minute",
        hours: "%d heure",
        days: "%d jour",
        months: "%d mois",
        years: "%d an"
    };

    var ms = milliseconds % 1000;
    var seconds = Math.floor((milliseconds % (1000 * 60) - ms) / 1000);
    var minutes = Math.floor((milliseconds % (1000 * 60 * 60) - seconds) / (1000 * 60));
    var hours = Math.floor((milliseconds % (1000 * 60 * 60 * 24) - minutes) / (1000 * 60 * 60));
    var days = Math.floor((milliseconds % (1000 * 60 * 60 * 24 * 365) - hours) / (1000 * 60 * 60 * 24)) % 30;
    var month = Math.floor((milliseconds % (1000 * 60 * 60 * 24 * 365)) / (1000 * 60 * 60 * 24 * 30));
    var years = Math.floor(milliseconds / (1000 * 60 * 60 * 24 * 365));

    function substitute(stringOrFunction, number) {
      var string = $.isFunction(stringOrFunction) ? stringOrFunction(number, milliseconds) : stringOrFunction;
      return number ? (string.replace(/%d/i, number) + (number > 1 ? "s" : "") + " ") : "";
    }

    return $.trim(substitute(l.years, years) + (substitute(l.months, month)) + (substitute(l.days, days)) + (substitute(l.hours, hours)) + (substitute(l.minutes, minutes)) + (substitute(l.seconds, seconds))  + (substitute(l.milliseconds, ms)));
}

/**
 * Pour chaque label de la classe 'tooltip' (ayant un title) trouvé sur la page, génére un icone (i) ayant le même title
 * et le place à la suite du champ de saisie le plus proche situé après ou avant le label.
 */
function installFormInputTooltips() 
{
    $(function() {
        $("label.tooltip").each(function() {
            if (!this.title) return;
            var span = $('<span style="display:inline-block; vertical-align:top;" class="ui-icon ui-icon-info tooltip" title="' + this.title + '"></span>');
            var input = $(this).find(":input:last"); // cas où le label contient l'input
            if (!input.length) {
                input =  $(this).next(':input'); // cas où le label est avant l'input
            }
            if (!input.length) {
                input =  $(this).prev(':input'); // cas où le label est après l'input
            }
            input.after(span);
            this.title = null;
        });
    });
}

/**
 * Autocomplete jQuery amélioré :
 * - format de données attendu pour chaque item { id: "", value: "", label: "", extra: "" }
 * - un item non sléctionnable s'affiche lorsqu'il n'y a aucun résultat
 * 
 * @param Array options Options de l'autocomplete jQuery + 
 *                      { 
 *                          elementDomId: "Id DOM de l'élément caché contenant l'id de l'item sélectionné (obligatoire)", 
 *                          noResultItemLabel: "Label de l'item affiché lorsque la recherche ne renvoit rien (optionnel)"
 *                      }
 * @returns description self
 */
$.fn.autocompleteUnicaen = function(options) 
{
    var defaults = {
        elementDomId: null,
        noResultItemLabel: "Aucun résultat trouvé.",
    };
    var opts = $.extend(defaults, options);
    if (!opts.elementDomId) {
        alert("Id DOM de l'élément invisible non spécifié.");
    }
    var select = function(event, ui) {
        // un item sans attribut "id" ne peut pas être sélectionné (c'est le cas de l'item "Aucun résultat")
        if (ui.item.id) {
            $(event.target).val(ui.item.label);
            $('#' + opts.elementDomId).val(ui.item.id);
        }
        return false;
    };
    var response = function(event, ui) {
        if(!ui.content.length) {
            ui.content.push({ label: opts.noResultItemLabel });
        }
    };
    var element = this;
    element.autocomplete($.extend({ select: select, response: response }, opts))
        // on doit vider le champ caché lorsque l'utilisateur tape le moindre caractère (touches spéciales du clavier exclues)
        .keypress(function(event) {
            if (event.which === 8 || event.which >= 32) { // 8=backspace, 32=space
                $('#' + opts.elementDomId).val(null);
            }
        })
        // on doit vider le champ caché lorsque l'utilisateur vide l'autocomplete (aucune sélection)
        // (nécessaire pour Chromium par exemple)
        .keyup(function() {
            if (!$(this).val().trim().length) {
                $('#' + opts.elementDomId).val(null);
            }
        })
        // ajoute de quoi faire afficher plus d'infos dans la liste de résultat de la recherche
        .data("ui-autocomplete")._renderItem = function(ul, item) {
            var template = item.template ? item.template : '<span id=\"{id}\">{label} <span class=\"extra\">{extra}</span></span>';
            var markup   = template
                    .replace('{id}', item.id ? item.id : '')
                    .replace('{label}', item.label ? item.label : '')
                    .replace('{extra}', item.extra ? item.extra : '');
            markup = '<a>' + markup + "</a>";
            var li = $("<li></li>").data("item.autocomplete", item).append(markup).appendTo(ul);
            // mise en évidence du motif dans chaque résultat de recherche
            highlight(element.val(), li, 'sas-highlight');
            // si l'item ne possède pas d'id, on fait en sorte qu'il ne soit pas sélectionnable
            if (!item.id) {
                li.click(function() { return false; });
            }
            return li; 
        };
    return this;
};

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
    RegExp.escape = function(text) { // Note: if you don't care for (), you can remove it..
        return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
    }
    var re = new RegExp("(" + RegExp.escape(term) + ")", "gi");
    $("*", base).contents().each(function(i, el) {
        if (el.nodeType === 3) {
            var data = el.data;
            data = data.replace(re, function (arg, match) {
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
(function( $ ) {
    /**
     * Sérialize au format tableau compatible JSON (name => value)
     * un formulaire ou des éléments de formulaire.
     * Même utilisation que "serializeArray()".
     */
    $.fn.serializeArrayJson = function() {
        var json = {};
        jQuery.map($(this).serializeArray(), function(n, i){
        json[n['name']] = n['value'];
        });
        return json;
    };

    /**
     * Rafraichit un élément en fonction d'une url donnée.
     * Se base sur l'attribut data-url de l'élément
     * Si l'attribut data-url n'est pas renseigné alors il ne se passe rien
     *
     * @param array|FormElement|null    data    (json) à transmettre
     * @param function                  onEnd   Fonction de callback à passer, si besoin. S'exécute une fois le rafraichissement terminé
     * @returns Element
     */
    $.fn.refresh = function( data, onEnd ){
        var that = $(this);
        var url = this.data('url');
        if (data instanceof jQuery){
            data = data.serialize();
        }
        if ("" !== url && undefined !== url) {
            that.load( url, data, onEnd );
        }
        return that;
    }

}) ( jQuery );
    
 /**
 * Exécuté quand le graphe DOM est chargé.
 */
$(function() {
    //installIcons(); à faire plutôt dans chaque vue intéressée
    //installConfirm();
});

/**
 * Remplace les liens d'action présents dans les tables (dans un "td.action") 
 * par un icône dont le title est le texte du lien remplacé.
 * 
 * @param options Objet : attribut = classe CSS, valeur = classe d'icône jQuery.
 */
function installIconsJQuery(options) 
{
    var defaults = {
        'action-ajouter':       'ui-icon-plus',
        'action-apercu':        'ui-icon-image',
        'action-afficher':      'ui-icon-zoomin',
        'action-voir':          'ui-icon-zoomin',
        'action-modifier':      'ui-icon-pencil',
        'action-supprimer':     'ui-icon-trash',
        'action-annuler':       'ui-icon-trash',
    };
    
    var classes = $.extend(defaults, options);
    
    for (c in classes) {
        $("a.iconify." + c).addClass('ui-icon ' + classes[c]).css('float','left').each(function() {
            if (!$(this).attr('title')) {
                $(this).attr('title', $(this).text());
            }
        });
    }
}
function installIcons(options) 
{
    var defaults = {
        'action-ajouter':       'glyphicon-plus',
        'action-apercu':        'glyphicon-image',
        'action-afficher':      'glyphicon-eye-open',
        'action-voir':          'glyphicon-eye-open',
        'action-modifier':      'glyphicon-pencil',
        'action-supprimer':     'glyphicon-trash',
        'action-annuler':       'glyphicon-trash',
    };
    
    var classes = $.extend(defaults, options);
    
    for (c in classes) {
        $("a.iconify." + c).each(function() {
            if (!$(this).attr('title')) {
                $(this).attr('title', $(this).text());
            }
            $(this).html('<button type="button" class="btn btn-link btn-xs"><span class="glyphicon ' + classes[c] + '"></span></button>');
        });
    }
}

/**
 * Installe une demande de confirmation pour certains liens d'actions
 */
function installConfirm() {
    $(".actions a:contains('Supprimer')").each(function() {
        var message;
        if (!$(this).attr('title')) {
            message = "Confirmez-vous la suppression de cet enregistrement ?";
        }
        else {
            message = $(this).attr('title');
            message = "Êtes-vous sûr(e) de vouloir " + message.substr(0,1).toLowerCase() + message.substr(1) + " ?";
        }
        $(this).click(function() { askConfirmation($(this), message); });
    });
    $(".actions .confirm").each(function() {
        $(this).click(function() { askConfirmation($(this)); });
    });
}

/**
 * Installe une demande de confirmation sur le lien ou bouton spécifié.
 * 
 * @param object target
 * @param string message
 */
function askConfirmation(target, message) 
{
    var msg;
    if (message.length)
        msg = message;
    else if ($(target).attr('title'))
        msg = $(target).attr('title');
    else
        msg = 'effectuer cette opération';
    
    msg = "Êtes-vous sûr(e) de vouloir " + msg.substr(0,1).toLowerCase() + msg.substr(1) + " ?";
    
    return confirm(msg);
}


function cssColor(id) {
    var colors = new Array(
        'Black',
        'Blue',
        'BlueViolet' ,
        'Brown' ,
        'CadetBlue' ,
        'Chocolate' ,
        'Coral' ,
        'CornflowerBlue' ,
        'Crimson' ,
        'DarkBlue' ,
        'DarkCyan' ,
        'DarkGoldenRod' ,
        'DarkGreen' ,
        'DarkKhaki' ,
        'DarkMagenta',
        'Darkorange' ,
        'DeepPink' ,
        'Red'
    );
    id = abs((0+id) % colors.length);
    return colors[id];
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
    var classe = jQuery.grep(classes, function(elementOfArray, indexInArray) {
        return contains ?
            elementOfArray.indexOf(prefix) > -1 :
            elementOfArray.indexOf(prefix) === 0;
    });
    return classe.length ?
        (substring ? classe[0].substr(classe[0].indexOf(prefix) + prefix.length) : classe[0]) :
        '';
}

var atoggle, menu, content, origwidth;
/**
 * Ajoute de quoi afficher/masquer le menu de gauche pour profiter de toute la largeur.
 * NB: La visibilité du menu est mémorisé dans le cache du navigateur (plugin jQuery "jStorage" requis).
 */
function installMenuToggleButton()
{
    menu = $('#left-menu'),
    content = $('#content, #breadcrumbs'),
    origwidth = 100 * parseInt(content.css('width')) / parseInt($('#wrapper').css('width')); // en %
    atoggle = $('<a title="Afficher/masquer le menu"></a>')
        .button({icons: {primary: 'ui-icon-carat-2-e-w'}, texte: false})
        .css('height','12px').css('width','30px').css('margin-bottom','2px')
        .click(function() {
            var visible = $.jStorage.get('menuvisible', '?');
            if (visible == '?') {
                visible = false;
            }
            else {
                visible = !visible;
            }
            setMenuVisible(visible);
            $.jStorage.set('menuvisible', visible);
        })
        .appendTo(menu);
        
    var menuvisible = $.jStorage.get('menuvisible', '?');
    if (menuvisible != '?') {
        setMenuVisible(menuvisible);
    }
}
function setMenuVisible(visible)
{  
    if (visible) {
        content.css('width', origwidth+'%');
        menu.children().not(atoggle).show();
    }
    else {
        menu.children().not(atoggle).hide();
        content.css('width', '97%');
    }
}

/**
 * Lorsque l'élément spécifié est cliqué, opacifie la page et affiche un témoin de 
 * chargement (pour faire patienter) à droite de l'élément.
 * @param element Élément concerné
 * @param opacity Ratio d'opacité, ex: "0.5"
 * @param ajaxLoadingElement Temoin de chargement qui sera affiché, ex: $("#ajax-loader")
 */
function installOpacifier(element, opacity, ajaxLoadingElement) {
    element.click(function() {
        $("body").css("opacity", opacity ? opacity : "0.5");
        $(ajaxLoadingElement ? ajaxLoadingElement : "#ajax-loader").show().position({
            my: "left center",
            at: "right center",
            of: element, /* or $("#otherdiv) */
            offset: "5 0"
        });
    });
}

/**
 * Indique les éléments de formulaires obligatoires.
 */
function installFormRequirement(form)
{
    var labels = form ? $(form).find("label.required") : $("label.required");
    labels.after('<span class="requirement" title="Ce champ est obligatoire"> *</span>');
    if (!form || !form.length) {
        form = labels.parents("form");
    }
    $(form).prepend('<p class="requirement">* : champs obligatoires</p>');
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

    function getJSONKey(key) {
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
    
    return text.replace(reg, function(t) {
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
    }
    else {
        return uri + separator + key + "=" + value;
    }
}