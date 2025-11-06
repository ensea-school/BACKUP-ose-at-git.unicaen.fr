$.widget("unicaen.popAjax", {

    popInstance: undefined,
    loading: true,
    ajaxLoaded: false,

    options: {
        url: undefined,
        title: undefined,
        content: undefined,
        confirm: false,
        confirmButton: '<i class="fas fa-check"></i> OK',
        cancelButton: '<i class="fas fa-xmark"></i> Annuler',
        submitEvent: undefined,
        submitClose: false,
        submitReload: false,
        forced: true,
        loadingTitle: 'Chargement...',
        loadingContent: '<div class="loading"></div>',
    },



    _create: function () {
        var that = this;

        that.loadOptions();

        /* Traitement des événements */
        if ('A' === this.element.prop("tagName")) {
            // On retire le comportement normal du click sur les ancres
            that.element.on("click", () => {
                return false;
            });
        }

        $('html').on("click", (e) => {
            // On détecte si on fait un clic ailleurs afin de fermer la pop-ajax
            that.htmlClick(e);
        });

        /* Préparation du popover bootstrap */
        popoptions = {
            html: true,
            sanitize: false,
            title: that.options.title ? that.options.title : that.options.loadingTitle,
            content: that.options.content ? that.options.content : that.options.loadingContent,
        };
        that.popInstance = new bootstrap.Popover(that.element, popoptions);

        that.element[0].addEventListener('show.bs.popover', () => {
            that.show(true);
        });

        that.element[0].addEventListener('inserted.bs.popover', () => {
            var pob = that.getPopoverElement().find('.popover-body');
            pob.addClass('intranavigator');
            pob.on('DOMSubtreeModified', () => {
                if (pob.find('.popover-title,.page-header').length > 0) {
                    that.setContent(pob.html());
                }
            });

            pob[0].addEventListener('intranavigator.refresh', (event) => {
                if (event.detail.isSubmit) {
                    that.contentSubmit(pob);
                }
            });
        });

        that.element[0].addEventListener('hidden.bs.popover', () => {
            that.hide(true);
        });
    },



    loadOptions: function () {
        /* Traitement des options de configuration pour chargement */
        var optionsKeys = {
            url: 'url',
            content: 'content',
            title: 'title',
            confirm: 'confirm',
            confirmButton: 'confirm-button',
            cancelButton: 'cancel-button',
            submitEvent: 'submit-event',
            submitClose: 'submit-close',
            submitReload: 'submit-reload',
            forced: 'forced',
            loadingTitle: 'loading-title',
            loadingContent: 'loading-content',
        };

        for (var k in optionsKeys) {
            if (typeof this.element.data(optionsKeys[k]) !== 'undefined') {
                this.options[k] = this.element.data(optionsKeys[k]);
            }
        }
        if (this.options.title === undefined) {
            this.options.title = this.element.attr('title');
        }

        if ('A' === this.element.prop("tagName")) {
            this.options.url = this.element.attr('href');
        }
    },



    ajaxLoad: function () {
        var that = this;

        this.ajaxLoaded = true;
        this.setTitle(this.options.loadingTitle);
        this.setContent(this.options.loadingContent, true);
        $.ajax({
            url: this.options.url,
            success: (response) => {
                that.setContent(response);
                //that.contentSubmit(that.getPopoverElement().find('.popover-body'));
            }
        });
    },



    setContent: function (content, loading) {
        var ct = IntraNavigator.extractTitle(content);

        this.popInstance._config.content = ct.content;
        this.popInstance.setContent();
        this.popInstance.update();

        if (ct.title) {
            this.setTitle(ct.title);
        }

        if (loading !== true) {
            this._trigger('change', null, this);
        }
    },



    getContent: function () {
        return this.popInstance._config.content;
    },



    setTitle: function (title) {
        this.options.title = title;
        this.popInstance._config.title = this.options.title;

        var poe = this.getPopoverElement();
        if (poe && poe.length == 1) {
            var titleElement = poe.find('.popover-header');
            if (titleElement && titleElement.length == 1) {
                titleElement.html(this.options.title);
            }
        }
    },



    getTitle: function () {
        return this.options.title;
    },



    show: function (shown) {
        var that = this;

        if ((this.options.forced || !this.ajaxLoaded) && this.options.url) {
            if (this.options.confirm) {
                this.setContent(this.makeConfirmBox());
            } else {
                this.loading = true;
                this.ajaxLoad();
            }
        }
        if (shown !== true) {
            this.popInstance.show();
        }
        this._trigger('show', null, this);
        setTimeout(() => {
            that.loading = false;
        }, 100);
    },



    hide: function (hidden) {
        if (hidden !== true) {
            this.popInstance.hide();
        }
        this.loading = true;
        this._trigger('hide', null, this);
    },



    shown: function () {
        return this.getPopoverElement() !== undefined;
    },



    hasErrors: function () {
        return IntraNavigator.hasErrors(this.getContent());
    },



    contentSubmit: function (element) {
        /* Gestion des événements lors de la submition d'un formulaire */
        if (IntraNavigator.hasErrors(element)) {
            this._trigger('error', null, this);
        } else {
            if (this.options.submitEvent) {
                if (this.options.submitEvent instanceof Function) {
                    this.options.submitEvent(this);
                    this.hide();
                } else {
                    $("body").trigger(this.options.submitEvent, this);
                }
            }
            if (this.options.submitClose) {
                this.hide();
            }
            if (this.options.submitReload) {
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }

            this._trigger('submit', null, this);
        }
    },



    makeConfirmBox: function () {
        var c = '<form action="' + this.options.url + '" method="post">' + this.options.content + '<div class="btn-goup" style="text-align:right;padding-top: 10px" role="group">';
        if (this.options.cancelButton) {
            c += '<button type="button" class="btn btn-secondary pop-ajax-hide">' + this.options.cancelButton + '</button>';
        }
        if (this.options.confirmButton && this.options.cancelButton) {
            c += '&nbsp;';
        }
        if (this.options.confirmButton) {
            c += '<button type="submit" class="btn btn-primary">' + this.options.confirmButton + '</button>';
        }
        c += '</div>' + '</form>';

        return c;
    },



    htmlClick: function (e) {
        var popEl = this.getPopoverElement();

        if (this.loading) return;
        if (!popEl || !popEl[0] || e.target == this.element[0]) return true;

        var p = popEl[0].getBoundingClientRect();
        var horsZonePop = e.clientX < p.left || e.clientX > p.left + p.width || e.clientY < p.top || e.clientY > p.top + p.height;
        var horsElementFils = $(e.target).parents('.popover-content,.popover-body,.ui-autocomplete').length == 0;

        if ($(e.target).hasClass('pop-ajax-hide')) {
            this.hide();
        }

        if (horsZonePop) {
            if (horsElementFils) { // il ne faut pas que l'élément soit dans le popover
                this.hide();
            }
        }
    },



    getPopoverElement: function () {
        var id = $(this.element).attr('aria-describedby');

        if (!id) {
            return undefined;
        }
        return $('#' + id);
    },
});



$(function () {
    WidgetInitializer.add('pop-ajax', 'popAjax');
});


// Recours à l'utilisation du popConfirm standard
function popConfirm(element, options)
{
    if (typeof options == 'function') {
        options = {
            confirm: options
        };
    }

    if (!options) {
        options = {};
    }
    if (!options.title) {
        options.title = element.dataset.title;
    }
    if (!options.title) {
        options.title = "Demande de confirmation";
    }

    if (!options.content) {
        options.content = element.dataset.content;
    }
    if (!options.content) {
        options.content = "Confirmez-vous cette action ?";
    }

    if (!options.confirm) {
        options.confirm = () => {
        };
    }
    if (!options.url) {
        options.url = element.href;
    }
    if (!options.url) {
        options.url = element.dataset.url;
    }

    if (options.url) {
        goFunc = () => {
            unicaenVue.axios.get(options.url).then(response => {
                options.confirm(response, element);
            });
        };
    } else {
        goFunc = () => {
            options.confirm(element);
        };
    }

    if (confirm(options.content)){
        goFunc();
    }
}


// ancien popConform sauvegardé
function popConfirmOld(element, options)
{
    var popConfirm = bootstrap.Popover.getInstance(element);
    if (!popConfirm) {
        if (typeof options == 'function') {
            options = {
                confirm: options
            };
        }

        if (!options) {
            options = {};
        }
        if (!options.title) {
            options.title = element.dataset.title;
        }
        if (!options.title) {
            options.title = "Demande de confirmation";
        }

        if (!options.content) {
            options.content = element.dataset.content;
        }
        if (!options.content) {
            options.content = "Confirmez-vous cette action ?";
        }

        if (!options.confirm) {
            options.confirm = () => {
            };
        }
        if (!options.url) {
            options.url = element.href;
        }
        if (!options.url) {
            options.url = element.dataset.url;
        }

        if (options.url) {
            goFunc = () => {
                unicaenVue.axios.get(options.url).then(response => {
                    options.confirm(response, element);
                });
            };
        } else {
            goFunc = () => {
                options.confirm(element);
            };
        }

        let popoptions = {
            html: true,
            sanitize: false,
            trigger: "focus",
            title: options.title,
            content: options.content + '<div class="btn-goup" style="text-align:right;padding-top: 10px" role="group"><button class="btn btn-secondary" id="nogo">Non</button><button class="btn btn-primary" id="go">Oui</button></div>',
        };
        popConfirm = new bootstrap.Popover(element, popoptions);
        element.addEventListener('shown.bs.popover', (eventShown) => {
            const popDivId = eventShown.target.getAttribute('aria-describedby');
            const popDiv = document.getElementById(popDivId);

            const goButton = popDiv.querySelector('button#go');
            const nogoButton = popDiv.querySelector('button#nogo');

            goButton.addEventListener('click', () => {
                popConfirm.hide();
                $("#" + popDivId).remove();
                goFunc();
            });

            nogoButton.addEventListener('click', () => {
                popConfirm.hide();
            });
        });
    }

    popConfirm.show();
}