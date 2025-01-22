IntraNavigator = {
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
        }
    },


    formSubmitListener: function (e) {
        const postData = new FormData(e.target); // paramètre "modal" indispensable
        const url = e.target.getAttribute('action');
        const elementToRefresh = IntraNavigator.getElementToRefresh(e.target);

        if (elementToRefresh) {
            // requête AJAX de soumission du formulaire
            IntraNavigator.pageBeforeLoad(url, elementToRefresh);

            fetch(url, {
                method: 'POST',
                headers: {
                    //    'Content-Type': 'application/x-www-form-urlencoded'
                    'X-Requested-With': 'XMLHttpRequest' // Indique que c'est une requête AJAX
                },
                body: postData
            }).then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status} ${response.statusText}`);
                }

                const contentType = response.headers.get('content-type');

                if (contentType && contentType.includes('text/html')) {
                    response.text().then(htmlData => {
                        IntraNavigator.refreshElement(elementToRefresh, htmlData, true);

                        // Appeler la fonction de succès
                        IntraNavigator.pageAfterLoadSuccess(url, elementToRefresh);
                    });
                } else {
                    IntraNavigator.downloadFile(response);
                }
            }).catch(error => {
                IntraNavigator.pageAfterLoadError(url, elementToRefresh, error);
            });
        }

        e.preventDefault();
    },


    innerAnchorClickListener: function (e) {
        const anchor = e.target;
        const url = anchor.getAttribute('href');
        const elementToRefresh = IntraNavigator.getElementToRefresh(anchor);

        if (elementToRefresh && url && url !== "#") {
            IntraNavigator.pageBeforeLoad(url, elementToRefresh);

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Indique que c'est une requête AJAX
                }
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Erreur HTTP : ' + response.status);
                }

                // Vérifier si la réponse est en HTML
                const contentType = response.headers.get('content-type');

                if (contentType && contentType.includes('text/html')) {
                    response.text().then(htmlData => {
                        IntraNavigator.refreshElement(elementToRefresh, htmlData, false);

                        // Appeler la fonction de succès
                        IntraNavigator.pageAfterLoadSuccess(url, elementToRefresh);
                    });
                } else {
                    IntraNavigator.downloadFile(response);
                }
            }).catch(error => {
                // Appeler la fonction d'erreur
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
        if (elementToRefresh && elementToRefresh.hasClass('intranavigator-page')) {
            history.pushState({url}, null, url);
        }
        window.dispatchEvent(new CustomEvent("intranavigator.change"));
        IntraNavigator.loadingTooltip.style.display = 'none';
    },


    pageAfterLoadError(url, elementToRefresh, error)
    {
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

        const contentDisposition = response.headers.get('content-disposition');
        const filename = getFilenameFromHeader(contentDisposition);

        if (!filename) {
            throw new Error('Nom du fichier non trouvé dans l\'en-tête Content-Disposition');
        }

        return response.blob().then(blobData => {
            // Créer un lien de téléchargement
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blobData);
            link.download = filename; // Utiliser le nom du fichier extrait

            // Simuler un clic sur le lien pour déclencher le téléchargement
            link.click();

            // Libérer l'objet URL
            window.URL.revokeObjectURL(link.href);

            IntraNavigator.pageAfterLoadSuccess();
        });
    },


    createLoadingTooltip()
    {
        const loadingTooltip = document.createElement('div');
        IntraNavigator.loadingTooltip = loadingTooltip;

        loadingTooltip.id = 'loading-tooltip';

        // Ajouter du contenu à la div (icône de chargement et texte)
        loadingTooltip.innerHTML = '<div class="spinner-border text-primary" role="status">\n' +
            '  <span class="visually-hidden">Loading...</span>\n' +
            '</div> Chargement...';

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


    installAnchorEvents()
    {
        function shouldBlockAnchor(element)
        {
            const classesToCheck = ['no-intranavigation', 'pop-ajax', 'ajax-modal', 'mod-ajax', 'tab-ajax'];

            for (const cls of classesToCheck) {
                if (element.classList.contains(cls)) {
                    return false; // on bloque l'intranavigation => autre mécanisme
                }
            }


            // Trouver le parent le plus proche ayant l'une des deux classes
            const closestParent = element.closest('.intranavigator, .no-intranavigation');

            // Si un parent est trouvé
            if (closestParent) {
                // Bloquer uniquement si le parent a la classe "intranavigator"
                return closestParent.classList.contains('intranavigator');
            }

            // Si aucun parent n'est trouvé, vérifier si l'ancre elle-même a la classe "intranavigation"
            return element.classList.contains('intranavigator');
        }

        // Fonction pour bloquer les clics sur les ancres ciblées
        function blockAnchorClicks(event)
        {
            const anchor = event.target;

            // Vérifier si l'ancre doit être bloquée et si aucun gestionnaire d'événements n'est déjà attaché
            if (anchor.tagName === 'A' && shouldBlockAnchor(anchor)) {
                IntraNavigator.innerAnchorClickListener(event);
            }
        }

        // Attacher un gestionnaire d'événements global pour les clics
        document.addEventListener('click', blockAnchorClicks, true); // Utiliser la capture pour intercepter tôt

        // Observer les modifications du DOM pour les nouvelles ancres
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                mutation.addedNodes.forEach(node => {
                    if (node.tagName === 'A' && shouldBlockAnchor(node)) {
                        // Bloquer les clics sur les nouvelles ancres ciblées
                        node.addEventListener('click', blockAnchorClicks);
                    } else if (node.querySelectorAll) {
                        // Bloquer les clics sur les ancres ciblées à l'intérieur des nouveaux éléments
                        node.querySelectorAll('a').forEach(anchor => {
                            if (shouldBlockAnchor(anchor)) {
                                //anchor.addEventListener('click', blockAnchorClicks);
                                anchor.addEventListener('click', IntraNavigator.innerAnchorClickListener);
                            }
                        });
                    }
                });
            });
        });

        // Démarrer l'observation du DOM
        observer.observe(document.body, {
            childList: true, // Observer les ajouts/suppressions d'enfants
            subtree: true    // Observer tout le sous-arbre du DOM
        });
    },


    /**
     * Lance automatiquement l'association de tous les widgets déclarés avec les éléments HTMl de classe correspondante
     */
    installSubmitEvents()
    {
        var submitSelector = '.intranavigator form:not(.no-intranavigation)';

        $('body').off("submit", submitSelector, IntraNavigator.formSubmitListener);
        $('body').one("submit", submitSelector, IntraNavigator.formSubmitListener);
        $('.intranavigator [autofocus]').trigger("focus");
    },


    /**
     * Gère les changements d'URL (par exemple, quand l'utilisateur utilise les boutons Précédent/Suivant)
     */
    installNavigation()
    {
        if (document.querySelector('.intranavigator-page')) {
            //window.onpopstate = function (event) {
            //    IntraNavigator.loadPage(url);
            //};
        }
    },


    /**
     * Installe le WidgetInitializer pour qu'il se lance au chargement de la page ET après chaque modification du DOM
     */
    install()
    {
        this.createLoadingTooltip();
        this.installAnchorEvents();
        this.installSubmitEvents();
        this.installNavigation();
    },


    /**
     * Charge une URL pour mettre à jour la page
     */
    loadPage(url)
    {
        if (!url) {
            url = window.location.href;
        }

        IntraNavigator.pageBeforeLoad(url);
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Indique que c'est une requête AJAX
            }
        }).then(response => {
            if (!response.ok) {
                throw new Error('Erreur HTTP : ' + response.status);
            }

            response.text().then(htmlData => {
                document.getElementsByClassName('intranavigator-page')[0].innerHTML = htmlData;
                IntraNavigator.pageAfterLoadSuccess(url);
            });
        }).catch(error => {
            console.error('Erreur :', error);
        });
    }
};