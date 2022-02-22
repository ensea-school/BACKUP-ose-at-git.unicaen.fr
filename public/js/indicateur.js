$.widget("ose.indicateur", {

    _create: function ()
    {
        var that = this;

        this.getElementNotif().change( function(){ that.abonner(); });
        this.getElementInHome().change( function(){ that.abonner(); });

        this.element.find(".glyphicon.glyphicon-info-sign").tooltip();
    },


    abonner: function()
    {
        var that = this;
        var notif = this.getElementNotif();
        var inHome = this.getElementInHome();

        notif.data('previousValue', notif.val());
        notif.addClass("loading");
        $.ajax({
            type: 'POST',
            url: this.element.data('url'),
            data: {
                notification: notif.val(),
                'in-home': inHome.is(':checked') ? '1' : '0'
            },
            success: function (data, textStatus, jqXHR)
            {
                if (data.status !== "success") {
                    notif.val(notif.data('previousValue'));
                }
                notif.removeClass("loading");
                var infos = $(".indicateur-info", notif.parent()).attr('title', data.infos).tooltip('destroy').tooltip();
                notif.val() ? infos.show() : infos.hide();
                alertFlash(data.message, data.status, 5000);
            }
        });
    },

    getElementNotif: function(){ return this.element.find('select.notif'); },
    getElementInHome: function(){ return this.element.find('input.in-home'); }

});

