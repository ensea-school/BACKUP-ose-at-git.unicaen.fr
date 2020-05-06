<?php

//@formatter:off

return [
    'name'        => 'VH_CHARGE_TYPE_INTERVENTION_FK',
    'table'       => 'VOLUME_HORAIRE_CHARGE',
    'rtable'      => 'TYPE_INTERVENTION',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENTION_ID' => 'ID',
    ],
];

//@formatter:on
