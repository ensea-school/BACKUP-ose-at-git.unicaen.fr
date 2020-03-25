<?php

//@formatter:off

return [
    'name'        => 'SNS_TYPE_INTERVENTION_FK',
    'table'       => 'SCENARIO_NOEUD_SEUIL',
    'rtable'      => 'TYPE_INTERVENTION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENTION_ID' => 'ID',
    ],
];

//@formatter:on
