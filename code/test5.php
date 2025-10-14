<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$regex = '[0-9]*';

$value = '789a';


$result = preg_match('(^' . $regex . '$)', $value, $matches);


dump($result);