<?php

//@formatter:off

return [
    'name'        => 'INTERVENANT_PAYS_NAISS_FK',
    'table'       => 'INTERVENANT',
    'rtable'      => 'PAYS',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'PAYS_NAISSANCE_ID' => 'ID',
    ],
];

//@formatter:on
