<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$sa = $container->get(\Framework\Authorize\Authorize::class);

$a = $container->get(\Framework\Navigation\Navigation::class)->home->getPage('intervenant')->getPage('validation-referentiel-prevu');


$a = $a->isVisible();
dump($a);

