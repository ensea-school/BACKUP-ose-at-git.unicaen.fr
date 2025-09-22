<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$router = $container->get(\Framework\Router\Router::class);


$d = [
    ["administration", "/administration"],
    ["of", "https://ose-dev.localhost.unicaen.fr/offre-de-formation"],
    ['intervenant/agrement/ajouter', '/intervenant/500/agrement/1/ajouter'],
    ['intervenant/agrement/ajouter', '/intervenant/500/agrement/1/ajouter/45'],
];

foreach( $d as $dd ){
    $route = $router->getRoute($dd[0]);
    $url = $dd[1];

    $res = $router->routeMatch($url, $route);

    dump($route, $url, $res);
}
