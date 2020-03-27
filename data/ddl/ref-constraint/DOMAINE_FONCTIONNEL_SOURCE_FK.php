<?php

//@formatter:off

return [
    'name'        => 'DOMAINE_FONCTIONNEL_SOURCE_FK',
    'table'       => 'DOMAINE_FONCTIONNEL',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
