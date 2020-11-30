<?php

//@formatter:off

return [
    'name'        => 'DISCIPLINE_MAPPING_FK',
    'table'       => 'DISCIPLINE_MAPPING',
    'rtable'      => 'DISCIPLINE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'DISCIPLINE_ID' => 'ID',
    ],
];

//@formatter:on
