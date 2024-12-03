<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$value = 5.179941753252348;
$value = 45.179941753252348;
$value = 5.001;
$value = 5.1;
$value = 6.05;

//$value = 15.0;

$dVal = $value * 100;
$strVal = (string)$dVal;
$dotPos = strpos($strVal,'.');
if (false !== $dotPos){
    $intVal = (int)substr($strVal, 0, $dotPos);
    $dVal -= $intVal;
    $diff = (int)(round($dVal, 2)*100);
}else{
    $diff = 0;
}
var_dump($diff);