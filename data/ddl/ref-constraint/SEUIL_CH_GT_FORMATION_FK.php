<?php

//@formatter:off

return [
    'name'        => 'SEUIL_CH_GT_FORMATION_FK',
    'table'       => 'SEUIL_CHARGE',
    'rtable'      => 'GROUPE_TYPE_FORMATION',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'GROUPE_TYPE_FORMATION_ID' => 'ID',
    ],
];

//@formatter:on
