<?php

//@formatter:off

return [
    'name'        => 'VH_CHARGE_SCENARIO_FK',
    'table'       => 'VOLUME_HORAIRE_CHARGE',
    'rtable'      => 'SCENARIO',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SCENARIO_ID' => 'ID',
    ],
];

//@formatter:on
