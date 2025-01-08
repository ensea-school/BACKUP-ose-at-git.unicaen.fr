IntraNavigator = {
    axios: null,
    loadingTooltip: null,

    getElementToRefresh: function (element) {
        return $($(element).parents('.intranavigator').get(0));
    },


    refreshElement: function (element, data, isSubmit) {
        element.html(data);
        element.trigger('intranavigator-refresh', {element: element, isSubmit: isSubmit});
    },


    hasErrors: function (element) {
        if (typeof element === 'string') {
            element = $('<div>' + element + '</div>');
        }

        var errs = element.find('.input-error, .has-error, .has-errors, .alert.alert-danger').length;

        return errs > 0;
    },


    extractTitle: function (element) {
        var res = {
            content: undefined,
            title: undefined
        };

        if (typeof element === 'string') {
            element = $('<div>' + element + '</div>');
        } else {
            element = $('<div></div>');
        }

        var extractedTitle = element.find('.title,.modal-title,.popover-title,.page-header');

        if (extractedTitle.length > 0) {
            res.title = extractedTitle.html().trim();
            extractedTitle.remove();
        }
        res.content = element.html().trim();

        return res;
    },


    embeds: function (element) {
        return $(element).parents('.intranavigator').length > 0;
    },


    add: function (element) {
        if (!$(element).hasClass('intranavigator')) {
            $(element).addClass('intranavigator');
            //IntraNavigator.run();
        }
    },


    formSubmitListener: function (e) {
        var form = $(e.target);
        var postData = form.serializeArray(); // paramètre "modal" indispensable
        var url = form.attr('action');
        var elementToRefresh = IntraNavigator.getElementToRefresh(form);

        if (elementToRefresh) {
            // requête AJAX de soumission du formulaire
            IntraNavigator.pageBeforeLoad(url, elementToRefresh);
            IntraNavigator.axios.post(
                url, postData
            ).then(response => {
                if (response.headers["content-disposition"]) {
                    IntraNavigator.downloadFile(response);
                } else {
                    IntraNavigator.refreshElement(elementToRefresh, response.request.responseText, true);
                }
                IntraNavigator.pageAfterLoad(url, elementToRefresh);
            }).catch(error => {
                IntraNavigator.pageAfterLoadError(url, elementToRefresh, error);
            });
        }

        e.preventDefault();
    },


    innerAnchorClickListener: function (e) {
        var anchor = $(e.currentTarget);
        var url = anchor.attr('href');
        var elementToRefresh = IntraNavigator.getElementToRefresh(anchor);

        if (elementToRefresh && url && url !== "#") {
            IntraNavigator.pageBeforeLoad(url, elementToRefresh);
            IntraNavigator.axios.get(
                url
            ).then(response => {
                if (response.headers["content-disposition"]) {
                    IntraNavigator.downloadFile(response);
                } else {
                    IntraNavigator.refreshElement(elementToRefresh, response.request.responseText, false);
                }
                IntraNavigator.pageAfterLoadSuccess(url, elementToRefresh);
            }).catch(error => {
                IntraNavigator.pageAfterLoadError(url, elementToRefresh, error);
            });
        }

        e.preventDefault();
    },


    pageBeforeLoad(url, elementToRefresh)
    {
        IntraNavigator.loadingTooltip.style.display = 'block';
    },


    pageAfterLoadSuccess(url, elementToRefresh)
    {
        if (elementToRefresh.hasClass('intranavigator-page')) {
            history.pushState({url}, null, url);
        }
        IntraNavigator.loadingTooltip.style.display = 'none';
    },


    pageAfterLoadError(url, elementToRefresh, error)
    {
        console.log(error);
        IntraNavigator.loadingTooltip.style.display = 'none';
    },


    downloadFile(response)
    {
        // Fonction pour extraire le nom du fichier depuis l'en-tête Content-Disposition
        const getFilenameFromHeader = (contentDisposition) => {
            const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            const matches = filenameRegex.exec(contentDisposition);
            if (matches != null && matches[1]) {
                // Retirer les guillemets autour du nom de fichier si présents
                return matches[1].replace(/['"]/g, '');
            }
            return null;
        };

        // Extraire le nom du fichier depuis l'en-tête Content-Disposition
        const contentDisposition = response.headers['content-disposition'];
        const filename = getFilenameFromHeader(contentDisposition);

        if (!filename) {
            throw new Error('Nom du fichier non trouvé dans l\'en-tête Content-Disposition');
        }

        // Créer un objet Blob à partir des données reçues
        const blob = new Blob([response.data]);

        // Créer un lien de téléchargement
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = filename; // Utiliser le nom du fichier extrait

        // Simuler un clic sur le lien pour déclencher le téléchargement
        link.click();

        // Libérer l'objet URL
        window.URL.revokeObjectURL(link.href);
    },



    createLoadingTooltip()
    {
        const loadingTooltip = document.createElement('div');
        IntraNavigator.loadingTooltip = loadingTooltip;

        loadingTooltip.id = 'loading-tooltip';

        // Ajouter du contenu à la div (icône de chargement et texte)
        loadingTooltip.innerHTML = '<i class="fas fa-spinner"></i> Chargement...';

        // Appliquer des styles à la div
        Object.assign(loadingTooltip.style, {
            position: 'absolute',
            display: 'none', // Cachée par défaut
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            color: 'white',
            padding: '8px 12px',
            borderRadius: '4px',
            fontSize: '14px',
            pointerEvents: 'none', // Permet de cliquer à travers la div
            zIndex: '1000', // S'assure qu'elle est au-dessus des autres éléments
        });

        // Ajouter la div au body
        document.body.appendChild(loadingTooltip);

        document.addEventListener('mousemove', IntraNavigator.moveLoadingTooltip);
    },


    moveLoadingTooltip(event)
    {
        // Déplacer la div à la position de la souris
        IntraNavigator.loadingTooltip.style.left = `${event.clientX + 10}px`;
        IntraNavigator.loadingTooltip.style.top = `${event.clientY + 10}px`;
    },


    /**
     * Lance automatiquement l'association de tous les widgets déclarés avec les éléments HTMl de classe correspondante
     */
    run() {
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
        $('.intranavigator [autofocus]').trigger("focus");
    },


    /**
     * Installe le WidgetInitializer pour qu'il se lance au chargement de la page ET après chaque requête AJAX
     */
    install() {
        var that = this;

        this.axios = unicaenVue.axios;

        this.createLoadingTooltip();

        this.run();
        $(document).ajaxSuccess(function () {
            that.run();
        });

        // Gérer les changements d'URL (par exemple, quand l'utilisateur utilise les boutons Précédent/Suivant)
        window.onpopstate = function (event) {
            const url = event.state ? event.state.page : '/';

            let content = document.getElementsByClassName('intranavigator-page');

            IntraNavigator.axios.get(
                url
            ).then(response => {
                content.innerHTML = response.request.responseText;
            });
        };
    }
};