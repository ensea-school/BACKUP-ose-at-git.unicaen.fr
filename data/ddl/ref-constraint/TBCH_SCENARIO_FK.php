<?php

//@formatter:off

return [
    'name'        => 'TBCH_SCENARIO_FK',
    'table'       => 'TBL_CHARGENS',
    'rtable'      => 'SCENARIO',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SCENARIO_ID' => 'ID',
    ],
];

//@formatter:on
