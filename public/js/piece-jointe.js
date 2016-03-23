/**
 * pieceJointe
 */
$.widget("ose.pieceJointe", {

    _create: function ()
    {
        var that = this;

        that.element.on('click', '.valider-pj, .devalider-pj', function(event){
            var a = $(this);
            var tpj = a.parents('.tpj').data('tpj');

            a.button('loading');
            $.post(a.prop('href'), [], function (data, textStatus, jqXHR)
            {
                var container = that.getContainer(tpj);
                container.find('.validation-bar').html(data);
                container.removeClass('panel-default');
                container.removeClass('panel-success');

                var isValider = data.indexOf("/valider/") !== -1;

                if (isValider){
                    container.addClass('panel-default');
                }else{
                    container.addClass('panel-success');
                }

                that.onValidationChange( tpj, isValider );
            });
            event.preventDefault();
        });

        $("body").on("upload-event-file-deleted upload-event-file-uploaded", function (event, container)
        {
            var tpj = container.data('tpj');
            that.onFileChange( tpj );
        });

    },



    onValidationChange: function( tpj, isValider )
    {
        this.refreshFiles(tpj, isValider);
        this.refreshInfos();
    },



    onFileChange: function( tpj )
    {
        var toutFourni;

        this.refreshContainerValidationBar(tpj);
        this.refreshInfos();

        toutFourni = this.isToutFourni();
        if (true === toutFourni) this.onToutFourni();
        if (false === toutFourni) this.onPasToutFourni();
    },



    onToutFourni: function()
    {
        this.element.find('#alert-contrat').fadeIn(200);
    },



    onPasToutFourni: function()
    {
        this.element.find('#alert-contrat').fadeOut(200);
    },



    isToutFourni: function()
    {
        var countObligatoires = 0;
        var countFournies = 0;

        this.element.find('.tpj-obligatoire').each( function(){
            var nbFichiers = $(this).find('.download-file').length;

            countObligatoires++;
            if (nbFichiers > 0) countFournies++;
        });

        if (countObligatoires == 0){
            return null;
        } else {
            return countFournies == countObligatoires;
        }
    },



    refreshInfos: function ()
    {
        this.element.find('.infos').refresh();
    },



    refreshContainerValidationBar: function (tpj)
    {
        this.getContainer(tpj).find('.validation-bar').refresh();
    },



    refreshFiles: function(tpj, isValider)
    {
        this.getContainer(tpj).find('.uploaded-files-div').refresh();
        if (isValider){
            this.getContainer(tpj).find('#upload-form').show();
        }else{
            this.getContainer(tpj).find('#upload-form').hide();
        }
    },



    getContainer: function (tpj) { return this.element.find('.tpj.tpj-' + tpj); },

});

$(function ()
{
    WidgetInitializer.add('piece-jointe', 'pieceJointe');
});