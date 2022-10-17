/**
 *
 * @constructor
 */
$.widget("unicaen.popAjax", {

    popDiv: undefined,
    inChange: false,

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



    _create: function ()
    {
        var that = this;


        this.element.click(function ()
        {
            that.showHide();

            return false;
        });

        $('html').click(function (e)
        {
            that.htmlClick(e);
        });

        $("body").on('intranavigator-refresh', function (event, args)
        {
            if (that && that.popDiv && $(args.element).parents(that.popDiv).length > 0) {
                that.afterRefresh(args.isSubmit);
            }
        });

        this.initOptions();
        var attr = this.element.attr('href');
        if (typeof attr !== typeof undefined && attr !== false) {
            this.options.url = this.element.attr('href');
        }

        if (this.options.autoShow) {
            setTimeout(function () { that.show(); }, 100);
        }
    },



    initOptions: function ()
    {
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



    showHide: function ()
    {
        if (this.shown()) {
            this.hide();
        } else {
            this.show();
        }
        return this;
    },



    htmlClick: function (e)
    {
        if (!this.popDiv) return true;

        var p = this.popDiv[0].getBoundingClientRect();

        var horsZonePop = e.clientX < p.left || e.clientX > p.left + p.width
            || e.clientY < p.top || e.clientY > p.top + p.height;

        var horsElementFils = $(e.target).parents('.popover-content,.ui-autocomplete').length == 0;

        if (horsZonePop) {
            if (horsElementFils) { // il ne faut pas que l'élément soit dans le popover
                this.hide();
            }
        }
    },



    shown: function ()
    {
        return this.popDiv != undefined;
    },



    show: function ()
    {
        this.inChange = true;
        if (this.options.animation) {
            this.makePopDiv().fadeIn(this.options.delay);
        } else {
            this.makePopDiv().show();
        }
        this.inChange = false;
        this.posPop();

        this._trigger('show', null, this);

        return this;
    },



    hide: function ()
    {
        if (this.popDiv) {
            if (this.options.animation) {
                this.popDiv.fadeOut(this.options.delay, function () { $(this).remove(); });
            } else {
                this.popDiv.hide();
                this.popDiv.remove();
            }

            this.popDiv = undefined;
        }

        this._trigger('hide', null, this);

        return this;
    },



    afterRefresh: function (isSubmit)
    {
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



    errorsInContent: function ()
    {
        var that = this;

        if (!this.popDiv) return false;

        var errs = this.popDiv.find('.popover-content')
            .find('.input-error, .has-error, .has-errors, .alert.alert-danger').length;

        return errs > 0;
    },



    makeConfirmBox: function (content)
    {
        var c = '<form action="' + this.options.url + '" method="post">' + content +
            '<div class="btn-goup" style="text-align:right;padding-top: 10px" role="group">';
        if (this.options.cancelButton) {
            c += '<button type="button" class="btn btn-default pop-ajax-hide">' + this.options.cancelButton + '</button>';
        }
        if (this.options.confirmButton && this.options.cancelButton) {
            c += '&nbsp;';
        }
        if (this.options.confirmButton) {
            c += '<button type="submit" class="btn btn-primary">' + this.options.confirmButton + '</button>';
        }
        c += '</div>' +
            '</form>';

        return c;
    },



    makePopDiv: function ()
    {
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
                this.posPop();
            } else {
                $.get(this.options.url)
                    .done(function (res)
                    {
                        if (that.options.confirm) {
                            res = that.makeConfirmBox(res);
                        }
                        that.populate(res);
                    })
                    .fail(function (err)
                    {
                        msg = '<div class="alert alert-danger">Erreur ' + err.status + ' : ' + err.statusText + '</div>';

                        that.populate(msg + "\n" + err.responseText);
                    });
            }

            this.getContent().bind('DOMNodeInserted DOMNodeRemoved', function ()
            {
                that.posPop();
            });
        }

        return this.popDiv;
    },



    populate: function (content)
    {
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
        this.posPop();
        this.posPop();
        this.posPop(); // on répête 3 fois car il est un peu dûr d'oreille...
    },



    extractTitle: function ()
    {
        var pc = this.getContent();

        var title = pc.find('h1,.popover-title,.page-header');

        if (title.length > 0) {
            this.popDiv.find('.popover-title').html(title.html()).show();
            title.remove();
        } else if (this.options.title) {
            this.popDiv.find('.popover-title').html(this.options.title).show();
        } else {
            this.popDiv.find('.popover-title').hide();
        }
    },



    posPop: function ()
    {
        if (this.inChange) return;
        if (!this.popDiv) return;

        /* Position de l'élément qui ouvre le popover */
        var aPos = this.element[0].getBoundingClientRect();

        /* Espace d'affichage */
        var doc = {
            left: $(window).scrollLeft(),
            top: $(window).scrollTop(),
            width: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
            height: window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
        };

        /* position du popover */
        var pop = {
            left: 0,
            top: 0,
            width: this.popDiv.width(),
            height: this.popDiv.height()
        };

        var placement = this.options.placement;

        if (placement == 'auto') {
            if (aPos.right + pop.width <= doc.width - 2) placement = 'right';
            if ((aPos.left - pop.width >= 2) && (aPos.left + (aPos.width / 2) < (doc.width / 2))) placement = 'left';
            if (aPos.top - pop.height >= 2) placement = 'top';
            if ((aPos.bottom + pop.height <= doc.height - 2) && (aPos.top + (aPos.height / 2) < (doc.height / 2))) placement = 'bottom';
        }

        this.popDiv.removeClass('bottom');
        this.popDiv.removeClass('top');
        this.popDiv.removeClass('left');
        this.popDiv.removeClass('right');
        this.popDiv.addClass(placement);
        switch (placement) {
            case 'bottom':
                pop.left = aPos.left + (aPos.width / 2) - (pop.width / 2);
                pop.top = aPos.bottom;
                break;
            case 'top':
                pop.left = aPos.left + (aPos.width / 2) - (pop.width / 2);
                pop.top = aPos.top - pop.height;
                break;
            case 'left':
                pop.left = aPos.left - pop.width;
                pop.top = aPos.top + (aPos.height / 2) - (pop.height / 2);
                break;
            case 'right':
                pop.left = aPos.right;
                pop.top = aPos.top + (aPos.height / 2) - (pop.height / 2);
                break;
        }

        if (pop.left + pop.width > doc.width - 2) pop.left = doc.width - 2 - pop.width;
        if (pop.top + pop.height > doc.height - 2) pop.top = doc.height - 2 - pop.height;

        if (pop.left < 2) pop.left = 2;
        if (pop.top < 2) pop.top = 2;

        this.popDiv.css({left: doc.left + pop.left, top: doc.top + pop.top});

        switch (placement) {
            case 'bottom':
            case 'top':
                var l = pop.left > aPos.left ? pop.left : aPos.left;
                var r = (pop.left + pop.width) < (aPos.right) ? (pop.left + pop.width) : aPos.right;

                var pos = ((r - l) / 2) + l - pop.left;
                if (pos < 20) pos = 20;
                if (pos > (pop.width - 20)) pos = pop.width - 20;

                this.popDiv.find('.arrow').css({left: pos});
                break;
            case 'left':
            case 'right':
                var t = pop.top > aPos.top ? pop.top : aPos.top;
                var h = (pop.top + pop.height) < aPos.bottom ? (pop.top + pop.height) : aPos.bottom;

                var pos = ((h - t) / 2) + t - pop.top;
                if (pos < 20) pos = 20;
                if (pos > (pop.height - 20)) pos = pop.height - 20;

                this.popDiv.find('.arrow').css({top: pos});
                break;
        }
        return this;
    },



    getContent: function ()
    {
        if (!this.popDiv) return undefined;
        return this.popDiv.find('.popover-content');
    },



    setContent: function (content)
    {
        this.options.content = content;
        return this;
    }

});

$(function ()
{
    WidgetInitializer.add('pop-ajax', 'popAjax');
});