<?php

//@formatter:off

return [
    'name'        => 'EFFECTIFS_ELEMENT_FK',
    'table'       => 'EFFECTIFS',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
