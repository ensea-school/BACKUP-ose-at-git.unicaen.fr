IntraNavigator = {
    _loadingTooltip: null,
    _loading: false,
    _installed: false,

    install()
    {
        this._createLoadingTooltip();

        const that = this;
        //document.addEventListener("DOMContentLoaded", () => {
        // On parse le document au chargement de la page
        that._parse(document);
        //});
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeName != '#text' && node.nodeName != '#comment') {
                        // à chaque changement détecté, on ajoute les nouveaux éléments
                        that._parse(node);
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true, subtree: true
        });

        this._installed = true;
    },


    /** @deprecated */
    add(element)
    {
        element = this.__dejqueryfy(element);
        element.classList.add('intranavigator');
        this._parse(element);
    },


    embeds: function (element) {
        const closestParent = element.closest('.intranavigator, .no-intranavigation');

        // Si un parent est trouvé
        if (closestParent) {
            // Si son plus proche parent est in intranavigator et non l'inverse, ok
            return closestParent.classList.contains('intranavigator');
        } else {
            return false;
        }
    },


    hasErrors: function (element) {
        element = this.__dejqueryfy(element);

        if (typeof element === 'string') {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = element;
            element = tempDiv;
        }

        const errs = element.querySelectorAll('.input-error, .has-error, .has-errors, .alert.alert-danger').length;

        return errs > 0;
    },


    extractTitle: function (element) {
        element = this.__dejqueryfy(element);

        const res = {
            content: undefined,
            title: undefined
        };

        if (typeof element === 'string') {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = element;
            element = tempDiv;
        } else {
            // Sinon, on crée un nouvel élément div vide
            element = document.createElement('div');
        }

        const extractedTitle = element.querySelector('.title, .modal-title, .popover-title, .page-header');

        if (extractedTitle) {
            // Si un titre est trouvé, on l'extrait et on le supprime de l'élément
            res.title = extractedTitle.innerHTML.trim();
            extractedTitle.remove();
        }

        res.content = element.innerHTML.trim();

        return res;
    },


    _createLoadingTooltip()
    {
        const that = this;
        const loadingTooltip = document.createElement('div');
        this._loadingTooltip = loadingTooltip;

        loadingTooltip.id = 'loading-tooltip';

        // Ajouter du contenu à la div (icône de chargement et texte)
        loadingTooltip.innerHTML = '<div class="spinner-border text-primary" role="status">\n' +
            '  <span class="visually-hidden">Loading...</span>\n' +
            '</div> <span style="line-height: 35px;display: block;float: right;margin-left: 10px;">Chargement...</span>';

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
            zIndex: '2500', // S'assure qu'elle est au-dessus des autres éléments
        });

        // Ajouter la div au body
        document.body.appendChild(loadingTooltip);

        document.addEventListener('mousemove', (e) => {
            // Déplacer la div à la position de la souris
            that._loadingTooltip.style.left = `${event.clientX + 10}px`;
            that._loadingTooltip.style.top = `${event.clientY + 10}px`;
        });
    },


    _parse(element)
    {
        const that = this;

        this._parseElement(element);
        element.querySelectorAll('a, form').forEach(subElement => {
            that._parseElement(subElement);
        });
    },


    _parseElement(element)
    {
        const that = this;

        switch (element.tagName) {
            case 'A':
                if (that.anchorIsEligible(element)) {
                    element.dataset.intranavigator = '1';
                    element.addEventListener("click", (e) => {
                        that.anchorClick(e)
                    });
                }
                break;
            case 'FORM':
                if (that.formIsEligible(element)) {
                    element.dataset.intranavigator = '1';
                    element.addEventListener("submit", (e) => {
                        that.formSubmit(e)
                    });
                }
                break;

        }
    },


    anchorIsEligible(a)
    {
        // Si l'élément n'a pas de liste de classe, on dégage
        if (!a.classList) {
            return false;
        }

        // Si l'intranavigateur est déjà chargé, on jette
        if (a.dataset.intranavigator == '1') {
            return false;
        }

        // l'ancre est une interne ou bien elle n'a pas de href => pas d'intranavigation possible
        if (!a.getAttribute('href') || a.getAttribute('href').startsWith('#')) {
            return false;
        }

        // Si c'est explicitement un intranavigateur, ok
        if (a.classList.contains('intranavigator')) {
            return true;
        }

        //  Si l'ancre a une classe interdite, on jette
        const classesToCheck = ['no-intranavigation', 'pop-ajax', 'ajax-modal', 'mod-ajax', 'tab-ajax'];
        for (const cls of classesToCheck) {
            if (a.classList.contains(cls)) {
                return false; // on bloque l'intranavigation => autre mécanisme
            }
        }


        // Trouver le parent le plus proche ayant l'une des deux classes
        const closestParent = a.closest('.intranavigator, .no-intranavigation');

        // Si un parent est trouvé
        if (closestParent) {
            // Si son plus proche parent est in intranavigator et non l'inverse, ok
            return closestParent.classList.contains('intranavigator');
        }

        // Sinon non
        return false;
    },


    anchorClick(e)
    {
        const that = this;
        const anchor = e.target;
        const url = anchor.getAttribute('href');
        const elementToRefresh = anchor.closest('.intranavigator');

        if (elementToRefresh && url) {
            e.preventDefault();
            this._pageBeforeLoad(url, elementToRefresh);

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
                        that._refreshElement(elementToRefresh, htmlData, false);

                        // Appeler la fonction de succès
                        this._pageAfterLoadSuccess(url, elementToRefresh);
                    });
                } else {
                    that._downloadFile(response);
                }
            }).catch(error => {
                // Appeler la fonction d'erreur
                that._pageAfterLoadError(url, elementToRefresh, error);
            });
        }
    },


    formIsEligible(form)
    {
        // Si l'élément n'a pas de liste de classe, on dégage
        if (!form.classList) {
            return false;
        }

        // Si l'intranavigateur est déjà chargé, on jette
        if (form.dataset.intranavigator == '1') {
            return false;
        }

        // Si c'est explicitement un intranavigateur, ok
        if (form.classList.contains('intranavigator')) {
            return true;
        }

        //  Si l'ancre a une classe interdite, on jette
        const classesToCheck = ['no-intranavigation'];
        for (const cls of classesToCheck) {
            if (form.classList.contains(cls)) {
                return false; // on bloque l'intranavigation => autre mécanisme
            }
        }


        // Trouver le parent le plus proche ayant l'une des deux classes
        const closestParent = form.closest('.intranavigator, .no-intranavigation');

        // Si un parent est trouvé
        if (closestParent) {
            // Si son plus proche parent est in intranavigator et non l'inverse, ok
            return closestParent.classList.contains('intranavigator');
        }

        // Sinon non
        return false;
    },


    formSubmit(e)
    {
        const that = this;
        const postData = new FormData(e.target); // paramètre "modal" indispensable
        const url = e.target.getAttribute('action');
        const elementToRefresh = e.target.closest('.intranavigator');

        if (elementToRefresh) {
            e.preventDefault();

            // requête AJAX de soumission du formulaire
            this._pageBeforeLoad(url, elementToRefresh);

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
                        that._refreshElement(elementToRefresh, htmlData, true);

                        // Appeler la fonction de succès
                        that._pageAfterLoadSuccess(url, elementToRefresh);
                    });
                } else {
                    that._downloadFile(response);
                }
            }).catch(error => {
                that._pageAfterLoadError(url, elementToRefresh, error);
            });
        }
    },


    _refreshElement: function (element, data, isSubmit) {
        element.innerHTML = data;

        const event = new CustomEvent('intranavigator.refresh', {
            detail: {
                element: element,
                isSubmit: isSubmit
            }
        });

        element.dispatchEvent(event);
    },


    _downloadFile(response)
    {
        const that = this;

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

            that._pageAfterLoadSuccess();
        });
    },


    loadBegin()
    {
        if (!this._installed) {
            this.install();
        }

        IntraNavigator._loadingTooltip.style.display = 'block';
        this._loading = true;
    },



    loadEnd()
    {
        if (!this._installed) {
            this.install();
        }

        IntraNavigator._loadingTooltip.style.display = 'none';
        this._loading = false;
    },


    _pageBeforeLoad(url, elementToRefresh)
    {
        this.loadBegin();
    },


    _pageAfterLoadSuccess(url, elementToRefresh)
    {
        if (elementToRefresh && elementToRefresh.classList.contains('intranavigator-page')) {
            history.pushState({url}, null, url);
        }
        window.dispatchEvent(new CustomEvent("intranavigator.load"));

        this.loadEnd();
    },


    _pageAfterLoadError(url, elementToRefresh, error)
    {
        this.loadEnd();
    },


    __dejqueryfy(element)
    {
        if (typeof element === 'string') {
            return element;
        }

        if (element[0] && element.length && element.length > 0) {
            element = element[0];
        }
        return element;
    },
};