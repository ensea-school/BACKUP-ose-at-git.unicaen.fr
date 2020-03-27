<?php

//@formatter:off

return [
    'name'        => 'TVE_VOLUME_HORAIRE_FK',
    'table'       => 'TBL_VALIDATION_ENSEIGNEMENT',
    'rtable'      => 'VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
