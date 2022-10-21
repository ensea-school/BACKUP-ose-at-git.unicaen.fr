$.widget("unicaen.popAjax", {

    popDiv: undefined, loading: false, contentGuid: null, container: undefined,

    options: {
        url: undefined,
        confirm: undefined,
        confirmButton: '<i class="fas fa-check"></i> OK',
        cancelButton: '<i class="fas fa-xmark"></i> Annuler',
        submitEvent: undefined,
        submitClose: false,
        submitReload: false,
        maxWidth: '600px',
        maxHeight: 'none',
        loadingTitle: 'Chargement...',
        loadingContent: '<div class="loading"></div>',
    },



    _create: function () {
        var that = this;

        /* Traitement des options de configuration */
        var optionsKeys = {
            url: 'url',
            confirm: 'confirm',
            confirmButton: 'confirm-button',
            cancelButton: 'cancel-button',
            submitEvent: 'submit-event',
            submitClose: 'submit-close',
            submitReload: 'submit-reload',
            maxWidth: 'max-width',
            maxHeight: 'max-height',
            loadingTitle: 'loading-title',
            loadingContent: 'loading-content',
        };

        for (var k in optionsKeys) {
            if (typeof this.element.data(optionsKeys[k]) !== 'undefined') {
                this.options[k] = this.element.data(optionsKeys[k]);
            }
        }

        if (href = this.element.attr('href')) {
            this.options.url = href;
        }

        /* Récupération des événements divers */
        $('html').click(function (e) {
            that.htmlClick(e);
        });
        $("body").on('intranavigator-refresh', function (event, args) {
            if (that && that.getContentElement() && $(args.element).parents(that.getContentElement()).length > 0) {
                that.contentChange(args.isSubmit);
            }
        });

        /* Préparation du popover */
        popoptions = {"html": true, "sanitize": false};
        if (!that.element.data('bs-content')) {
            popoptions.title = that.options.loadingTitle;
            popoptions.content = function () {
                return that.makeContent();
            };
        }

        that.element.popover(popoptions);
    },



    makeContent: function () {
        var that = this;

        var out = '<div class="intranavigator">';
        if (that.options.confirm !== undefined) {
            out += that.makeConfirmBox(that.options.confirm);
        } else {
            $.ajax({
                url: that.options.url, success: function (response) {
                    that.setContent(response);
                }
            });
            out += that.options.loadingContent;
        }
        out += '</div>';

        return out;
    },



    setContent: function (content) {
        var contentElement = this.getContentElement();

        contentElement.html(content);
        this.contentChange(false);

    },



    contentChange: function (isSubmit) {
        var contentElement = this.getContentElement();

        /* Mise à jour éventuelle du titre du popajax */
        var extractedTitle = contentElement.find('.popover-title,.page-header');
        if (extractedTitle.length > 0) {
            this.setTitle(extractedTitle.html());
            extractedTitle.remove();
        } else if ($(this.element).data('bs-title')) {
            this.setTitle($(this.element).data('bs-title'));
        } else {
            this.setTitle(null);
        }

        /* Gestion des événements */
        if (isSubmit && !this.errorsInContent()) {
            if (this.options.submitEvent) {
                $("body").trigger(this.options.submitEvent, this);
            }
            if (this.options.submitClose) {
                $(this.element).popover('hide');
            }
            if (this.options.submitReload) {
                window.location.reload();
            }

            // this._trigger('submit', null, this); // @todo
        }
        //this._trigger('change', null, this); // @todo
    },



    makeConfirmBox: function (content) {
        var c = '<form action="' + this.options.url + '" method="post">' + content + '<div class="btn-goup" style="text-align:right;padding-top: 10px" role="group">';
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



    setTitle: function (title) { // @todo gérer les NULL
        if (title) {
            this.getPopoverElement().find('.popover-header').html(title);
            this.getPopoverElement().find('.popover-header').show();
        } else {
            this.getPopoverElement().find('.popover-header').hide();
        }
    },



    errorsInContent: function () {
        var ce = this.getContentElement();

        if (!ce) return false;

        var errs = ce.find('.input-error, .has-error, .has-errors, .alert.alert-danger').length;

        return errs > 0;
    },



    htmlClick: function (e) {
        var popEl = this.getPopoverElement();

        if ($(e.target).hasClass('pop-ajax-hide')) {
            $(this.element).popover('hide');
        }

        if (!popEl || !popEl[0] || e.target == this.element[0]) return true;

        var p = popEl[0].getBoundingClientRect();
        var horsZonePop = e.clientX < p.left || e.clientX > p.left + p.width || e.clientY < p.top || e.clientY > p.top + p.height;
        var horsElementFils = $(e.target).parents('.popover-content,.ui-autocomplete').length == 0;

        if (horsZonePop) {
            if (horsElementFils) { // il ne faut pas que l'élément soit dans le popover
                $(this.element).popover('hide');
            }
        }
    },



    getContentElement: function () {
        return this.getPopoverElement().find('popover-body');
    },



    getPopoverElement: function () {
        var id = $(this.element).attr('aria-describedby');

        if (!id) {
            return undefined;
        }
        return $('#' + id);
    },
});





/**
 *
 * @constructor
 */
$.widget("unicaen.popAjaxOld", {

    popDiv: undefined, inChange: false,

    options: {
        url: undefined,
        content: undefined,
        confirm: false,
        confirmButton: '<i class="fas fa-check"></i> OK',
        cancelButton: '<i class="fas fa-xmark"></i> Annuler',
        animation: true,
        delay: 200,
        placement: 'auto',
        submitEvent: undefined,
        submitClose: false,
        submitReload: false,
        minWidth: '100px',
        maxWidth: '600px',
        minHeight: '50px',
        maxHeight: 'none',
        loadingTitle: 'Chargement...',
        loadingContent: '<div class="loading"></div>',
        title: undefined,
        autoShow: false
    },



    _create: function () {
        var that = this;


        this.element.click(function () {
            that.showHide();

            return false;
        });

        $('html').click(function (e) {
            that.htmlClick(e);
        });

        $("body").on('intranavigator-refresh', function (event, args) {
            if (that && that.popDiv && $(args.element).parents(that.popDiv).length > 0) {
                that.afterRefresh(args.isSubmit);
            }
        });

        this.initOptions();
        var attr = this.element.attr('href');
        if (typeof attr !== typeof undefined && attr !== false) {
            this.options.url = this.element.attr('href');
        }

    },



    initOptions: function () {
        var optionsKeys = {
            url: 'url',
            content: 'content',
            confirm: 'confirm',
            confirmButton: 'confirm-button',
            cancelButton: 'cancel-button',
            animation: 'animation',
            delay: 'delay',
            placement: 'placement',
            submitEvent: 'submit-event',
            submitClose: 'submit-close',
            submitReload: 'submit-reload',
            minWidth: 'min-width',
            maxWidth: 'max-width',
            minHeight: 'min-height',
            maxHeight: 'max-height',
            loadingTitle: 'loading-title',
            loadingContent: 'loading-content',
            title: 'title',
            autoShow: 'auto-show'
        };

        for (var k in optionsKeys) {
            if (typeof this.element.data(optionsKeys[k]) !== 'undefined') {
                this.options[k] = this.element.data(optionsKeys[k]);
            }
        }
    },



    show: function () {
        this.inChange = true;
        if (this.options.animation) {
            this.makePopDiv().fadeIn(this.options.delay);
        } else {
            this.makePopDiv().show();
        }
        this.inChange = false;
        //this.posPop();

        this._trigger('show', null, this);

        return this;
    },



    afterRefresh: function (isSubmit) {
        this.extractTitle(); // on rafraichit le titre, éventuellement
        if (isSubmit && !this.errorsInContent()) {
            if (this.options.submitEvent) {
                $("body").trigger(this.options.submitEvent, this);
            }
            if (this.options.submitClose) {
                this.hide();
            }
            if (this.options.submitReload) {
                window.location.reload();
            }

            this._trigger('submit', null, this);
        }
        this._trigger('change', null, this);
    },



    errorsInContent: function () {
        var that = this;

        if (!this.popDiv) return false;

        var errs = this.popDiv.find('.popover-content')
            .find('.input-error, .has-error, .has-errors, .alert.alert-danger').length;

        return errs > 0;
    },



    makeConfirmBox: function (content) {
        var c = '<form action="' + this.options.url + '" method="post">' + content + '<div class="btn-goup" style="text-align:right;padding-top: 10px" role="group">';
        if (this.options.cancelButton) {
            c += '<button type="button" class="btn btn-default pop-ajax-hide">' + this.options.cancelButton + '</button>';
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



    makePopDiv: function () {
        var that = this;

        if (this.options.content !== undefined) {
            var title = this.options.title;
            var content = this.options.content;
            if (this.options.confirm) {
                content = this.makeConfirmBox(content);
            }
        } else {
            var title = this.options.loadingTitle;
            var content = this.options.loadingContent;
        }

        if (undefined == this.popDiv) {
            this.popDiv = $('<div></div>');
            this.popDiv.addClass('popover pop-ajax-div');
            this.popDiv.css({
                'min-width': this.options.minWidth,
                'max-width': this.options.maxWidth,
                'min-height': this.options.minHeight,
                'max-height': this.options.maxHeight,
                'position': 'absolute',
                'left': '-80000px',
                'top': '-80000px'
            });

            var contentDiv = '<div class="arrow"></div>';
            if (title) {
                contentDiv += '<h3 class="popover-title">' + title + '</h3>';
            } else {
                contentDiv += '<h3 class="popover-title" style="display:none"></h3>';
            }
            contentDiv += '<div class="popover-content intranavigator">' + content + '</div>';

            this.popDiv.html(contentDiv);
            this.popDiv.appendTo("body");
            this.popDiv.find('.pop-ajax-hide').click(function () { that.hide();});
            IntraNavigator.run(); // navigateur interne!!

            if (this.options.content !== undefined) {
                this._trigger('change', null, this);
                //this.posPop();
            } else {
                $.get(this.options.url)
                    .done(function (res) {
                        if (that.options.confirm) {
                            res = that.makeConfirmBox(res);
                        }
                        that.populate(res);
                    })
                    .fail(function (err) {
                        msg = '<div class="alert alert-danger">Erreur ' + err.status + ' : ' + err.statusText + '</div>';

                        that.populate(msg + "\n" + err.responseText);
                    });
            }

            this.getContent().bind('DOMNodeInserted DOMNodeRemoved', function () {
                //that.posPop();
            });
        }

        return this.popDiv;
    },



    populate: function (content) {
        var that = this;
        var pc = this.getContent();

        this.inChange = true;

        if (pc) {
            pc.hide();

            pc.html(content);
            pc.find('.pop-ajax-hide').click(function () { that.hide();});
            this.extractTitle();
            this._trigger('change', null, this);

            pc.show();
        }
        this.inChange = false;
        //this.posPop();
    },

});

$(function () {
    WidgetInitializer.add('pop-ajax', 'popAjax');
});