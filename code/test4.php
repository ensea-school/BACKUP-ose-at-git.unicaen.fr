<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$router = $container->get(\Unicaen\Framework\Router\Router::class);

$route = $router->getCurrentRoute()->getName();

dump($route);