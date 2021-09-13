<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_REFERENTIEL_INT_FK',
    'table'       => 'TBL_PLAFOND_REFERENTIEL',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
