<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$sa = $container->get(\Framework\Authorize\Authorize::class);

//$a = $sa->isAllowedController(\Application\Controller\IndexController::class, 'index');

$a = $sa->isAllowedController(\Application\Controller\PeriodeController::class, 'index');

dump($a);

