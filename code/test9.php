<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$s = $container->get(\Framework\Navigation\Navigation::class);

dump($s->home->getPages());

$pages = $s->home->getPage('intervenant');

dd($pages);