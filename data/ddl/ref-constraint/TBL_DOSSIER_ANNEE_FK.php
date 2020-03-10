<?php

//@formatter:off

return [
    'name'        => 'TBL_DOSSIER_ANNEE_FK',
    'table'       => 'TBL_DOSSIER',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
