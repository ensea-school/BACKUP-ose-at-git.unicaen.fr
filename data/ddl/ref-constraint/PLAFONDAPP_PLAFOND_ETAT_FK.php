<?php

//@formatter:off

return [
    'name'        => 'PLAFONDAPP_PLAFOND_ETAT_FK',
    'table'       => 'PLAFOND_APPLICATION',
    'rtable'      => 'PLAFOND_ETAT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'PLAFOND_ETAT_ID' => 'ID',
    ],
];

//@formatter:on
