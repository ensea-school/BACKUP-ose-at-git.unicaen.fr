<?php

//@formatter:off

return [
    'name'        => 'SNE_TYPE_HEURES_FK',
    'table'       => 'SCENARIO_NOEUD_EFFECTIF',
    'rtable'      => 'TYPE_HEURES',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_HEURES_ID' => 'ID',
    ],
];

//@formatter:on
