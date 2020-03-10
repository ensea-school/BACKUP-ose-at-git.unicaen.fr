<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_TVH_FK',
    'table'       => 'TBL_SERVICE',
    'rtable'      => 'TYPE_VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
