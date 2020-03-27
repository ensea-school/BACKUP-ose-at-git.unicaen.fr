<?php

//@formatter:off

return [
    'name'        => 'VOLUME_HORAIRE_ENS_SOURCE_FK',
    'table'       => 'VOLUME_HORAIRE_ENS',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
