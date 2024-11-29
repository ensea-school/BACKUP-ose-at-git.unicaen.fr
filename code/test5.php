<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */


    $dir = getenv('SCRIPT_LAUNCH_IR');
    if (!$dir){
        $dir = getenv('PWD');
    }
    if (!$dir){
        $dir = getcwd();
    }

var_dump($dir);