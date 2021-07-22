<?php

$settings = [
    'view-dirs'            => [getcwd() . '/code'],
    'template-dirs'        => [getcwd() . '/code/template'],
    'generator-output-dir' => '/app/cache/UnicaenCode',
];


return [
    'unicaen-code' => $settings,
];