<?php

//@formatter:off

return [
    'name'    => 'WORKFLOW_ETAPE_DEPENDANCE_UK',
    'table'   => 'WORKFLOW_ETAPE_DEPENDANCE',
    'index'   => 'WORKFLOW_ETAPE_DEPENDANCE_UK',
    'columns' => [
        'ETAPE_SUIVANTE_ID',
        'ETAPE_PRECEDANTE_ID',
        'TYPE_INTERVENANT_ID',
        'ACTIVE',
    ],
];

//@formatter:on
