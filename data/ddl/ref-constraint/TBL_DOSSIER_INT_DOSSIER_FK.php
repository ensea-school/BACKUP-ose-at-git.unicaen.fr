<?php

//@formatter:off

return [
    'name'        => 'TBL_DOSSIER_INT_DOSSIER_FK',
    'table'       => 'TBL_DOSSIER',
    'rtable'      => 'INTERVENANT_DOSSIER',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'DOSSIER_ID' => 'ID',
    ],
];

//@formatter:on
