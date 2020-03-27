<?php

//@formatter:off

return [
    'name'        => 'SCENARIO_LIEN_LIEN_FK',
    'table'       => 'SCENARIO_LIEN',
    'rtable'      => 'LIEN',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'LIEN_ID' => 'ID',
    ],
];

//@formatter:on
