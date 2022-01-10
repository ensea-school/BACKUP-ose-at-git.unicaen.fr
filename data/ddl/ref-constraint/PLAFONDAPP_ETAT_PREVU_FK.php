<?php

//@formatter:off

return [
    'name'        => 'PLAFONDAPP_ETAT_PREVU_FK',
    'table'       => 'PLAFOND_APPLICATION',
    'rtable'      => 'PLAFOND_ETAT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'PLAFOND_ETAT_PREVU_ID' => 'ID',
    ],
];

//@formatter:on
