<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$bdd = $container->get(\Unicaen\BddAdmin\Bdd::class);

/*$tables = [
    [
        'key'    => "Corps",
        'fields' => [
            ['name' => "field1", 'info' => "", 'color' => "#F7B84B", 'figure' => "Ellipse"],
            ['name' => "field2", 'info' => "the second one", 'color' => "#F25022", 'figure' => "Ellipse"],
            ['name' => "fieldThree", 'info' => "3rd", 'color' => "#00BCF2"],
        ],
        'loc'    => "0 0",
    ],
    [
        'key'    => "Grade",
        'fields' => [
            ['name' => "fieldA", 'info' => "", 'color' => "#FFB900", 'figure' => "Diamond"],
            ['name' => "fieldB", 'info' => "", 'color' => "#F25022", 'figure' => "Rectangle"],
            ['name' => "fieldC", 'info' => "", 'color' => "#7FBA00", 'figure' => "Diamond"],
            ['name' => "fieldD", 'info' => "fourth", 'color' => "#00BCF2", 'figure' => "Rectangle"],
        ],
        'loc'    => "280 50",
    ],
];*/

$list = [
    'ANNEE', 'INTERVENANT', 'CORPS', 'GRADE', 'GROUPE_TYPE_FORMATION', 'TYPE_FORMATION', 'ETAPE', 'ELEMENT_PEDAGOGIQUE',
    'CHEMIN_PEDAGOGIQUE', 'VOLUME_HORAIRE_ENS', 'TYPE_INTERVENTION',
];

$tables = [];
$tbls   = $bdd->table()->get($list);
foreach ($tbls as $tbl) {
    $table = [
        'key'    => $tbl['name'],
        'fields' => [],
        'loc'    => '0 0',
    ];
    foreach ($tbl['columns'] as $col) {
        $type = $col['type'];
        if ($col['length']) {
            $type .= ' (' . $col['length'] . ')';
        }
        $column            = [
            'name' => $col['name'],
            'type' => $type,
        ];
        $table['fields'][] = $column;
    }

    $tables[] = $table;
}

$cles = [];
$cls  = $bdd->refConstraint()->get();
foreach ($cls as $cl) {
    foreach ($cl['columns'] as $col => $rcol) {
        $cle    = [
            'from'    => $cl['table'],
            'fromCol' => $col,
            'to'      => $cl['rtable'],
            'toCol'   => $rcol,
        ];
        $cles[] = $cle;
    }
}


?>
<div id="sample">
    <div id="myDiagramDiv" style="border: solid 1px black; width:100%; height:1000px"></div>


    <p>For a variation on this sample with selectable fields in the record nodes, see the <a href="selectableFields.html">selectable
            fields</a> sample.</p>
    <div>
        Diagram Model saved in JSON format, automatically updated after each change or undo or redo:
        <textarea id="mySavedModel" style="width:100%;height:250px"></textarea>
        <div style="display: none; position: absolute; width: 0px; height: 0px; margin-top: -1px;"></div>
    </div>
</div>
<script>


    $(function () {

        WidgetInitializer.includeJs(unicaenVue.url('ext/go.js'));

        var $ = go.GraphObject.make;

        myDiagram =
            $(go.Diagram, "myDiagramDiv",
                {
                    layout: $(go.LayeredDigraphLayout, {direction: 0}),

                    validCycle: go.Diagram.CycleNotDirected,  // don't allow loops
                    // For this sample, automatically show the state of the diagram's model on the page
                    "ModelChanged": function (e) {
                        if (e.isTransactionFinished) showModel();
                    },
                    "undoManager.isEnabled": true
                });

        var columnTemplate =
            $(go.Panel, "TableRow",  // this Panel is a row in the containing Table
                new go.Binding("portId", "name"),  // this Panel is a "port"
                {
                    background: "transparent",  // so this port's background can be picked by the mouse
                    fromSpot: go.Spot.Right,  // links only go from the right side to the left side
                    toSpot: go.Spot.Left,
                    // allow drawing links from or to this port:
                    fromLinkable: false, toLinkable: false
                },
                $(go.TextBlock,
                    {
                        margin: new go.Margin(0, 0), column: 1, font: "normal 10px sans-serif",
                        alignment: go.Spot.Left,
                        // and disallow drawing links from or to this text:
                        fromLinkable: false, toLinkable: false
                    },
                    new go.Binding("text", "name")),
                $(go.TextBlock,
                    {
                        margin: new go.Margin(0, 5),
                        column: 2,
                        stroke: "#ef9b0b",
                        font: "normal 9px sans-serif",
                        alignment: go.Spot.Left
                    },
                    new go.Binding("text", "type"))
            );

        // This template represents a whole "record".
        myDiagram.nodeTemplate =
            $(go.Node, "Auto",
                {copyable: false, deletable: false},
                //new go.Binding("location"),
                //new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
                // this rectangular shape surrounds the content of the node
                $(go.Shape,
                    {stroke: "#efd47e", strokeWidth: 2, fill: "#fff4eb"}),
                // the content consists of a header and a list of items
                $(go.Panel, "Vertical",
                    // this is the header for the whole node
                    $(go.Panel, "Auto",
                        {stretch: go.GraphObject.Horizontal},  // as wide as the whole node
                        $(go.Shape,
                            {fill: "#ffe6cf", stroke: null}),
                        $(go.TextBlock,
                            {
                                alignment: go.Spot.Center,
                                margin: 1,
                                stroke: "black",
                                textAlign: "center",
                                font: "bold 10pt sans-serif"
                            },
                            new go.Binding("text", "key"))),
                    // this Panel holds a Panel for each item object in the itemArray;
                    // each item Panel is defined by the itemTemplate to be a TableRow in this Table
                    $(go.Panel, "Table",
                        {
                            padding: 2,
                            minSize: new go.Size(100, 10),
                            defaultStretch: go.GraphObject.Horizontal,
                            itemTemplate: columnTemplate
                        },
                        new go.Binding("itemArray", "fields")
                    )  // end Table Panel of items
                )  // end Vertical Panel
            );  // end Node

        myDiagram.linkTemplate =
            $(go.Link,  // the whole link panel
                {relinkableFrom: false, relinkableTo: false, reshapable: true, resegmentable: true},
                {
                    //routing: go.Link.AvoidsNodes,  // but this is changed to go.Link.Orthgonal when the Link is reshaped
                    adjusting: go.Link.End,
                    curve: go.Link.JumpOver,
                    corner: 1,
                    toShortLength: 4
                },
                new go.Binding("points").makeTwoWay(),
                // remember the Link.routing too
                new go.Binding("routing", "routing", go.Binding.parseEnum(go.Link, go.Link.AvoidsNodes))
                    .makeTwoWay(go.Binding.toString),
                $(go.Shape,  // the link path shape
                    {isPanelMain: true, strokeWidth: 1}),
                $(go.Shape,  // the arrowhead
                    {toArrow: "Standard", stroke: null})
            );

        myDiagram.model =
            $(go.GraphLinksModel,
                {
                    copiesArrays: true,
                    copiesArrayObjects: true,
                    linkFromPortIdProperty: "fromCol",
                    linkToPortIdProperty: "toCol",
                    nodeDataArray: <?php echo json_encode($tables) ?>,
                    linkDataArray: <?php echo json_encode($cles) ?>,
                });

        showModel();  // show the diagram's initial model

        function showModel()
        {
            document.getElementById("mySavedModel").textContent = myDiagram.model.toJson();
        }
    });
</script>