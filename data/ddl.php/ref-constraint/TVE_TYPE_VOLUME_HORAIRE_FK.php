<?php

//@formatter:off

return [
    'name'        => 'TVE_TYPE_VOLUME_HORAIRE_FK',
    'table'       => 'TBL_VALIDATION_ENSEIGNEMENT',
    'rtable'      => 'TYPE_VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
