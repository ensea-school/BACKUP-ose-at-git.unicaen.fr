<?php

//@formatter:off

return [
    'name'        => 'TBCH_NOEUD_FK',
    'table'       => 'TBL_CHARGENS',
    'rtable'      => 'NOEUD',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'NOEUD_ID' => 'ID',
    ],
];

//@formatter:on
