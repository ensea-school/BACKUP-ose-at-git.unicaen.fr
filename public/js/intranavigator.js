IntraNavigator = {
    getElementToRefresh: function (element)
    {
        return $($(element).parents('.intranavigator').get(0));
    },

    refreshElement: function (element, data, isSubmit)
    {
        element.html(data);
        $("body").trigger('intranavigator-refresh', {element: element, isSubmit: isSubmit});
    },

    embeds: function (element)
    {
        return $(element).parents('.intranavigator').length > 0;
    },

    add: function (element)
    {
        if (!$(element).hasClass('intranavigator')) {
            $(element).addClass('intranavigator');
            //IntraNavigator.run();
        }
    },

    waiting: function (element, message)
    {
        if ($(element).find('.intramessage').length == 0) {
            var msg = message ? message : 'Chargement';
            msg += ' <span class="loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
            msg = '<div class="alert alert-success intramessage" role="alert">' + msg + '</div>';
            $(element).append(msg);
        } else {
            $(element).find('.intramessage').show();
        }
    },

    endWaiting: function ()
    {
        $('.intramessage').hide();
    },

    formSubmitListener: function (e)
    {
        var form = $(e.target);
        var postData = form.serializeArray(); // paramètre "modal" indispensable
        var url = form.attr('action');
        var elementToRefresh = IntraNavigator.getElementToRefresh(form);

        if (elementToRefresh) {
            // requête AJAX de soumission du formulaire
            IntraNavigator.waiting(elementToRefresh, 'Veuillez patienter s\'il vous plaît...');
            $.post(url, postData, $.proxy(function (data)
            {
                IntraNavigator.refreshElement(elementToRefresh, data, true);
            }, this));
        }
        e.preventDefault();
    },

    innerAnchorClickListener: function (e)
    {
        var anchor = $(e.currentTarget);
        var url = anchor.attr('href');
        var elementToRefresh = IntraNavigator.getElementToRefresh(anchor);

        if (elementToRefresh && url && url !== "#") {
            // requête AJAX pour obtenir le nouveau contenu de la fenêtre modale
            IntraNavigator.waiting(elementToRefresh, 'Chargement');
            $.get(url, {}, $.proxy(function (data)
            {
                IntraNavigator.refreshElement(elementToRefresh, data, true);
            }, this));
        }

        e.preventDefault();
    },

    /*btnPrimaryClickListener: function (e)
     {
     var form = IntraNavigator.getElementToRefresh(e.target).find('form');
     if (form.length) {
     form.submit();
     e.preventDefault();
     }
     },*/

    /**
     * Lance automatiquement l'association de tous les widgets déclarés avec les éléments HTMl de classe correspondante
     */
    run: function ()
    {
        var submitSelector = '.intranavigator form:not(.no-intranavigation)';
        var clickSelector = '.intranavigator a:not(.pop-ajax):not(.ajax-modal):not(.no-intranavigation):not(.no-intranavigation a)';

        /* TODO: trouver une meilleure solution que d'utiliser la classe CSS "no-intranavigation" pour désactiver l'intra-navigation ?*/

        $('body').off("submit", submitSelector, IntraNavigator.formSubmitListener);
        $('body').off("click", clickSelector, IntraNavigator.innerAnchorClickListener);
        //$('body').off("click", ".intranavigator .btn-primary", IntraNavigator.btnPrimaryClickListener);

        $('body').one("submit", submitSelector, IntraNavigator.formSubmitListener);
        $('body').one("click", clickSelector, IntraNavigator.innerAnchorClickListener);

        //$('body').one("click", ".intranavigator .btn-primary", IntraNavigator.btnPrimaryClickListener);
        // Réglage du focus sur le champ de formulaire ayant l'attribut 'autofocus'
        $('.intranavigator [autofocus]').focus();
    },

    /**
     * Installe le WidgetInitializer pour qu'il se lance au chargement de la page ET après chaque requête AJAX
     */
    install: function ()
    {
        var that = this;

        this.run();
        $(document).ajaxSuccess(function ()
        {
            that.run();
            that.endWaiting();
        });
    }
};