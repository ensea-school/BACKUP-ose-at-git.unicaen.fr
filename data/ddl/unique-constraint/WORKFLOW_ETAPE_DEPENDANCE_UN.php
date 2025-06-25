<?php

//@formatter:off

return [
    'name'    => 'WORKFLOW_ETAPE_DEPENDANCE_UN',
    'table'   => 'WORKFLOW_ETAPE_DEPENDANCE',
    'index'   => 'WORKFLOW_ETAPE_DEPENDANCE_UN',
    'columns' => [
        'ETAPE_SUIVANTE_ID',
        'ETAPE_PRECEDANTE_ID',
        'TYPE_INTERVENANT_ID',
        'ACTIVE',
        'ANNEE_ID',
        'HISTO_DESTRUCTION',
    ],
];

//@formatter:on
