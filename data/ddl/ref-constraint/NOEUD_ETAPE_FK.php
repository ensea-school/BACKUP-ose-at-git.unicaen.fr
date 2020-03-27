<?php

//@formatter:off

return [
    'name'        => 'NOEUD_ETAPE_FK',
    'table'       => 'NOEUD',
    'rtable'      => 'ETAPE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ETAPE_ID' => 'ID',
    ],
];

//@formatter:on
