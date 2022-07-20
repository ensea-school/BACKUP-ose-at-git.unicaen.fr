<?php

//@formatter:off

return [
    'name'        => 'TBL_REFERENTIEL_ANNEE_FK',
    'table'       => 'TBL_REFERENTIEL',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
