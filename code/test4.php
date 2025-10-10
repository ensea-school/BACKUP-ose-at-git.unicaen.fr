<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$navigation = $container->get(\Unicaen\Framework\Navigation\Navigation::class);

dump($navigation->home->getPage('intervenant')->getPages());