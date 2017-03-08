$.widget("ose.chargens", {
    etape: null,
    scenario: null,
    noeuds: {},
    liens: {},
    typesIntervention: {},
    typesHeures: {},
    diagramme: undefined,
    formNoeud: undefined,
    formLien: undefined,
    mousePosEvent: undefined,
    editionNoeudId: undefined,
    editionLienId: undefined,



    _create: function ()
    {
        var that = this;

        this.typesIntervention = this.element.data('type-intervention');
        this.typesHeures = this.element.data('type-heures');

        this.diagramme = this.__makeGraph();

        this.formNoeud = this.element.find(".form-noeud");
        this.formNoeud.dialog({
            autoOpen: false,
            hide: {effect: 'clip', duration: 200},
            show: {effect: 'clip', duration: 200},
            title: 'Édition',
            width: 400
        });
        this.formNoeud.css('display:block');

        this.formLien = this.element.find(".form-lien");
        this.formLien.dialog({
            autoOpen: false,
            hide: {effect: 'clip', duration: 200},
            show: {effect: 'clip', duration: 200},
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
        this.element.find('.controles .dupliquer').click(function () { that.demanderDuplication(); });

        this.diagramme.addDiagramListener("ObjectSingleClicked", function (e)
        {
            var part = e.subject.part;

            if (part instanceof go.Link) {
                that.editionLien(part.data.id);
            } else {
                that.editionNoeud(part.data.id);
            }
        });

        this.getFormNoeudBtnCancel().click(function ()
        {
            that.formNoeud.dialog('close');
            that.diagramme.clearSelection();
        });
        this.getFormNoeudBtnSave().click(function ()
        {
            that.applicationEditionNoeud();
            that.formNoeud.dialog("close");
            that.diagramme.clearSelection();
        });

        this.getFormLienBtnCancel().click(function ()
        {
            that.formLien.dialog('close');
            that.diagramme.clearSelection();
        });
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



    demanderDuplication: function ()
    {
        var that = this;
        var source = this.element.find('.controles #scenario').val();
        var eltDupl = this.element.find('.controles .dupliquer');

        if (!source) return this;

        var options = {
            url: this.element.data('url-scenario-dupliquer') + '/' + source,
            title: 'Dupliquer les données de ce diagramme dans un autre scénario',
            autoShow: true,
            submitClose: true,
            change: function (event, popAjax)
            {
                if (content = popAjax.getContent()) {
                    var noeuds = '';
                    var liens = '';

                    for (var n in that.noeuds) {
                        if (noeuds != '') noeuds += ',';
                        noeuds += n.toString();
                    }

                    for (var l in that.liens) {
                        if (liens != '') liens += ',';
                        liens += l.toString();
                    }

                    content.find('input:hidden[name=noeuds]').val(noeuds);
                    content.find('input:hidden[name=liens]').val(liens);
                }
            }
        };
        eltDupl.popAjax(options);
    },



    editionNoeud: function (noeudId)
    {
        var noeud = this.noeuds[noeudId];
        this.editionNoeudId = noeudId;

        if (noeud.liste) return this;

        this.formNoeud.find('#assiduite').val(noeud['assiduite'] * 100);

        for (var tid in this.typesHeures) {
            var val = noeud['effectifs'][tid];
            if (val === undefined) val = '';
            this.formNoeud.find('#effectifs-' + tid).val(val);
        }
        for (var tid in this.typesIntervention) {
            var val = noeud['seuils-ouverture'][tid];
            if (val === undefined) val = '';
            this.formNoeud.find('#seuil-ouverture-' + tid).val(val);

            var val = noeud['seuils-dedoublement'][tid];
            if (val === undefined) val = '';
            this.formNoeud.find('#seuil-dedoublement-' + tid).val(val);
        }


        if (noeud['element-pedagogique'] || noeud['etape']) {
            this.formNoeud.find('#choix-assiduite').hide();
        } else {
            this.formNoeud.find('#choix-assiduite').show();
        }


        if (noeud['etape']) {
            this.formNoeud.find('#effectifs').show();
        } else {
            this.formNoeud.find('#effectifs').hide();
        }

        if (noeud['etape']) {
            this.formNoeud.find('#seuils').show();
            this.formNoeud.find('#seuils .seuil').show();
        } else if (noeud['types-intervention'].length == 0) {
            this.formNoeud.find('#seuils').hide();
        } else {
            this.formNoeud.find('#seuils').show();
            this.formNoeud.find('#seuils .seuil').hide();
            for (ti in noeud['types-intervention']) {
                this.formNoeud.find('#seuils #seuil-' + noeud['types-intervention'][ti]).show();
            }
        }

        if (this.formNoeud.find('#choix-assiduite').css('display') != 'none'
            || this.formNoeud.find('#effectifs').css('display') != 'none'
            || this.formNoeud.find('#seuils').css('display') != 'none'
        ) {
            this.formNoeud.dialog({
                position: {
                    my: "center center",
                    of: this.mousePosEvent
                },
                title: noeud.libelle + ' (' + noeud.code + ')'
            });

            this.formNoeud.dialog("open");
        }
    },



    applicationEditionNoeud: function ()
    {
        var assiduite = this.formNoeud.find('#assiduite').val();

        var noeud = {
            id: this.editionNoeudId,
            assiduite: assiduite !== '' ? parseInt(assiduite) / 100 : 1,
            effectifs: {},
            'seuils-ouverture': {},
            'seuils-dedoublement': {}
        };
        for (var tid in this.typesHeures) {
            var val = this.formNoeud.find('#effectifs-' + tid).val();
            noeud.effectifs[tid] = val !== '' ? parseInt(val) : null;
        }
        for (var tid in this.typesIntervention) {
            var valOuv = this.formNoeud.find('#seuil-ouverture-' + tid).val();
            var valDed = this.formNoeud.find('#seuil-dedoublement-' + tid).val();
            noeud['seuils-ouverture'][tid] = valOuv !== '' ? parseInt(valOuv) : null;
            noeud['seuils-dedoublement'][tid] = valDed !== '' ? parseInt(valDed) : null;
        }

        this.mergeNoeudData(noeud);

        return this;
    },



    mergeNoeudData: function (data)
    {
        var noeud = this.noeuds[data.id];

        noeud['assiduite'] = data['assiduite'];
        for (var tid in this.typesHeures) {
            noeud.effectifs[tid] = data.effectifs[tid];
        }
        for (var tid in this.typesIntervention) {
            noeud['seuils-ouverture'][tid] = data['seuils-ouverture'][tid];
            noeud['seuils-dedoublement'][tid] = data['seuils-dedoublement'][tid];
        }

        this.majNoeud(data.id);

        saveData = {noeuds: {}};
        saveData.noeuds[data.id] = data;
        this.enregistrer(saveData);

        return this;
    },



    majNoeud: function (noeudId, noTransaction)
    {
        var model = this.diagramme.model;

        if (!noTransaction) model.startTransaction("majNoeud");
        for (i in model.nodeDataArray) {
            data = model.nodeDataArray[i];
            if (data.key == noeudId) {
                model.setDataProperty(data, 'hover', this.noeuds[noeudId].hover);
                model.setDataProperty(data, 'assiduite', this.__dataToGraph(noeudId, 'assiduite'));
                model.setDataProperty(data, 'hetd', this.__dataToGraph(noeudId, 'hetd'));
                model.setDataProperty(data, 'effectifs', this.__dataToGraph(noeudId, 'effectifs'));
                for (var ti in this.typesIntervention) {
                    model.setDataProperty(data, 'groupes-' + ti, this.__dataToGraph(noeudId, 'groupes', ti));
                }
            }
        }
        if (!noTransaction) model.commitTransaction("majNoeud");

        return this;
    },



    editionLien: function (lienId)
    {
        var lien = this.liens[lienId];
        var noeudInf = this.noeuds[lien['noeud-inf']];

        this.editionLienId = lienId;

        if (noeudInf.liste) {
            this.formLien.find('#choix').show();
        } else {
            this.formLien.find('#choix').hide();
        }

        this.formLien.find('#choix-minimum').val(lien['choix-minimum']);
        this.formLien.find('#choix-maximum').val(lien['choix-maximum']);

        this.formLien.find('#actif').prop('checked', lien['actif']);
        this.formLien.find('#poids').val(lien['poids']);

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
        var choixMinimum = this.formLien.find('#choix-minimum').val();
        var choixMaximum = this.formLien.find('#choix-maximum').val();

        var lien = {
            id: this.editionLienId,
            'choix-minimum': choixMinimum !== '' ? parseInt(choixMinimum) : null,
            'choix-maximum': choixMaximum !== '' ? parseInt(choixMaximum) : null,
            actif: this.formLien.find('#actif').is(':checked') ? 1 : 0,
            poids: parseFloat(this.formLien.find('#poids').val())
        };

        this.mergeLienData(lien);

        return this;
    },



    mergeLienData: function (data)
    {
        var lien = this.liens[data.id];

        lien['choix-minimum'] = data['choix-minimum'];
        lien['choix-maximum'] = data['choix-maximum'];
        lien.actif = data.actif;
        lien.poids = data.poids;

        this.majLien(data.id);

        saveData = {liens: {}};
        saveData.liens[data.id] = data;
        this.enregistrer(saveData);

        return this;
    },



    majLien: function (lienId, noTransaction)
    {
        var model = this.diagramme.model;

        if (!noTransaction) model.startTransaction("majLien");
        for (i in model.linkDataArray) {
            data = model.linkDataArray[i];
            if (data.id == lienId) {
                model.setDataProperty(data, 'hover', this.liens[lienId].hover);
                model.setDataProperty(data, 'actif', this.liens[lienId].actif);
                model.setDataProperty(data, 'poids', this.liens[lienId].poids);
            }
        }
        if (!noTransaction) model.commitTransaction("majLien");

        return this;
    },



    majDiagramme: function ()
    {
        var nd = [];

        for (var noeudId in this.noeuds) {
            var n = this.noeuds[noeudId];
            var category = n.etape ? 'etape' : n['element-pedagogique'] ? 'element' : 'noeud';
            if (n.liste) category = 'liste';
            var d = {
                key: noeudId,
                id: noeudId,
                code: n.code,
                libelle: n.libelle,
                choix: this.__dataToGraph(noeudId, 'choix'),
                assiduite: this.__dataToGraph(noeudId, 'assiduite'),
                hetd: this.__dataToGraph(noeudId, 'hetd'),
                effectifs: this.__dataToGraph(noeudId, 'effectifs'),
                category: category
            };

            for (var ti in this.typesIntervention) {
                d['groupes-' + ti] = this.__dataToGraph(noeudId, 'groupes', ti);
            }

            nd.push(d);
        }

        var ld = [];

        for (var lienId in this.liens) {
            ld.push({
                id: lienId,
                from: this.liens[lienId]['noeud-sup'],
                to: this.liens[lienId]['noeud-inf'],
                actif: this.liens[lienId].actif,
                poids: this.liens[lienId].poids,
                category: 'default'
            });
        }

        this.diagramme.model = go.GraphObject.make(go.GraphLinksModel, {
            nodeDataArray: nd,
            linkDataArray: ld
        });
    },



    majDiagrammeData: function ()
    {
        var model = this.diagramme.model;

        model.startTransaction("majDiagrammeData");
        for (var noeudId in this.noeuds) {
            this.majNoeud(noeudId, true);
        }

        for (var lienId in this.liens) {
            this.majLien(lienId, true);
        }
        model.commitTransaction("majDiagrammeData");
    },



    chargerDonnees: function (etape, scenario, data)
    {
        if (!data.noeuds) return this;

        this.scenario = scenario;
        this.noeuds = data.noeuds;
        this.liens = data.liens;

        if (etape != this.etape) {
            this.etape = etape;
            this.majDiagramme();
        } else {
            this.majDiagrammeData();
        }

        this.element.find('.controles #hetd-composante').html(
            data.hetd == null ? 'NC' : Formatter.floatToString(data.hetd)
        );

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



    charger: function (etape, scenario)
    {
        var url = this.element.data('url-json-etape');
        var params = {
            etape: etape,
            scenario: scenario
        };

        this.__actionServeur(url, params);
        return this;
    },



    enregistrer: function (data)
    {
        var url = this.element.data('url-enregistrer');
        var params = {
            etape: this.etape,
            scenario: this.scenario,
            data: data,
        };
        this.__actionServeur(url, params);
        return this;
    },



    highlight: function (noeudId, hover, noTransaction)
    {
        var model = this.diagramme.model;

        if (!noTransaction) model.startTransaction("highlight");

        this.noeuds[noeudId].hover = hover;
        this.majNoeud(noeudId);

        for (var lienId in this.liens) {
            var lien = this.liens[lienId];
            if (lien.actif && lien['noeud-sup'] == noeudId) {
                lien.hover = hover;
                this.majLien(lien.id);
                this.highlight(lien['noeud-inf'], hover, true);
            }
        }

        if (!noTransaction) model.commitTransaction("highlight");
    },



    __actionServeur: function (url, params)
    {
        var that = this;
        var p = params;

        $.post(url, params, function (data)
        {
            if (data.erreur) {
                alertFlash(data.erreur, 'danger', 5000);
            } else {
                that.chargerDonnees(p.etape, p.scenario, data);
            }

        }).fail(function (jqXHR)
        {
            alertFlash('Une erreur est survenue. L\'opération n\'a pas pu être effectuée.', 'danger', 5000);
            console.log(jqXHR);
        });
    },



    getFormNoeudBtnCancel: function () { return this.formNoeud.find('#btn-cancel')},
    getFormNoeudBtnSave: function () { return this.formNoeud.find('#btn-save')},
    getFormLienBtnCancel: function () { return this.formLien.find('#btn-cancel')},
    getFormLienBtnSave: function () { return this.formLien.find('#btn-save')},
    getHetdComposante: function () { return this.element.find('#hetd-composante')},



    __makeGraph: function ()
    {
        var that = this;
        var $ = go.GraphObject.make;

        var highlightColor = "#00A1FF";

        var yellowGradient = [
            {
                fill: $(go.Brush, "Linear", {0: "rgb(252, 248, 227)", 1: "rgb(250, 242, 204)"}),
                stroke: "#edd6a3"
            },
            new go.Binding("stroke", "hover", function (hover) {return hover ? highlightColor : "#edd6a3";}),
            new go.Binding("strokeWidth", "hover", function (hover) {return hover ? 2 : 1;})
        ];

        var grayGradient = [
            {
                fill: $(go.Brush, "Linear", {0: "rgb(245, 245, 245)", 1: "rgb(232, 232, 232)"}),
                stroke: "#ccc"
            },
            new go.Binding("stroke", "hover", function (hover) {return hover ? highlightColor : "#ccc";}),
            new go.Binding("strokeWidth", "hover", function (hover) {return hover ? 2 : 1;})
        ];

        var blueGradient = [
            {
                fill: $(go.Brush, "Linear", {0: "rgb(217, 237, 247)", 1: "rgb(196, 227, 243)"}),
                stroke: "#98CED9"
            },
            new go.Binding("stroke", "hover", function (hover) {return hover ? highlightColor : "#98CED9";}),
            new go.Binding("strokeWidth", "hover", function (hover) {return hover ? 2 : 1;})
        ];


        var d =
            $(go.Diagram, this.element.find('.dessin').attr('id'),
                {
                    initialContentAlignment: go.Spot.Top,
                    initialDocumentSpot: go.Spot.TopCenter,
                    initialViewportSpot: go.Spot.TopCenter,
                    isReadOnly: true,
                    maxSelectionCount: 1,
                    layout: $(go.LayeredDigraphLayout, {
                        direction: 90,
                        layerSpacing: 20,
                        columnSpacing: 1,
                        aggressiveOption: go.LayeredDigraphLayout.AggressiveMore
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

        var newNodeTemplatePropriete = function (index, propriete, label)
        {
            return $(go.Panel, "TableRow", {row: index},
                $(go.TextBlock, label, {column: 0, font: "8pt \"Open Sans\""}),
                $(go.TextBlock, new go.Binding("text", propriete), {column: 1, font: "8pt \"Open Sans\""})
            )
        };

        var sel = {
            selectionAdornmentTemplate: $(go.Adornment, "Auto",
                $(go.Shape, "RoundedRectangle",
                    {fill: null, stroke: "dodgerblue", strokeWidth: 4}),
                $(go.Placeholder)
            ),
            mouseEnter: function (e, obj) { that.highlight(obj.part.data.id, true); },
            mouseLeave: function (e, obj) { that.highlight(obj.part.data.id, false); }
        }

        var defaultNodeTemplate = $(go.Node, "Vertical", sel,
            $(go.Panel, "Auto",
                {name: 'panel', width: 110, height: 82},
                $(go.Shape, "RoundedRectangle", grayGradient, {name: "SHAPE"}),
                $(go.Panel, "Vertical",
                    {padding: 10},
                    $(go.TextBlock, new go.Binding("text", "code"), {stroke: "#999"}),
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
                        [
                            newNodeTemplatePropriete(0, 'assiduite', 'Assiduité'),
                            newNodeTemplatePropriete(1, 'effectifs', 'Effectifs')
                        ]
                    )
                )
            )
        );

        var listeNodeTemplate = $(go.Node, "Vertical", sel,
            $(go.Panel, "Auto",
                {name: 'panel', width: 10, height: 10},
                $(go.Shape, "Circle", {
                        name: "SHAPE",
                        fill: '#3F3F3F',
                        stroke: '#3F3F3F'
                    },
                    new go.Binding("fill", "hover", function (hover) {return hover ? highlightColor : "#3F3F3F";}),
                    new go.Binding("stroke", "hover", function (hover) {return hover ? highlightColor : "#3F3F3F";})
                ),
                $(go.Panel, "Vertical",
                    {padding: 10}
                )
            )
        );

        var etapeNodeTemplate = $(go.Node, "Vertical", sel,
            $(go.Panel, "Auto",
                {name: 'panel', width: 110, height: 82},
                $(go.Shape, "RoundedRectangle", yellowGradient, {name: "SHAPE"}),
                $(go.Panel, "Vertical",
                    {padding: 10},
                    $(go.TextBlock, new go.Binding("text", "code"), {stroke: "#B39F2D"}),
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
                        [
                            newNodeTemplatePropriete(0, 'effectifs', 'Effectifs'),
                            newNodeTemplatePropriete(1, 'hetd', 'HETD')
                        ]
                    )
                )
            )
        );

        var index = 0;
        var elementNodeTemplateProprietes = [
            newNodeTemplatePropriete(index++, 'effectifs', 'Effectifs')
        ];

        for (var ti in this.typesIntervention) {
            elementNodeTemplateProprietes.push(
                newNodeTemplatePropriete(index++, 'groupes-' + ti, 'Groupes ' + this.typesIntervention[ti])
            );
        }

        elementNodeTemplateProprietes.push(
            newNodeTemplatePropriete(index++, 'hetd', 'HETD')
        );

        var elementNodeTemplate = $(go.Node, "Vertical", sel,
            $(go.Panel, "Auto",
                {name: 'panel', width: 110, height: 55 + (elementNodeTemplateProprietes.length) * 13},
                $(go.Shape, "RoundedRectangle", blueGradient, {name: "SHAPE"}),
                $(go.Panel, "Vertical",
                    {padding: 10},
                    $(go.TextBlock, new go.Binding("text", "code"), {stroke: "#3C728D"}),
                    $(go.TextBlock,
                        {
                            column: 0,
                            margin: 1,
                            width: 100,
                            height: 28,
                            isMultiline: true,
                            maxLines: 2,
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
                        elementNodeTemplateProprietes
                    )
                )
            )
        );

        var defaultLinkTemplate = $(go.Link,
            {
                curve: go.Link.Bezier,
                toEndSegmentLength: 30, fromEndSegmentLength: 30,
                relinkableFrom: false, relinkableTo: false
            },
            $(
                go.Shape,
                new go.Binding("strokeWidth", "poids"),
                new go.Binding("stroke", "", function (data)
                {
                    if (data.hover) return highlightColor;
                    return data.actif ? "#3F3F3F" : "#CB0000";
                }),
                new go.Binding("strokeDashArray", "actif", function (actif) {return actif ? null : [3, 5];})
            )
        );

        d.nodeTemplateMap = new go.Map("string", go.Node);
        d.nodeTemplateMap.add("noeud", defaultNodeTemplate);
        d.nodeTemplateMap.add("etape", etapeNodeTemplate);
        d.nodeTemplateMap.add("liste", listeNodeTemplate);
        d.nodeTemplateMap.add("element", elementNodeTemplate);

        d.linkTemplateMap = new go.Map("string", go.Link);
        d.linkTemplateMap.add("default", defaultLinkTemplate);

        return d;
    },



    __dataToGraph: function (noeudId, propriete, propriete2)
    {
        switch (propriete) {
            case 'assiduite':
                return Formatter.floatToString(this.noeuds[noeudId]['assiduite'] * 100) + '%';

            case 'hetd':
                if (this.noeuds[noeudId]['hetd'] == null) return 'NC';
                return Formatter.floatToString(this.noeuds[noeudId]['hetd']);

            case 'effectifs':
                var effectifs = 0;
                for (var ti in this.noeuds[noeudId]['effectifs']) {
                    effectifs += this.noeuds[noeudId]['effectifs'][ti];
                }
                return effectifs;

            case 'groupes':
                if (
                    this.noeuds[noeudId]['types-intervention'].length > 0
                    && -1 != this.noeuds[noeudId]['types-intervention'].indexOf(parseInt(propriete2))
                ) {
                    var effectifs = 0;
                    for (var ti in this.noeuds[noeudId]['effectifs']) {
                        effectifs += this.noeuds[noeudId]['effectifs'][ti];
                    }

                    var seuilOuverture = this.noeuds[noeudId]['seuils-ouverture'][propriete2];
                    if (!seuilOuverture) seuilOuverture = 1;

                    var seuilDedoublement = this.noeuds[noeudId]['seuils-dedoublement'][propriete2];
                    if (!seuilOuverture) seuilOuverture = 9999;

                    if (effectifs < seuilOuverture) return 0;
                    return Math.ceil(effectifs / seuilDedoublement);
                } else {
                    return '-';
                }
        }
    }
});





$.widget("ose.chargensFiltre", {
    structuresEtapes: [],
    structuresScenarios: [],
    etapesStructure: [],


    _create: function ()
    {
        var that = this;

        this.structuresEtapes = this.getStructureElement().data('etapes');
        this.structuresScenarios = this.getStructureElement().data('scenarios');
        this.etapesStructure = this.getEtapeElement().data('structures');

        this.getStructureElement().change(function ()
        {
            that.updateEtapeValues();
            that.change();
        });
        this.getEtapeElement().change(function ()
        {
            that.updateScenarioValues();
            that.change();
        });
        this.getScenarioElement().change(function () { that.change(); });

        this.updateEtapeValues();
        this.change();
    },



    updateEtapeValues: function ()
    {
        var structure = this.getStructureElement().val();

        var etapes = structure ? this.structuresEtapes[structure] : 'all';

        Util.filterSelectPicker(this.getEtapeElement(), etapes);
        this.updateScenarioValues();
    },



    updateScenarioValues: function ()
    {
        var etape = this.getEtapeElement().val();
        var structure = this.getStructureElement().val();

        if (etape) {
            var scenarios = this.etapesStructure[etape] ? this.structuresScenarios[[this.etapesStructure[etape]]] : 'all';
        } else {
            var scenarios = structure ? this.structuresScenarios[structure] : 'all';
        }

        Util.filterSelectPicker(this.getScenarioElement(), scenarios);
    },



    change: function ()
    {
        var etape = this.getEtapeElement().val();
        var scenario = this.getScenarioElement().val();

        $('.chargens').chargens('charger', etape, scenario);
    },



    getStructureElement: function () { return this.element.find('#structure'); },
    getEtapeElement: function () { return this.element.find('#etape'); },
    getScenarioElement: function () { return this.element.find('#scenario'); }

});
