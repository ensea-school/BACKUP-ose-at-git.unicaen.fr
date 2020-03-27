<?php

//@formatter:off

return [
    'name'        => 'PLAFONDAPP_TVH_FK',
    'table'       => 'PLAFOND_APPLICATION',
    'rtable'      => 'TYPE_VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
