<?php

//@formatter:off

return [
    'name'    => 'WORKFLOW_ETAPE_DEPENDANCE_UK',
    'unique'  => TRUE,
    'table'   => 'WORKFLOW_ETAPE_DEPENDANCE',
    'columns' => [
        'ETAPE_SUIVANTE_ID',
        'ETAPE_PRECEDANTE_ID',
        'TYPE_INTERVENANT_ID',
        'ACTIVE',
    ],
];

//@formatter:on
