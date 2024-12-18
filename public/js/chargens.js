$.widget("ose.chargens", {
    etape: null,
    scenario: null,
    noeuds: {},
    liens: {},
    structure: null,
    heures: null,
    hetd: null,
    typesIntervention: {},
    typesHeures: {},
    diagramme: undefined,
    formNoeud: undefined,
    formNoeudModal: undefined,
    formLien: undefined,
    formLienModal: undefined,
    mousePosEvent: undefined,
    editionNoeudId: undefined,
    editionLienId: undefined,


    _create: function () {
        var that = this;

        this.typesIntervention = this.element.data('type-intervention');
        this.typesHeures = this.element.data('type-heures');

        this.diagramme = this.__makeGraph();

        this.formNoeud = this.element.find(".form-noeud");
        this.formNoeudModal = new bootstrap.Modal(this.formNoeud, {});

        this.formLien = this.element.find(".form-lien");
        this.formLienModal = new bootstrap.Modal(this.formLien, {});

        $(document).mousemove(function (event) {
            that.mousePosEvent = event;
        });

        this.element.find('.controles .zplus').click(function () {
            that.zoomPlus();
        });
        this.element.find('.controles .zmoins').click(function () {
            that.zoomMoins();
        });
        this.element.find('.controles .zdefaut').click(function () {
            that.zoomDefaut();
        });
        this.element.find('.controles .fullscreen').change(function () {
            that.fullScreen();
        });
        this.element.find('.controles .dupliquer').click(function () {
            that.demanderDuplication();
        });

        this.diagramme.addDiagramListener("ObjectSingleClicked", function (e) {
            var part = e.subject.part;

            if (part instanceof go.Link) {
                that.editionLien(part.data.id);
            } else {
                that.editionNoeud(part.data.id);
            }
        });

        this.getFormNoeudBtnCancel().click(function () {
            that.diagramme.clearSelection();
        });
        this.getFormNoeudBtnSave().click(function () {
            that.applicationEditionNoeud();
            that.formNoeudModal.hide();
            that.diagramme.clearSelection();
        });

        this.getFormLienBtnCancel().click(function () {
            that.formLien.dialog('close');
            that.diagramme.clearSelection();
        });
        this.getFormLienBtnSave().click(function () {
            that.applicationEditionLien();
            that.formLienModal.hide();
            that.diagramme.clearSelection();
        });

        this.updateHeuresComposante();
    },


    zoomPlus: function () {
        this.diagramme.commandHandler.increaseZoom(1.2);
        return this;
    },


    zoomMoins: function () {
        this.diagramme.commandHandler.increaseZoom(0.8);
        return this;
    },


    zoomDefaut: function () {
        this.diagramme.commandHandler.resetZoom();
        return this;
    },


    demanderDuplication: function () {
        var that = this;
        var source = this.element.find('.controles #scenario').val();
        var eltDupl = this.element.find('.controles .dupliquer');

        if (!source) return this;

        var options = {
            url: this.element.data('url-scenario-dupliquer') + '/' + source,
            title: 'Dupliquer les données de ce diagramme dans un autre scénario',
            autoShow: true,
            submitClose: true,
            change: function (event, popAjax) {
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


    editionNoeud: function (noeudId) {
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
            if (noeud['seuils-dedoublement-defaut'][tid]) {
                this.formNoeud.find('#seuil-dedoublement-' + tid).attr('placeholder', 'Défaut : ' + noeud['seuils-dedoublement-defaut'][tid]);
            } else {
                this.formNoeud.find('#seuil-dedoublement-' + tid).attr('placeholder', 'Aucun');
            }

            var val = noeud['seuils-assiduite'][tid];
            if (val === undefined) val = ''; else val *= 100;
            this.formNoeud.find('#seuil-assiduite-' + tid).val(val);
        }

        this.formNoeud.find('#choix-assiduite').hide();
        /*if (!noeud['can-edit-assiduite'] || noeud['element-pedagogique'] || noeud['etape']) {
            this.formNoeud.find('#choix-assiduite').hide();
        } else {
            this.formNoeud.find('#choix-assiduite').show();
        }*/

        if (noeud['can-edit-assiduite'] && noeud['element-pedagogique']) {
            this.formNoeud.find('.seuil-assiduite').show();
        } else {
            this.formNoeud.find('.seuil-assiduite').hide();
        }

        if (noeud['can-edit-effectifs'] && noeud['etape']) {
            this.formNoeud.find('#effectifs').show();
        } else {
            this.formNoeud.find('#effectifs').hide();
        }

        if (!noeud['can-edit-seuils']) {
            this.formNoeud.find('#seuils').hide();
        } else {
            if (noeud['types-intervention'].length == 0) {
                this.formNoeud.find('#seuils').hide();
            } else {
                this.formNoeud.find('#seuils').show();
                this.formNoeud.find('#seuils .seuil').hide();
                for (ti in noeud['types-intervention']) {
                    this.formNoeud.find('#seuils #seuil-' + noeud['types-intervention'][ti]).show();
                }
            }
        }

        if (this.formNoeud.find('#choix-assiduite').css('display') != 'none'
            || this.formNoeud.find('#effectifs').css('display') != 'none'
            || this.formNoeud.find('#seuils').css('display') != 'none'
        ) {
            this.formNoeud.find('.modal-title').html(noeud.libelle + ' (' + noeud.code + ')');
            this.formNoeudModal.show();
        }
    },


    applicationEditionNoeud: function () {
        var assiduite = this.formNoeud.find('#assiduite').val();

        var noeud = {
            id: this.editionNoeudId,
            assiduite: assiduite !== '' ? parseInt(assiduite) / 100 : 1,
            effectifs: {},
            'seuils-ouverture': {},
            'seuils-dedoublement': {},
            'seuils-assiduite': {}
        };
        for (var tid in this.typesHeures) {
            var val = this.formNoeud.find('#effectifs-' + tid).val();
            noeud.effectifs[tid] = val !== '' ? parseFloat(val) : null;
        }
        for (var tid in this.typesIntervention) {
            var valOuv = this.formNoeud.find('#seuil-ouverture-' + tid).val();
            var valDed = this.formNoeud.find('#seuil-dedoublement-' + tid).val();
            var valAss = this.formNoeud.find('#seuil-assiduite-' + tid).val();
            noeud['seuils-ouverture'][tid] = valOuv !== '' ? parseInt(valOuv) : null;
            noeud['seuils-dedoublement'][tid] = valDed !== '' ? parseInt(valDed) : null;
            noeud['seuils-assiduite'][tid] = valAss !== '' ? parseFloat(valAss) / 100 : null;
        }

        this.mergeNoeudData(noeud);

        return this;
    },


    mergeNoeudData: function (data) {
        var noeud = this.noeuds[data.id];

        noeud['assiduite'] = data['assiduite'];
        for (var tid in this.typesHeures) {
            noeud.effectifs[tid] = data.effectifs[tid];
        }
        for (var tid in this.typesIntervention) {
            noeud['seuils-ouverture'][tid] = data['seuils-ouverture'][tid];
            noeud['seuils-dedoublement'][tid] = data['seuils-dedoublement'][tid];
            noeud['seuils-assiduite'][tid] = data['seuils-assiduite'][tid];
        }

        this.majNoeud(data.id);

        saveData = {noeuds: {}};
        saveData.noeuds[data.id] = data;
        this.enregistrer(saveData);

        return this;
    },


    noeudToNodeData: function (noeudId) {
        var noeud = this.noeuds[noeudId];

        var category = noeud.etape ? 'etape' : noeud['element-pedagogique'] ? 'element' : 'noeud';
        if (noeud.liste) category = 'liste';

        var data = {
            key: noeudId,
            id: noeudId,
            code: noeud.code,
            libelle: noeud.libelle,
            hover: noeud.hover,
            category: category,
            proprietes: []
        };

        /* Assiduité */
        /*if (category == 'noeud') {
            data.proprietes.push({
                label: 'Assiduité',
                value: Formatter.floatToString(noeud.assiduite * 100) + '%'
            });
        }*/

        /* Effectifs */
        if (category == 'noeud' || category == 'etape' || category == 'element') {
            var effectifs = 0;
            for (var eff in noeud.effectifs) {
                effectifs += noeud.effectifs[eff];
            }
            data.proprietes.push({
                label: 'Effectifs',
                value: Math.ceil(effectifs)
            });
        }

        /* Groupes */
        if (category == 'element') {
            for (var ti in this.typesIntervention) {
                if (noeud['types-intervention'].length > 0
                    && -1 != noeud['types-intervention'].indexOf(parseInt(ti))) {
                    var seuilOuverture = noeud['seuils-ouverture'][ti];
                    if (!seuilOuverture) seuilOuverture = 1;

                    var seuilDedoublement = noeud['seuils-dedoublement'][ti];
                    if (!seuilDedoublement) seuilDedoublement = noeud['seuils-dedoublement-defaut'][ti];
                    if (!seuilDedoublement) seuilDedoublement = 1;

                    var seuilAssiduite = noeud['seuils-assiduite'][ti];
                    if (!seuilAssiduite) seuilAssiduite = 1;

                    var value = 0;
                    if (effectifs * seuilAssiduite >= seuilOuverture) {
                        value = Math.ceil(effectifs * seuilAssiduite / seuilDedoublement);
                    }

                    data.proprietes.push({
                        label: this.typesIntervention[ti],
                        value: value
                    });
                }
            }
        }

        /* Heures */
        if (category == 'etape' || category == 'element') {
            var heures = 'NC';
            if (noeud.heures != null) {
                heures = Formatter.floatToString(noeud.heures);
            }
            data.proprietes.push({
                label: 'Heures',
                value: heures
            });

            var hetd = 'NC';
            if (noeud.hetd != null) {
                hetd = Formatter.floatToString(noeud.hetd);
            }
            data.proprietes.push({
                label: 'HETD',
                value: hetd
            });
        }

        return data;
    },


    majNoeud: function (noeudId, noTransaction) {
        var model = this.diagramme.model;

        if (!noTransaction) model.startTransaction("majNoeud");
        for (i in model.nodeDataArray) {
            data = model.nodeDataArray[i];
            if (data.key == noeudId) {
                var newData = this.noeudToNodeData(noeudId);
                for (var k in newData) {
                    model.setDataProperty(data, k, newData[k]);
                }
            }
        }

        if (!noTransaction) model.commitTransaction("majNoeud");

        return this;
    },


    editionLien: function (lienId) {
        var lien = this.liens[lienId];
        var noeudInf = this.noeuds[lien['noeud-inf']];

        this.editionLienId = lienId;

        if (noeudInf.liste && lien['can-edit-choix']) {
            this.formLien.find('#choix').show();
        } else {
            this.formLien.find('#choix').hide();
        }

        if (lien['can-edit-actif']) {
            this.formLien.find('#div-actif').show();
        } else {
            this.formLien.find('#div-actif').hide();
        }

        if (!noeudInf.liste && lien['can-edit-poids']) {
            this.formLien.find('#div-poids').show();
        } else {
            this.formLien.find('#div-poids').hide();
        }

        this.formLien.find('#choix-minimum').val(lien['choix-minimum']);
        this.formLien.find('#choix-maximum').val(lien['choix-maximum']);

        this.formLien.find('#actif').prop('checked', lien['actif']);
        this.formLien.find('#poids').val(lien['poids']);

        if (this.formLien.find('#choix').css('display') != 'none'
            || this.formLien.find('#div-actif').css('display') != 'none'
            || this.formLien.find('#div-poids').css('display') != 'none'
        ) {
            this.formLienModal.show();
        }
    },


    applicationEditionLien: function () {
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


    mergeLienData: function (data) {
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


    lienToLinkData: function (lienId) {
        var lien = this.liens[lienId];

        var data = {
            id: lienId,
            from: lien['noeud-sup'],
            to: lien['noeud-inf'],
            actif: lien.actif,
            poids: lien.poids,
            hover: lien.hover,
            category: 'default'
        };

        return data;
    },


    majLien: function (lienId, noTransaction) {
        var model = this.diagramme.model;

        if (!noTransaction) model.startTransaction("majLien");
        for (i in model.linkDataArray) {
            data = model.linkDataArray[i];
            if (data.id == lienId) {
                var newData = this.lienToLinkData(lienId);
                for (var k in newData) {
                    model.setDataProperty(data, k, newData[k]);
                }
            }
        }
        if (!noTransaction) model.commitTransaction("majLien");

        return this;
    },


    majDiagramme: function () {
        var nd = [];

        for (var noeudId in this.noeuds) {
            nd.push(this.noeudToNodeData(noeudId));
        }

        var ld = [];

        for (var lienId in this.liens) {
            ld.push(this.lienToLinkData(lienId));
        }

        this.diagramme.model = go.GraphObject.make(go.GraphLinksModel, {
            nodeDataArray: nd,
            linkDataArray: ld
        });
        this.element.find('#chargens-attente').hide();
    },


    majDiagrammeData: function () {
        var model = this.diagramme.model;

        model.startTransaction("majDiagrammeData");
        for (var noeudId in this.noeuds) {
            this.majNoeud(noeudId, true);
        }

        for (var lienId in this.liens) {
            this.majLien(lienId, true);
        }
        model.commitTransaction("majDiagrammeData");
        this.element.find('#chargens-attente').hide();
    },


    chargerDonnees: function (etape, scenario, data) {
        if (!data.noeuds) return this;

        this.scenario = scenario;
        this.noeuds = data.noeuds;
        this.liens = data.liens;
        this.heures = data.heures;
        this.hetd = data.hetd;
        this.structure = data.structure;

        if (etape != this.etape) {
            //console.log('chargerDonnees');
            this.etape = etape;
            this.majDiagramme();
        } else {
            this.majDiagrammeData();
        }

        this.updateHeuresComposante();

        return this;
    },


    updateHeuresComposante: function () {
        var str = '';
        var heures = 'NC';
        var hetd = 'NC';
        if (this.structure) {
            heures = Formatter.floatToString(this.heures);
            hetd = Formatter.floatToString(this.hetd);

            str = 'Total <abbr title="Formation initiale">(FI)</abbr> ' + this.structure + ' (heures: ' + heures + '; hetd: ' + hetd + ')';
        }
        this.getHeuresComposante().html(str);
    },


    isFullScreen: function () {
        return this.element.hasClass('fullscreen');
    },


    fullScreen: function () {
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


    charger: function (etape, scenario) {
        if (etape && scenario) {
            this.element.find('#chargens-attente').show();
        }
        var url = this.element.data('url-json-etape');
        var params = {
            etape: etape,
            scenario: scenario
        };

        this.__actionServeur(url, params);
        return this;
    },


    enregistrer: function (data) {
        var url = this.element.data('url-enregistrer');
        var params = {
            etape: this.etape,
            scenario: this.scenario,
            data: data,
        };
        this.__actionServeur(url, params);
        return this;
    },


    highlight: function (noeudId, hover, sens, noTransaction) {
        var model = this.diagramme.model;

        if (!noTransaction) model.startTransaction("highlight");

        this.noeuds[noeudId].hover = hover;
        this.majNoeud(noeudId);

        for (var lienId in this.liens) {
            var lien = this.liens[lienId];
            if (lien.actif && lien['noeud-sup'] == noeudId && sens != 'haut') {
                lien.hover = hover;
                this.majLien(lien.id);
                this.highlight(lien['noeud-inf'], hover, 'bas', true);
            }

            if (lien.actif && lien['noeud-inf'] == noeudId && sens != 'bas') {
                lien.hover = hover;
                this.majLien(lien.id);
                this.highlight(lien['noeud-sup'], hover, 'haut', true);
            }
        }

        if (!noTransaction) model.commitTransaction("highlight");
    },


    __actionServeur: function (url, params) {
        var that = this;
        var p = params;

        $.ajax({
            type: 'POST',
            url: url,
            data: params,
            success: function (data) {
                if (data.erreur) {
                    unicaenVue.flashMessenger.toast(data.erreur, 'error');
                } else if (data.noeuds) {
                    that.chargerDonnees(p.etape, p.scenario, data);
                } else {
                    unicaenVue.flashMessenger.toast(data, 'error');
                }

            },
            error: function (jqXHR) {
                unicaenVue.flashMessenger.toast(jqXHR.responseText, 'error');
                console.log(jqXHR);
            }
        });
    },


    getFormNoeudBtnCancel: function () {
        return this.formNoeud.find('#btn-cancel')
    },
    getFormNoeudBtnSave: function () {
        return this.formNoeud.find('#btn-save')
    },
    getFormLienBtnCancel: function () {
        return this.formLien.find('#btn-cancel')
    },
    getFormLienBtnSave: function () {
        return this.formLien.find('#btn-save')
    },
    getHeuresComposante: function () {
        return this.element.find('#heures-composante')
    },


    __makeGraph: function () {
        var that = this;
        var $ = go.GraphObject.make;

        go.licenseKey = "73ff44e0b11c28c702d95d76423d38f919a42a63c98449a30c0416f6ef086c46729cec7059c19bc6d5a846fd182dc08ddac76028c01e553eb03887d811e4d1f8b23123b01d00178bf15474c09dfd2aa9a82d70f7c2e120a68a788ee0fbae96cc5ae8a18449d81eb828780f2e5561af4e";

        var highlightColor = "#00A1FF";

        var yellowGradient = [
            {
                fill: $(go.Brush, "Linear", {0: "rgb(252, 248, 227)", 1: "rgb(250, 242, 204)"}),
                stroke: "#edd6a3"
            },
            new go.Binding("stroke", "hover", function (hover) {
                return hover ? highlightColor : "#edd6a3";
            })
        ];

        var grayGradient = [
            {
                fill: $(go.Brush, "Linear", {0: "rgb(245, 245, 245)", 1: "rgb(232, 232, 232)"}),
                stroke: "#ccc"
            },
            new go.Binding("stroke", "hover", function (hover) {
                return hover ? highlightColor : "#ccc";
            })
        ];

        var blueGradient = [
            {
                fill: $(go.Brush, "Linear", {0: "rgb(217, 237, 247)", 1: "rgb(196, 227, 243)"}),
                stroke: "#98CED9"
            },
            new go.Binding("stroke", "hover", function (hover) {
                return hover ? highlightColor : "#98CED9";
            })
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
                        columnSpacing: 4,
                        aggressiveOption: go.LayeredDigraphLayout.AggressiveMore
                    }),
                    InitialLayoutCompleted: function (e) {
                        var maxHeight = window.innerHeight - that.element.find('.dessin').offset().top;
                        var dia = e.diagram;
                        var height = dia.documentBounds.height + 20;

                        if (height > maxHeight) height = maxHeight;

                        dia.div.style.height = height + "px";
                    }
                }
            );

        var nodeTemplate = function (gradient) {
            return $(go.Node, "Vertical", {
                    selectionAdornmentTemplate: $(go.Adornment, "Auto",
                        $(go.Shape, "RoundedRectangle",
                            {fill: null, stroke: "dodgerblue", strokeWidth: 4}),
                        $(go.Placeholder)
                    ),
                    mouseEnter: function (e, obj) {
                        that.highlight(obj.part.data.id, true);
                    },
                    mouseLeave: function (e, obj) {
                        that.highlight(obj.part.data.id, false);
                    }
                },
                $(go.Panel, "Auto",
                    {name: 'panel', width: 110},
                    $(go.Shape, "RoundedRectangle", gradient, {name: "SHAPE"}),
                    $(go.Panel, "Vertical",
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
                            new go.Binding("itemArray", "proprietes"),
                            {
                                name: "details",
                                defaultAlignment: go.Spot.Left,
                                background: "white",
                                padding: 1.5,
                                itemTemplate: $(go.Panel, "TableRow",
                                    $(go.TextBlock, new go.Binding("text", 'label'), {
                                        column: 0,
                                        width: 50,
                                        font: "8pt \"Open Sans\""
                                    }),
                                    $(go.TextBlock, new go.Binding("text", 'value'), {
                                        column: 1,
                                        width: 50,
                                        font: "8pt \"Open Sans\""
                                    })
                                )
                            }
                        )
                    )
                )
            );
        }

        var defaultNodeTemplate = nodeTemplate(grayGradient);
        var etapeNodeTemplate = nodeTemplate(yellowGradient);
        var elementNodeTemplate = nodeTemplate(blueGradient);

        var listeNodeTemplate = $(go.Node, "Vertical", {
                selectionAdornmentTemplate: $(go.Adornment, "Auto",
                    $(go.Shape, "RoundedRectangle",
                        {fill: null, stroke: "dodgerblue", strokeWidth: 4}),
                    $(go.Placeholder)
                ),
                mouseEnter: function (e, obj) {
                    that.highlight(obj.part.data.id, true);
                },
                mouseLeave: function (e, obj) {
                    that.highlight(obj.part.data.id, false);
                }
            },
            $(go.Panel, "Auto",
                {name: 'panel', width: 10, height: 10},
                $(go.Shape, "Circle", {
                        name: "SHAPE",
                        fill: '#3F3F3F',
                        stroke: '#3F3F3F'
                    },
                    new go.Binding("fill", "hover", function (hover) {
                        return hover ? highlightColor : "#3F3F3F";
                    }),
                    new go.Binding("stroke", "hover", function (hover) {
                        return hover ? highlightColor : "#3F3F3F";
                    })
                ),
                $(go.Panel, "Vertical",
                    {padding: 10}
                )
            )
        );

        var linkTemplate = $(go.Link,
            {
                curve: go.Link.Bezier,
                toEndSegmentLength: 30, fromEndSegmentLength: 30,
                relinkableFrom: false, relinkableTo: false
            },
            $(
                go.Shape,
                new go.Binding("strokeWidth", "poids"),
                new go.Binding("stroke", "", function (data) {
                    if (data.hover) return highlightColor;
                    return data.actif ? "#3F3F3F" : "#CB0000";
                }),
                new go.Binding("strokeDashArray", "actif", function (actif) {
                    return actif ? null : [3, 5];
                })
            )
        );

        d.nodeTemplateMap = new go.Map("string", go.Node);
        d.nodeTemplateMap.add("noeud", defaultNodeTemplate);
        d.nodeTemplateMap.add("etape", etapeNodeTemplate);
        d.nodeTemplateMap.add("liste", listeNodeTemplate);
        d.nodeTemplateMap.add("element", elementNodeTemplate);

        d.linkTemplateMap = new go.Map("string", go.Link);
        d.linkTemplateMap.add("default", linkTemplate);

        return d;
    },

});


$.widget("ose.chargensFiltre", {
    structuresEtapes: [],
    structuresScenarios: [],
    etapesStructure: [],

    _create: function () {
        var that = this;

        this.structuresEtapes = this.getStructureElement().data('etapes');
        this.structuresScenarios = this.getStructureElement().data('scenarios');
        this.etapesStructure = this.getEtapeElement().data('structures');

        this.getStructureElement().change(function () {
            that.updateEtapeValues();
            that.change();
        });
        this.getEtapeElement().change(function () {
            that.updateScenarioValues();
            that.change();
        });
        this.getScenarioElement().change(function () {
            that.change();
        });

        this.updateEtapeValues();
        this.change();
    },


    updateEtapeValues: function () {
        var structure = this.getStructureElement().val();

        var etapes = structure ? this.structuresEtapes[structure] : 'all';

        Util.filterSelectPicker(this.getEtapeElement(), etapes);
        this.updateScenarioValues();
    },


    updateScenarioValues: function () {
        var etape = this.getEtapeElement().val();
        var structure = this.getStructureElement().val();

        if (etape) {
            var scenarios = this.etapesStructure[etape] ? this.structuresScenarios[[this.etapesStructure[etape]]] : 'all';
        } else {
            var scenarios = structure ? this.structuresScenarios[structure] : 'all';
        }

        Util.filterSelectPicker(this.getScenarioElement(), scenarios);
    },


    change: function () {
        var etape = this.getEtapeElement().val();
        var scenario = this.getScenarioElement().val();

        if (etape && scenario) {
            $('.chargens').chargens('charger', etape, scenario);
        }
    },


    getStructureElement: function () {
        return this.element.find('#structure');
    },
    getEtapeElement: function () {
        return this.element.find('#etape');
    },
    getScenarioElement: function () {
        return this.element.find('#scenario');
    }

});

$(function () {

    /* Charges d'enseignement */
    WidgetInitializer.add('chargens', 'chargens');
    WidgetInitializer.add('chargens-filtre', 'chargensFiltre');

});