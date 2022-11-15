/**
 * TabAjax
 */
$.widget("unicaen.tabAjax", {

    /**
     * Permet de retourner un onglet, y compris à partir de son ID
     *
     * @param string|a tab
     * @returns {*}
     */
    getTab: function (tab)
    {
        if (typeof tab === 'string') {
            return this.element.find('.nav-tabs a[aria-controls="' + tab + '"]');
        } else {
            return tab; // par défaut on présuppose que le lien "a" a été transmis!!
        }
    },

    getIsLoaded: function (tab)
    {
        tab = this.getTab(tab);
        return tab.data('is-loaded') == '1';
    },

    setIsLoaded: function (tab, isLoaded)
    {
        tab = this.getTab(tab);
        tab.data('is-loaded', isLoaded ? '1' : '0');

        this._trigger('loaded', null, tab);

        return this;
    },

    getForceRefresh: function (tab)
    {
        return this.getTab(tab).data('force-refresh') ? true : false;
    },

    setForceRefresh: function (tab, forceRefresh)
    {
        this.getTab(tab).data('force-refresh', forceRefresh);
        return this;
    },

    select: function (tab)
    {
        var that = this;

        tab = this.getTab(tab);
        if (tab.attr('href')[0] !== '#' && (!this.getIsLoaded(tab) || this.getForceRefresh(tab))) {
            var loadurl = tab.attr('href'),
                tid = tab.data('bs-target');

            that.element.find(".tab-pane" + tid).html("<div class=\"loading\">&nbsp;</div>");
            IntraNavigator.add(that.element.find(".tab-pane" + tid));
            $.get(loadurl, function (data)
            {
                that.element.find(".tab-pane" + tid).html(data);
                that.setIsLoaded(tab, true);
            });
        }
        tab.tab('show');
        this._trigger("change");
        return this;
    },

    getContent: function (tab)
    {
        var that = this;

        tab = this.getTab(tab);
        tid = tab.data('bs-target');
        return that.element.find(".tab-pane" + tid).html();
    },

    getSelected: function ()
    {
        var sel = this.element.find('.tab-pane.active').attr('id');

        return sel;
    },

    _create: function ()
    {
        var that = this;
        this.element.find('.nav-tabs a').on('click', function (e)
        {
            e.preventDefault();
            that.select($(this));
            return false;
        });
        if (!that.getContent(that.getSelected())) {
            that.select(that.getSelected());
        }
    },

});

$(function ()
{
    WidgetInitializer.add('tab-ajax', 'tabAjax');
});