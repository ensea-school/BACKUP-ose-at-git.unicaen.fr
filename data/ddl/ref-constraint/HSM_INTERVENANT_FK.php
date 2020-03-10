<?php

//@formatter:off

return [
    'name'        => 'HSM_INTERVENANT_FK',
    'table'       => 'HISTO_INTERVENANT_SERVICE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
