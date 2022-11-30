<?php

//@formatter:off

return [
    'name'        => 'MISSION_TRV_TAUX_FK',
    'table'       => 'MISSION_TAUX_REMU_VALEUR',
    'rtable'      => 'MISSION_TAUX_REMU',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'MISSION_TAUX_REMU_ID' => 'ID',
    ],
];

//@formatter:on
