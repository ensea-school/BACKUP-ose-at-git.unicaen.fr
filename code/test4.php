<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$navigation = $container->get(\Unicaen\Framework\Navigation\Navigation::class);

$page = $navigation->home->getPage('chargens')->getPage('seuil');

dump($page->getUri(['scenario' => 1]));

dump($page->getUri());