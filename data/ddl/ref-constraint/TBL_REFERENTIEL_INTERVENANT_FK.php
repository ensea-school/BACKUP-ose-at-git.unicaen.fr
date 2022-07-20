<?php

//@formatter:off

return [
    'name'        => 'TBL_REFERENTIEL_INTERVENANT_FK',
    'table'       => 'TBL_REFERENTIEL',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
