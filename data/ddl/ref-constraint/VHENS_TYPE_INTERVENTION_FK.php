<?php

//@formatter:off

return [
    'name'        => 'VHENS_TYPE_INTERVENTION_FK',
    'table'       => 'VOLUME_HORAIRE_ENS',
    'rtable'      => 'TYPE_INTERVENTION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENTION_ID' => 'ID',
    ],
];

//@formatter:on
