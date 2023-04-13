<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_MISSION_TVH_FK',
    'table'       => 'TBL_PLAFOND_MISSION',
    'rtable'      => 'TYPE_VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
