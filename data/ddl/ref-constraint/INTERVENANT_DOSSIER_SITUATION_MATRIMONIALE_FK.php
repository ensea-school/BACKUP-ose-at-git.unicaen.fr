<?php

//@formatter:off

return [
    'name'        => 'INTERVENANT_DOSSIER_SITUATION_MATRIMONIALE_FK',
    'table'       => 'INTERVENANT_DOSSIER',
    'rtable'      => 'SITUATION_MATRIMONIALE',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'SITUATION_MATRIMONIALE_ID' => 'ID',
    ],
];

//@formatter:on
