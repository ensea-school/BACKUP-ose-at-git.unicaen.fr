<?php

//@formatter:off

return [
    'name'        => 'TBCH_ETAPE_FK',
    'table'       => 'TBL_CHARGENS',
    'rtable'      => 'ETAPE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ETAPE_ID' => 'ID',
    ],
];

//@formatter:on
