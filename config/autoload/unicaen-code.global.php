<?php

if (defined('APPLICATION_PATH')){
    $settings = [
        'view-dirs'     => [APPLICATION_PATH . '/code'],
        'template-dirs' => [APPLICATION_PATH . '/code/template'],
        'generator-output-dir' => '/tmp/UnicaenCode',
    ];
}else{
    $settings = [];
}

return [
    'unicaen-code' => $settings,
];