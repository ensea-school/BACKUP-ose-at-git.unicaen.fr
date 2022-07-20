<?php

//@formatter:off

return [
    'name'        => 'TBL_REFERENTIEL_INT_STR_FK',
    'table'       => 'TBL_REFERENTIEL',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
