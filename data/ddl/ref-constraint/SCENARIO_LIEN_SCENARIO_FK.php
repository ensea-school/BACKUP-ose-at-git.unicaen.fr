<?php

//@formatter:off

return [
    'name'        => 'SCENARIO_LIEN_SCENARIO_FK',
    'table'       => 'SCENARIO_LIEN',
    'rtable'      => 'SCENARIO',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SCENARIO_ID' => 'ID',
    ],
];

//@formatter:on
