$.widget("unicaen.modAjax", {

    modInstance: undefined,
    modDiv: undefined,
    bodyDiv: undefined,
    headerDiv: undefined,
    actionDiv: undefined,
    uid: undefined,
    loading: true,
    ajaxLoaded: false,

    options: {
        url: undefined,
        title: undefined,
        content: undefined,
        confirm: false,
        confirmButton: '<i class="fas fa-check"></i> OK',
        cancelButton: '<i class="fas fa-xmark"></i> Annuler',
        closeButton: '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" tabindex="99999">Fermer</button>',
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
            that.element.click(function (e) {
                return false;
            });
        }

        $('html').click((e) => {
            // On détecte si on fait un clic ailleurs afin de fermer la mod-ajax
            that.htmlClick(e);
        });

        this.makeDiv();
        this.element.data('bs-toggle', 'modal');
        this.element.data('bs-target', '#' + this.uid);
        this.element.click((e) => {
            that.show();
        });

        /* Préparation de la modale bootstrap */
        that.modInstance = new bootstrap.Modal(that.getModalElement());

        that.element[0].addEventListener('show.bs.modal', () => {
            that.show(true);
        });

        /*that.contentDiv.on('DOMSubtreeModified', () => {
            if (that.contentDiv.find('.modal-title,.title,.page-header,.btn-save').length > 0) {

            }
        });*/

        that.contentDiv[0].addEventListener('intranavigator.refresh', (event) => {
            that.setContent(that.contentDiv.html());
            if (event.detail.isSubmit) {
                that.contentSubmit(that.contentDiv);
            }
        });

        that.element[0].addEventListener('hidden.bs.modal', () => {
            that.hide(true);
        });
    },



    makeDiv: function () {
        do {
            uid = Math.floor(Math.random() * 10000000)
        } while (document.getElementById('mod-ajax-' + uid))
        this.uid = 'mod-ajax-' + uid;

        this.modDiv = $('<div class="modal fade" id="' + this.uid + '" aria-hidden="true">\n' +
            '  <div class="modal-dialog">\n' +
            '    <div class="modal-content">\n' +
            '      <div class="modal-header">\n' +
            '        <h2 class="modal-title"></h2>\n' +
            '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>\n' +
            '      </div>\n' +
            '      <div class="modal-body intranavigator"></div>\n' +
            '      <div class="modal-footer">\n' +
            '           ' + this.options.closeButton + '\n' +
            '      </div>\n' +
            '    </div>\n' +
            '  </div>\n' +
            '</div>'
        )
        ;

        this.titleDiv = this.modDiv.find('.modal-title');
        this.contentDiv = this.modDiv.find('.modal-body');
        this.actionDiv = this.modDiv.find('.modal-footer');
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
            closeButton: 'close-button',
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
            url: that.options.url,
            success: (response) => {
                that.setContent(response);

                var first_input = that.contentDiv.find('input[type=text]:visible:enabled:first, textarea:visible:enabled:first, select:visible:enabled:first')[0];
                if (first_input != undefined) {
                    first_input.focus();
                }

                //that.contentSubmit(that.contentDiv);
            }
        });
    },



    setContent: function (content, loading) {
        var that = this;
        var ct = IntraNavigator.extractTitle(content);

        this.contentDiv.html(ct.content);

        this.contentDiv.find('.btn-save').each(function () {
            var oriBtn = $(this);
            var newBtn = oriBtn.clone();

            oriBtn.hide();
            newBtn.show();
            newBtn.on('click', function () {oriBtn.click()});

            that.actionDiv.html(that.options.closeButton);
            that.actionDiv.append(newBtn);
        });


        if (ct.title) {
            this.setTitle(ct.title);
        }

        if (loading !== true) {
            this._trigger('change', null, this);
        }
    },



    getContent: function () {
        return this.contentDiv.html();
    },



    setTitle: function (title) {
        this.titleDiv.html(title);
    },



    getTitle: function () {
        return this.titleDiv.html();
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
            this.modInstance.show();
        }
        this._trigger('show', null, this);
        setTimeout(() => {
            that.loading = false;
        }, 100);
    },



    hide: function (hidden) {
        if (hidden !== true) {
            this.modInstance.hide();
        }
        this.loading = true;
        this._trigger('hide', null, this);
    },



    shown: function () {
        return this.getModalElement().is(":visible");
    },



    hasErrors: function () {
        return IntraNavigator.hasErrors(this.contentDiv);
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
            c += '<button type="button" class="btn btn-secondary mod-ajax-hide">' + this.options.cancelButton + '</button>';
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
        var modEl = this.getModalElement();

        if (this.loading) return;
        if (!modEl || !modEl[0] || e.target == this.element[0]) return true;

        var p = modEl[0].getBoundingClientRect();
        var horsZoneMod = e.clientX < p.left || e.clientX > p.left + p.width || e.clientY < p.top || e.clientY > p.top + p.height;
        var horsElementFils = $(e.target).parents('.modal-dialog,.ui-autocomplete').length == 0;

        if ($(e.target).hasClass('mod-ajax-hide')) {
            this.hide();
        }

        if (horsZoneMod) {
            if (horsElementFils) { // il ne faut pas que l'élément soit dans la modale
                this.hide();
            }
        }
    },



    getModalElement: function () {
        if (this.modDiv === undefined) {
            this.modDiv = $('<div class="modal fade" aria-hidden="true">\n' +
                '  <div class="modal-dialog">\n' +
                '    <div class="modal-content">\n' +
                '      <div class="modal-header">\n' +
                '        <h2 class="modal-title">Modal title</h2>\n' +
                '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>\n' +
                '      </div>\n' +
                '      <div class="modal-body">Modal Body</div>\n' +
                '      <div class="modal-footer">\n' +
                '        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>\n' +
                '        <button type="button" class="btn btn-primary">Understood</button>\n' +
                '      </div>\n' +
                '    </div>\n' +
                '  </div>\n' +
                '</div>');
        }

        return this.modDiv;
    },
});



$(function () {
    WidgetInitializer.add('mod-ajax', 'modAjax');
});


function modAjax(element, onSubmit)
{
    var widget = $(element).data('unicaenModAjax');

    if (!element.classList.contains('no-intranavigation')) {
        element.classList.add('no-intranavigation');
    }

    if (!widget) {
        $(element).modAjax();
        widget = $(element).data('unicaenModAjax');
        if (onSubmit) {
            widget.options.submitEvent = onSubmit;
        }
        widget.show();
    }
    widget.element = $(element);
    if (element.dataset.url) {
        widget.options.url = element.dataset.url;
    }

    return widget;
}