/**
 * Système d'initialisation automatique de widgets
 *
 */
WidgetInitializer = {

    /**
     * Liste des widgets déclarés (format [className => widgetName])
     * className = Nom de la classe CSS qui déclenche l'association
     * widgetName = Nom du widget (sans le namespace)
     */
    widgets: {},

    use: function (className)
    {
        if (!this.widgets[className]) {
            console.log('ATTENTION : Widget ' + className + ' non déclaré!!');
            return;
        }

        var widgetName = this.widgets[className].widgetName;
        var onInitialize = this.widgets[className].onInitialize;
        var widgets = $('.' + className);

        if (widgets.length > 0) {
            if (undefined != onInitialize && !WidgetInitializer.widgets[className].initialized) {
                onInitialize();
                WidgetInitializer.widgets[className].initialized = true;
            }
            if (widgetName) {
                widgets.each(function () {
                    try {
                        $(this)[widgetName]($(this).data('widget'));
                    } catch (e) {
                        console.log('ERREUR : Widget "' + widgetName + '" non trouvé');
                        console.log(e);
                    }
                });
            }
        }
    },

    /**
     * Ajoute un nouveau Widget à l'initializer
     *
     * @param string className
     * @param string widgetName
     */
    add: function (className, widgetName, onInitialize)
    {
        if (typeof widgetName === "object") {
            $.widget("wi." + className, widgetName);
            widgetName = className;
        }

        WidgetInitializer.widgets[className] = {
            widgetName: widgetName,
            onInitialize: onInitialize,
            initialized: false
        };
        this.use(className);
    },

    /**
     * Lance automatiquement l'association de tous les widgets déclarés avec les éléments HTMl de classe correspondante
     */
    run: function ()
    {
        for (className in this.widgets) {
            this.use(className);
        }
    },

    /**
     * Installe le WidgetInitializer pour qu'il se lance au chargement de la page ET après chaque requête AJAX
     */
    install: function ()
    {
        var that = this;

        this.run();
        window.addEventListener("intranavigator.change", (event) => {
            that.run();
        });
        $(document).ajaxSuccess(function () {
            that.run();
        });
    },

    includeCss: function (fileName)
    {
        if (!$("link[href='" + fileName + "']").length) {
            var link = '<link rel="stylesheet" type="text/css" href="' + fileName + '">';
            $('head').append(link);
        }
    },

    includeJs: function (fileName)
    {
        if (!$("script[src='" + fileName + "']").length) {
            var script = '<script type="text/javascript" src="' + fileName + '">' + '</script>';
            $('body').append(script);
        }
    }
};