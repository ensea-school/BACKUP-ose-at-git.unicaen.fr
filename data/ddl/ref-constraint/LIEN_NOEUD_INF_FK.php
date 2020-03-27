<?php

//@formatter:off

return [
    'name'        => 'LIEN_NOEUD_INF_FK',
    'table'       => 'LIEN',
    'rtable'      => 'NOEUD',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'NOEUD_INF_ID' => 'ID',
    ],
];

//@formatter:on
