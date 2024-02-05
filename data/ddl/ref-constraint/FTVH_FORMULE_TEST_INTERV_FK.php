<?php

//@formatter:off

return [
    'name'        => 'FTVH_FORMULE_TEST_INTERV_FK',
    'table'       => 'FORMULE_TEST_VOLUME_HORAIRE',
    'rtable'      => 'FORMULE_TEST_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'FORMULE_INTERVENANT_TEST_ID' => 'ID',
    ],
];

//@formatter:on
