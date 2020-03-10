<?php

//@formatter:off

return [
    'name'        => 'TBL_DOSSIER_INTERVENANT_FK',
    'table'       => 'TBL_DOSSIER',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
