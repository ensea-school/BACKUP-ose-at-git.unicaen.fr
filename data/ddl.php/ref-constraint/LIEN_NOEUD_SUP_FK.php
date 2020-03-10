<?php

//@formatter:off

return [
    'name'        => 'LIEN_NOEUD_SUP_FK',
    'table'       => 'LIEN',
    'rtable'      => 'NOEUD',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'NOEUD_SUP_ID' => 'ID',
    ],
];

//@formatter:on
