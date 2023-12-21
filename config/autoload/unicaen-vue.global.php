<?php

$oa = OseAdmin::instance();

return [
    'unicaen-vue' => [
        'host'        => 'http://localhost:5133',
        'hot-loading' => $oa->env()->inDev() ? $oa->config()->get('dev', 'hot-loading', false) : false,
        'dist-path'   => 'public/dist',
        'dist-url'    => 'dist',
    ],
];