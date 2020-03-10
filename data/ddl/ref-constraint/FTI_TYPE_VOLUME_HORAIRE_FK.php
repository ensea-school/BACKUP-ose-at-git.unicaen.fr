<?php

//@formatter:off

return [
    'name'        => 'FTI_TYPE_VOLUME_HORAIRE_FK',
    'table'       => 'FORMULE_TEST_INTERVENANT',
    'rtable'      => 'TYPE_VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
