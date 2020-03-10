<?php

//@formatter:off

return [
    'name'        => 'CHEMIN_PEDAGOGIQUE_ETAPE_FK',
    'table'       => 'CHEMIN_PEDAGOGIQUE',
    'rtable'      => 'ETAPE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ETAPE_ID' => 'ID',
    ],
];

//@formatter:on
