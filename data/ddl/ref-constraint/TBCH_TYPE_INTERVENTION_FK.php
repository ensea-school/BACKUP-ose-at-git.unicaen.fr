<?php

//@formatter:off

return [
    'name'        => 'TBCH_TYPE_INTERVENTION_FK',
    'table'       => 'TBL_CHARGENS',
    'rtable'      => 'TYPE_INTERVENTION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENTION_ID' => 'ID',
    ],
];

//@formatter:on
