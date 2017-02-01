$.widget("ose.diagramme", {
    noeuds: {},
    liens: {},
    diagramme: undefined,
    formNoeud: undefined,
    formLien: undefined,
    mousePosEvent: undefined,
    editionNoeudId: undefined,
    editionLienId: undefined,



    _create: function ()
    {
        var that = this;
        this.noeuds = this.element.data('noeuds');
        this.liens = this.element.data('liens');

        this.diagramme = this.__makeGraph();

        this.formNoeud = this.element.find(".form-noeud");
        this.formNoeud.dialog({
            autoOpen: false,
            hide: { effect: 'clip', duration: 200 },
            show: { effect: 'clip', duration: 200 },
            title: 'Édition',
            width: 400
        });
        this.formNoeud.css('display:block');

        this.formLien = this.element.find(".form-lien");
        this.formLien.dialog({
            autoOpen: false,
            hide: { effect: 'clip', duration: 200 },
            show: { effect: 'clip', duration: 200 },
            title: 'Édition',
            width: 280
        });
        this.formLien.css('display:block');

        $(document).mousemove(function (event)
        {
            that.mousePosEvent = event;
        });

        this.element.find('.controles .zplus').click(function () { that.zoomPlus(); });
        this.element.find('.controles .zmoins').click(function () { that.zoomMoins(); });
        this.element.find('.controles .zdefaut').click(function () { that.zoomDefaut(); });
        this.element.find('.controles .fullscreen').change(function () { that.fullScreen(); });
        this.element.find('.controles .sauvegarder').click(function () { that.sauvegarder(); });

        this.diagramme.addDiagramListener("ObjectSingleClicked", function (e){
            var part = e.subject.part;

            if (part instanceof go.Link) {
                that.editionLien(part.data.id);
            } else {
                that.editionNoeud(part.data.id);
            }
        });

        this.getFormNoeudBtnCancel().click(function () { that.formNoeud.dialog('close'); that.diagramme.clearSelection();});
        this.getFormNoeudBtnSave().click(function ()
        {
            that.applicationEditionNoeud();
            that.formNoeud.dialog("close");
            that.diagramme.clearSelection();
        });

        this.getFormLienBtnCancel().click(function () { that.formLien.dialog('close'); that.diagramme.clearSelection();});
        this.getFormLienBtnSave().click(function ()
        {
            that.applicationEditionLien();
            that.formLien.dialog("close");
            that.diagramme.clearSelection();
        });
    },



    zoomPlus: function ()
    {
        this.diagramme.commandHandler.increaseZoom(1.2);
        return this;
    },



    zoomMoins: function ()
    {
        this.diagramme.commandHandler.increaseZoom(0.8);
        return this;
    },



    zoomDefaut: function ()
    {
        this.diagramme.commandHandler.resetZoom();
        return this;
    },



    editionNoeud: function (noeudId)
    {
        this.editionNoeudId = noeudId;

        this.formNoeud.find('#choix-minimum').val( this.noeuds[noeudId]['choix-minimum'] );
        this.formNoeud.find('#choix-maximum').val( this.noeuds[noeudId]['choix-maximum'] );
        this.formNoeud.find('#assiduite').val( this.noeuds[noeudId]['assiduite'] * 100 );

        for( var tid in this.element.data('type-heures')){
            var val = this.noeuds[noeudId]['effectifs'][tid];
            if (val === undefined) val = 0;
            this.formNoeud.find('#effectifs-'+tid).val( val );
        }
        for( var tid in this.element.data('type-intervention')){
            var val = this.noeuds[noeudId]['seuils-ouverture'][tid];
            if (val === undefined) val = 0;
            this.formNoeud.find('#seuil-ouverture-'+tid).val( val );

            var val = this.noeuds[noeudId]['seuils-dedoublement'][tid];
            if (val === undefined) val = 0;
            this.formNoeud.find('#seuil-dedoublement-'+tid).val( val );
        }

        if (this.noeuds[noeudId]['etape']){
            this.formNoeud.find('#effectifs').show();
        }else{
            this.formNoeud.find('#effectifs').hide();
        }

        this.formNoeud.dialog({
            position: {
                my: "center center",
                of: this.mousePosEvent
            },
            title: this.noeuds[noeudId].libelle + ' (' + this.noeuds[noeudId].code + ')'
        });

        this.formNoeud.dialog("open");
    },



    applicationEditionNoeud: function ()
    {
        this.noeuds[this.editionNoeudId]['choix-minimum'] = parseInt(this.formNoeud.find('#choix-minimum').val());
        this.noeuds[this.editionNoeudId]['choix-maximum'] = parseInt(this.formNoeud.find('#choix-maximum').val());
        this.noeuds[this.editionNoeudId]['assiduite'] = parseInt(this.formNoeud.find('#assiduite').val()) / 100;
        for( var tid in this.element.data('type-heures')){
            this.noeuds[this.editionNoeudId]['effectifs'][tid] = parseInt(this.formNoeud.find('#effectifs-'+tid).val());
        }
        for( var tid in this.element.data('type-intervention')){
            this.noeuds[this.editionNoeudId]['seuils-ouverture'][tid] = parseInt(this.formNoeud.find('#seuil-ouverture-'+tid).val());
            this.noeuds[this.editionNoeudId]['seuils-dedoublement'][tid] = parseInt(this.formNoeud.find('#seuil-dedoublement-'+tid).val());
        }
        this.majNoeud(this.editionNoeudId);

        return this;
    },



    majNoeud: function (noeudId)
    {
        var model = this.diagramme.model;

        model.startTransaction("majNoeud");
        for (i in model.nodeDataArray) {
            data = model.nodeDataArray[i];
            if (data.key == noeudId) {
                model.setDataProperty(data, 'choix', this.__dataToGraph(noeudId, 'choix'));
                model.setDataProperty(data, 'groupes', this.__dataToGraph(noeudId, 'groupes'));
                model.setDataProperty(data, 'assiduite', this.__dataToGraph(noeudId, 'assiduite'));
                model.setDataProperty(data, 'effectifs', this.__dataToGraph(noeudId, 'effectifs'));
            }
        }
        model.commitTransaction("majNoeud");
        return this;
    },



    editionLien: function (lienId)
    {
        this.editionLienId = lienId;

        this.formLien.find('#actif').prop('checked', this.liens[lienId]['actif'] );
        this.formLien.find('#poids').val( this.liens[lienId]['poids'] );

        this.formLien.dialog({
            position: {
                my: "center center",
                of: this.mousePosEvent
            }
        });

        this.formLien.dialog("open");
    },



    applicationEditionLien: function ()
    {
        this.liens[this.editionLienId].actif = this.formLien.find('#actif').is(':checked');
        this.liens[this.editionLienId].poids = parseFloat(this.formLien.find('#poids').val());

        this.majLien(this.editionLienId);

        return this;
    },



    majLien: function (lienId)
    {
        var model = this.diagramme.model;

        model.startTransaction("majLien");
        for (i in model.linkDataArray) {
            data = model.linkDataArray[i];
            if (data.id == lienId) {
                model.setDataProperty(data, 'actif', this.liens[lienId].actif);
                model.setDataProperty(data, 'poids', this.liens[lienId].poids);
                model.setDataProperty(data, 'category', (this.liens[lienId].actif) ? 'actif' : 'non-actif');
            }
        }
        model.commitTransaction("majLien");
        return this;
    },



    sauvegarder: function ()
    {

        return this;
    },



    isFullScreen: function ()
    {
        return this.element.hasClass('fullscreen');
    },



    fullScreen: function ()
    {
        if (this.isFullScreen()) {
            this.element.removeClass('fullscreen');
            var maxHeight = window.innerHeight - this.element.find('.dessin').offset().top;
            var height = this.diagramme.documentBounds.height + 20;
            if (height > maxHeight) height = maxHeight;
        } else {
            this.element.addClass('fullscreen');
            var maxHeight = window.innerHeight - this.element.find('.dessin').offset().top;
            var height = maxHeight;
        }

        this.element.find('.dessin').css('height', height);
        this.diagramme.requestUpdate();

        return this;
    },

    getFormNoeudBtnCancel: function () { return this.formNoeud.find('#btn-cancel')},
    getFormNoeudBtnSave: function () { return this.formNoeud.find('#btn-save')},
    getFormLienBtnCancel: function () { return this.formLien.find('#btn-cancel')},
    getFormLienBtnSave: function () { return this.formLien.find('#btn-save')},



    __makeGraph: function ()
    {
        var that = this;
        var $ = go.GraphObject.make;
        var d =
            $(go.Diagram, this.element.find('.dessin').attr('id'),
                {
                    initialContentAlignment: go.Spot.Top,
                    initialDocumentSpot: go.Spot.TopCenter,
                    initialViewportSpot: go.Spot.TopCenter,
                    /*    initialAutoScale: go.Diagram.UniformToFill,*/
                    isReadOnly: true,
                    maxSelectionCount: 1,
                    layout: $(go.LayeredDigraphLayout, {
                        direction: 90,
                        layerSpacing: 5,
                        columnSpacing: 1
                    }),
                    InitialLayoutCompleted: function (e)
                    {
                        var maxHeight = window.innerHeight - that.element.find('.dessin').offset().top;
                        var dia = e.diagram;
                        var height = dia.documentBounds.height + 20;

                        if (height > maxHeight) height = maxHeight;

                        dia.div.style.height = height + "px";
                    }
                }
            );

        var defaultNodeTemplate = $(go.Node, "Vertical",
            $(go.Panel, "Auto",
                {name: 'panel', width: 110, height: 105},
                $(go.Shape, "RoundedRectangle", {fill: "#eee", name: "SHAPE", stroke: '#bbb'}),
                $(go.Panel, "Vertical",
                    {padding: 10},
                    $(go.TextBlock, new go.Binding("text", "code")),
                    $(go.TextBlock,
                        {
                            column: 0,
                            margin: 1,
                            width: 100,
                            height: 28,
                            isMultiline: true,
                            maxLines: 3,
                            stroke: "black",
                            textAlign: "center",
                            font: "9pt \"Open Sans\""
                        },
                        new go.Binding("text", "libelle")
                    ),
                    $(go.Panel, "Table",
                        {
                            name: "details",
                            defaultAlignment: go.Spot.Left,
                            background: "white",
                            padding: 1.5,
                            visible: true
                        },
                        $(go.RowColumnDefinition, {column: 0, width: 70}),
                        $(go.RowColumnDefinition, {column: 1, width: 30, minimum: 30}),

                        $(go.Panel, "TableRow", {row: 0},
                            $(go.TextBlock, "Choix", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "choix"), {column: 1, font: "8pt \"Open Sans\""})
                        ),
                        $(go.Panel, "TableRow", {row: 1},
                            $(go.TextBlock, "Assiduité", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "assiduite"), {column: 1, font: "8pt \"Open Sans\""})
                        ),
                        $(go.Panel, "TableRow", {row: 2},
                            $(go.TextBlock, "Effectifs", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "effectifs"), {column: 1, font: "8pt \"Open Sans\""})
                        ),
                        $(go.Panel, "TableRow", {row: 3},
                            $(go.TextBlock, "Groupes", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "groupes"), {column: 1, font: "8pt \"Open Sans\""})
                        )
                    )
                )
            )
        );

        var etapeNodeTemplate = $(go.Node, "Vertical",
            $(go.Panel, "Auto",
                {name: 'panel', width: 110, height: 105},
                $(go.Shape, "RoundedRectangle", {fill: "#FFF6C6", name: "SHAPE", stroke: '#C7AE24'}),
                $(go.Panel, "Vertical",
                    {padding: 10},
                    $(go.TextBlock, new go.Binding("text", "code")),
                    $(go.TextBlock,
                        {
                            column: 0,
                            margin: 1,
                            width: 100,
                            height: 28,
                            isMultiline: true,
                            maxLines: 3,
                            stroke: "black",
                            textAlign: "center",
                            font: "9pt \"Open Sans\""
                        },
                        new go.Binding("text", "libelle")
                    ),
                    $(go.Panel, "Table",
                        {
                            name: "details",
                            defaultAlignment: go.Spot.Left,
                            background: "white",
                            padding: 1.5,
                            visible: true
                        },
                        $(go.RowColumnDefinition, {column: 0, width: 70}),
                        $(go.RowColumnDefinition, {column: 1, width: 30, minimum: 30}),

                        $(go.Panel, "TableRow", {row: 0},
                            $(go.TextBlock, "Choix", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "choix"), {column: 1, font: "8pt \"Open Sans\""})
                        ),
                        $(go.Panel, "TableRow", {row: 1},
                            $(go.TextBlock, "Assiduité", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "assiduite"), {column: 1, font: "8pt \"Open Sans\""})
                        ),
                        $(go.Panel, "TableRow", {row: 2},
                            $(go.TextBlock, "Effectifs", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "effectifs"), {column: 1, font: "8pt \"Open Sans\""})
                        ),
                        $(go.Panel, "TableRow", {row: 3},
                            $(go.TextBlock, "Groupes", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "groupes"), {column: 1, font: "8pt \"Open Sans\""})
                        )
                    )
                )
            )
        );

        var elementNodeTemplate = $(go.Node, "Vertical",
            $(go.Panel, "Auto",
                {name: 'panel', width: 110, height: 105},
                $(go.Shape, "RoundedRectangle", {fill: "#DAE8FF", name: "SHAPE", stroke: '#587DBA'}),
                $(go.Panel, "Vertical",
                    {padding: 10},
                    $(go.TextBlock, new go.Binding("text", "code")),
                    $(go.TextBlock,
                        {
                            column: 0,
                            margin: 1,
                            width: 100,
                            height: 28,
                            isMultiline: true,
                            maxLines: 3,
                            stroke: "black",
                            textAlign: "center",
                            font: "9pt \"Open Sans\""
                        },
                        new go.Binding("text", "libelle")
                    ),
                    $(go.Panel, "Table",
                        {
                            name: "details",
                            defaultAlignment: go.Spot.Left,
                            background: "white",
                            padding: 1.5,
                            visible: true
                        },
                        $(go.RowColumnDefinition, {column: 0, width: 70}),
                        $(go.RowColumnDefinition, {column: 1, width: 30, minimum: 30}),

                        $(go.Panel, "TableRow", {row: 0},
                            $(go.TextBlock, "Choix", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "choix"), {column: 1, font: "8pt \"Open Sans\""})
                        ),
                        $(go.Panel, "TableRow", {row: 1},
                            $(go.TextBlock, "Assiduité", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "assiduite"), {column: 1, font: "8pt \"Open Sans\""})
                        ),
                        $(go.Panel, "TableRow", {row: 2},
                            $(go.TextBlock, "Effectifs", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "effectifs"), {column: 1, font: "8pt \"Open Sans\""})
                        ),
                        $(go.Panel, "TableRow", {row: 3},
                            $(go.TextBlock, "Groupes", {column: 0, font: "8pt \"Open Sans\""}),
                            $(go.TextBlock, new go.Binding("text", "groupes"), {column: 1, font: "8pt \"Open Sans\""})
                        )
                    )
                )
            )
        );

        var defaultLinkTemplate = $(go.Link,
            {
                routing: go.Link.Orthogonal,
                corner: 5,
                relinkableFrom: false, relinkableTo: false
            },
            $(go.Shape, {
                stroke:'gray'
            },new go.Binding("strokeWidth", "poids")),
            $(go.Shape, {
                fill:'gray',
                stroke:'gray',
                toArrow: "Standard"
            },new go.Binding("strokeWidth", "poids"))
        );

        var desactivedLinkTemplate = $(go.Link,
            {
                routing: go.Link.Orthogonal,
                corner: 5,
                relinkableFrom: false, relinkableTo: false
            },
            $(go.Shape, {
                stroke:'red',
                strokeDashArray: [3, 5]
            },new go.Binding("strokeWidth", "poids")),
            $(go.Shape, {
                fill:'red',
                stroke:'red',
                toArrow: "Standard"
            },new go.Binding("strokeWidth", "poids"))
        );

        d.nodeTemplateMap = new go.Map("string", go.Node);
        d.nodeTemplateMap.add("noeud", defaultNodeTemplate);
        d.nodeTemplateMap.add("etape", etapeNodeTemplate);
        d.nodeTemplateMap.add("element", elementNodeTemplate);

        d.linkTemplateMap = new go.Map("string", go.Link);
        d.linkTemplateMap.add("actif", defaultLinkTemplate);
        d.linkTemplateMap.add("non-actif", desactivedLinkTemplate);

        //d.nodeTemplate = defaultNodeTemplate;
        //d.linkTemplate = defaultLinkTemplate;

        d.model = $(go.GraphLinksModel, {
            nodeDataArray: this.__makeNodeData(),
            linkDataArray: this.__makeLinkData()
        });

        return d;
    },



    __makeNodeData: function ()
    {
        var nd = [];

        for (var noeudId in this.noeuds) {
            nd.push({
                key: noeudId,
                id: noeudId,
                code: this.noeuds[noeudId].code,
                libelle: this.noeuds[noeudId].libelle,
                choix: this.__dataToGraph(noeudId, 'choix'),
                groupes: this.__dataToGraph(noeudId, 'groupes'),
                assiduite: this.__dataToGraph(noeudId, 'assiduite'),
                effectifs: this.__dataToGraph(noeudId, 'effectifs'),
                category: this.noeuds[noeudId].etape ? 'etape' : this.noeuds[noeudId]['element-pedagogique'] ? 'element' : 'noeud'
            });
        }
        return nd;
    },



    __makeLinkData: function ()
    {
        var ld = [];

        for(var lienId in this.liens){
            ld.push({
                id: lienId,
                from: this.liens[lienId]['noeud-sup'],
                to: this.liens[lienId]['noeud-inf'],
                actif: this.liens[lienId].actif,
                poids: this.liens[lienId].poids,
                category: (this.liens[lienId].actif) ? 'actif' : 'non-actif'
            });
        }
        return ld;
    },



    __dataToGraph: function (noeudId, propriete)
    {
        switch (propriete) {
            case 'choix':
                return this.noeuds[noeudId]['choix-minimum'] + ' / ' + this.noeuds[noeudId]['choix-maximum'];

            case 'groupes':
                return this.noeuds[noeudId]['groupes'];

            case 'assiduite':
                return Formatter.floatToString(this.noeuds[noeudId]['assiduite'] * 100) + '%';

            case 'effectifs':
                var effectifs = 0;
                for (var ti in this.noeuds[noeudId]['effectifs']) {
                    effectifs += this.noeuds[noeudId]['effectifs'][ti];
                }
                return effectifs;
        }
    }
});
