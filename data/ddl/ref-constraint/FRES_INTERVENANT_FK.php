<?php

//@formatter:off

return [
    'name'        => 'FRES_INTERVENANT_FK',
    'table'       => 'FORMULE_RESULTAT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
