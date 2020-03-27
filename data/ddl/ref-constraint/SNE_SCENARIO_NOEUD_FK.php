<?php

//@formatter:off

return [
    'name'        => 'SNE_SCENARIO_NOEUD_FK',
    'table'       => 'SCENARIO_NOEUD_EFFECTIF',
    'rtable'      => 'SCENARIO_NOEUD',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SCENARIO_NOEUD_ID' => 'ID',
    ],
];

//@formatter:on
