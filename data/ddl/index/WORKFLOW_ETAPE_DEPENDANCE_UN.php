<?php

//@formatter:off

return [
    'name'    => 'WORKFLOW_ETAPE_DEPENDANCE_UN',
    'unique'  => TRUE,
    'table'   => 'WORKFLOW_ETAPE_DEPENDANCE',
    'columns' => [
        'ETAPE_SUIVANTE_ID',
        'ETAPE_PRECEDANTE_ID',
        'TYPE_INTERVENANT_ID',
        'ANNEE_ID',
        'HISTO_DESTRUCTION',
    ],
];

//@formatter:on
