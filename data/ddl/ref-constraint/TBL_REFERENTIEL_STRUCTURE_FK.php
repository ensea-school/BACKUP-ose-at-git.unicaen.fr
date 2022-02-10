<?php

//@formatter:off

return [
    'name'        => 'TBL_REFERENTIEL_STRUCTURE_FK',
    'table'       => 'TBL_REFERENTIEL',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
