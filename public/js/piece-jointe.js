/**
 * pieceJointe
 */
$.widget("ose.pieceJointe", {

    _create: function ()
    {
        var that = this;

        that.element.on('click', '.valider-pj, .devalider-pj', function(event){
            that.validerDevalider( $(this) );
            event.preventDefault();
        });

        that.element.on('click', '.archiver-pj', function (event){
            that.archiver($(this));
            event.preventDefault();


        });

        $("body").on("upload-event-file-deleted upload-event-file-uploaded", function (event, container)
        {
            var tpj = container.data('tpj');
            that.onFileChange( tpj );
        });

    },

    archiver: function( element)
    {
        var that = this;
        var tpj = element.parent('.tpj').data('tpj');
        element.button('loading');
        $.ajax({
            type: 'POST',
            url: element.prop('href'),
            data: {},
            success: function (data, textStatus, jqXHR) {
                that.onFileChange(tpj);
            },
            error: function (jqXHR) {
                alert('Une erreur est survenue. L\'opération n\'a pas pu être effectuée.');
                console.log(jqXHR);
            },
            complete: function (jqXHR) {
                location.reload(true);
            }
        });
    },

    validerDevalider: function( element )
    {
        var that = this;
        var tpj = element.parents('.tpj').data('tpj');

        element.button('loading');

        $.ajax({
            type: 'POST',
            url: element.prop('href'),
            data: {},
            success: function (data, textStatus, jqXHR) {
                var container = that.getContainer(tpj);
                container.find('.validation-bar').html(data);
                container.removeClass('panel-default');
                container.removeClass('panel-success');

                var isValider = data.indexOf("/valider/") !== -1;

                if (isValider) {
                    container.addClass('panel-default');
                } else {
                    container.addClass('panel-success');
                }

                that.onValidationChange(tpj, isValider);
            },
            error: function (jqXHR) {
                alert('Une erreur est survenue. L\'opération n\'a pas pu être effectuée.');
                console.log(jqXHR);
            },
        });
    },



    onValidationChange: function( tpj, isValider )
    {
        this.refreshFiles(tpj, isValider);
        this.refreshInfos();
        this._trigger('validation-change', null, this);
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