<?php

//@formatter:off

return [
    'name'        => 'EFFECTIFS_SOURCE_FK',
    'table'       => 'EFFECTIFS',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
