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



    editionNoeud: function (noeudId)
    {
        var noeud = this.noeuds[noeudId];
        this.editionNoeudId = noeudId;

        this.formNoeud.find('#choix-minimum').val(noeud['choix-minimum']);
        this.formNoeud.find('#choix-maximum').val(noeud['choix-maximum']);
        this.formNoeud.find('#assiduite').val(noeud['assiduite'] * 100);

        for (var tid in this.typesHeures) {
            var val = noeud['effectifs'][tid];
            if (val === undefined) val = 0;
            this.formNoeud.find('#effectifs-' + tid).val(val);
        }
        for (var tid in this.typesIntervention) {
            var val = noeud['seuils-ouverture'][tid];
            if (val === undefined) val = 0;
            this.formNoeud.find('#seuil-ouverture-' + tid).val(val);

            var val = noeud['seuils-dedoublement'][tid];
            if (val === undefined) val = 0;
            this.formNoeud.find('#seuil-dedoublement-' + tid).val(val);
        }


        if (noeud['element-pedagogique']) {
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
        var noeud = {
            id: this.editionNoeudId,
            'choix-minimum': parseInt(this.formNoeud.find('#choix-minimum').val()),
            'choix-maximum': parseInt(this.formNoeud.find('#choix-maximum').val()),
            assiduite: parseInt(this.formNoeud.find('#assiduite').val()) / 100,
            effectifs: {},
            'seuils-ouverture': {},
            'seuils-dedoublement': {}
        };
        for (var tid in this.typesHeures) {
            noeud.effectifs[tid] = parseInt(this.formNoeud.find('#effectifs-' + tid).val());
        }
        for (var tid in this.typesIntervention) {
            noeud['seuils-ouverture'][tid] = parseInt(this.formNoeud.find('#seuil-ouverture-' + tid).val());
            noeud['seuils-dedoublement'][tid] = parseInt(this.formNoeud.find('#seuil-dedoublement-' + tid).val());
        }

        this.mergeNoeudData(noeud);

        return this;
    },



    mergeNoeudData: function (data)
    {
        var noeud = this.noeuds[data.id];

        if (data['choix-minimum'] !== undefined) {
            noeud['choix-minimum'] = data['choix-minimum'];
        }
        if (data['choix-maximum'] !== undefined) {
            noeud['choix-maximum'] = data['choix-maximum'];
        }
        if (data['assiduite'] !== undefined) {
            noeud['assiduite'] = data['assiduite'];
        }
        for (var tid in this.typesHeures) {
            if (data.effectifs[tid] !== undefined){
                noeud.effectifs[tid] = data.effectifs[tid];
            }
        }
        for (var tid in this.typesIntervention) {
            if (data['seuils-ouverture'][tid] !== undefined){
                noeud['seuils-ouverture'][tid] = data['seuils-ouverture'][tid];
            }
            if (data['seuils-dedoublement'][tid] !== undefined){
                noeud['seuils-dedoublement'][tid] = data['seuils-dedoublement'][tid];
            }
        }

        this.majNoeud(data.id);

        saveData = {noeuds: {}};
        saveData.noeuds[data.id] = data;
        this.enregistrer(saveData);

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
                model.setDataProperty(data, 'assiduite', this.__dataToGraph(noeudId, 'assiduite'));
                model.setDataProperty(data, 'effectifs', this.__dataToGraph(noeudId, 'effectifs'));
                for (var ti in this.typesIntervention) {
                    model.setDataProperty(data, 'groupes-' + ti, this.__dataToGraph(noeudId, 'groupes', ti));
                }
            }
        }
        model.commitTransaction("majNoeud");

        return this;
    },



    editionLien: function (lienId)
    {
        var lien = this.liens[lienId];
        this.editionLienId = lienId;

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
        var lien = {
            id: this.editionLienId,
            actif: this.formLien.find('#actif').is(':checked'),
            poids: parseFloat(this.formLien.find('#poids').val())
        };

        this.mergeLienData(lien);

        return this;
    },



    mergeLienData: function (data)
    {
        var lien = this.liens[data.id];

        if (data.actif !== undefined){
            lien.actif = data.actif;
        }
        if (data.poids !== undefined){
            lien.poids = data.poids;
        }

        this.majLien(data.id);

        saveData = {liens: {}};
        saveData.liens[data.id] = data;
        this.enregistrer(saveData);

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



    majDiagramme: function ()
    {
        var nd = [];

        for (var noeudId in this.noeuds) {
            var n = this.noeuds[noeudId];
            var d = {
                key: noeudId,
                id: noeudId,
                code: n.code,
                libelle: n.libelle,
                choix: this.__dataToGraph(noeudId, 'choix'),
                assiduite: this.__dataToGraph(noeudId, 'assiduite'),
                effectifs: this.__dataToGraph(noeudId, 'effectifs'),
                category: n.etape ? 'etape' : n['element-pedagogique'] ? 'element' : 'noeud'
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
                category: (this.liens[lienId].actif) ? 'actif' : 'non-actif'
            });
        }

        this.diagramme.model = go.GraphObject.make(go.GraphLinksModel, {
            nodeDataArray: nd,
            linkDataArray: ld
        });
    },



    majDiagrammeData: function ()
    {
        for (var noeudId in this.noeuds) {
            this.majNoeud(noeudId);
        }

        for (var lienId in this.liens) {
            this.majLien(lienId);
        }
    },



    chargerDonnees: function (etape, scenario, noeuds, liens)
    {
        if (!noeuds) return this;

        this.scenario = scenario;
        this.noeuds = noeuds;
        this.liens = liens;

        if (etape != this.etape) {
            this.etape = etape;
            this.majDiagramme();
        } else {
            this.majDiagrammeData();
        }

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



    __actionServeur: function (url, params)
    {
        var that = this;
        var p = params;

        $.post(url, params, function (data)
        {
            if (data.erreur) {
                alertFlash(data.erreur, 'danger', 5000);
            } else {
                that.chargerDonnees(p.etape, p.scenario, data.noeuds, data.liens);
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



    __makeGraph: function ()
    {
        var that = this;
        var $ = go.GraphObject.make;

        var yellowGradient = {
            fill: $(go.Brush, "Linear", {0: "rgb(252, 248, 227)", 1: "rgb(250, 242, 204)"}),
            stroke: '#edd6a3'
        };

        var grayGradient = {
            fill: $(go.Brush, "Linear", {0: "rgb(245, 245, 245)", 1: "rgb(232, 232, 232)"}),
            stroke: '#ccc'
        };

        var blueGradient = {
            fill: $(go.Brush, "Linear", {0: "rgb(217, 237, 247)", 1: "rgb(196, 227, 243)"}),
            stroke: '#98CED9'
        };




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
            )  // end Adornment
        }

        var defaultNodeTemplate = $(go.Node, "Vertical", sel,
            $(go.Panel, "Auto",
                {name: 'panel', width: 110, height: 95},
                $(go.Shape, "RoundedRectangle", grayGradient),
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
                            newNodeTemplatePropriete(0, 'choix', 'Choix'),
                            newNodeTemplatePropriete(1, 'assiduite', 'Assiduité'),
                            newNodeTemplatePropriete(2, 'effectifs', 'Effectifs')
                        ]
                    )
                )
            )
        );

        var etapeNodeTemplate = $(go.Node, "Vertical", sel,
            $(go.Panel, "Auto",
                {name: 'panel', width: 110, height: 95},
                $(go.Shape, "RoundedRectangle", yellowGradient),
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
                            newNodeTemplatePropriete(0, 'choix', 'Choix'),
                            newNodeTemplatePropriete(1, 'assiduite', 'Assiduité'),
                            newNodeTemplatePropriete(2, 'effectifs', 'Effectifs')
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

        var elementNodeTemplate = $(go.Node, "Vertical", sel,
            $(go.Panel, "Auto",
                {name: 'panel', width: 110, height: 55 + (elementNodeTemplateProprietes.length) * 13},
                $(go.Shape, "RoundedRectangle", blueGradient),
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
                routing: go.Link.Orthogonal,
                corner: 5,
                relinkableFrom: false, relinkableTo: false
            },
            $(go.Shape, {
                stroke: '#3F3F3F'
            }, new go.Binding("strokeWidth", "poids")),
            $(go.Shape, {
                fill: '#3F3F3F',
                stroke: '#3F3F3F',
                toArrow: "Standard"
            }, new go.Binding("strokeWidth", "poids"))
        );

        var desactivedLinkTemplate = $(go.Link,
            {
                routing: go.Link.Orthogonal,
                corner: 5,
                relinkableFrom: false, relinkableTo: false
            },
            $(go.Shape, {
                stroke: '#CB0000',
                strokeDashArray: [3, 5]
            }, new go.Binding("strokeWidth", "poids")),
            $(go.Shape, {
                fill: '#CB0000',
                stroke: '#CB0000',
                toArrow: "Standard"
            }, new go.Binding("strokeWidth", "poids"))
        );

        d.nodeTemplateMap = new go.Map("string", go.Node);
        d.nodeTemplateMap.add("noeud", defaultNodeTemplate);
        d.nodeTemplateMap.add("etape", etapeNodeTemplate);
        d.nodeTemplateMap.add("element", elementNodeTemplate);

        d.linkTemplateMap = new go.Map("string", go.Link);
        d.linkTemplateMap.add("actif", defaultLinkTemplate);
        d.linkTemplateMap.add("non-actif", desactivedLinkTemplate);

        return d;
    },



    __dataToGraph: function (noeudId, propriete, propriete2)
    {
        switch (propriete) {
            case 'choix':
                return this.noeuds[noeudId]['choix-minimum'] + ' / ' + this.noeuds[noeudId]['choix-maximum'];

            case 'assiduite':
                return Formatter.floatToString(this.noeuds[noeudId]['assiduite'] * 100) + '%';

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
