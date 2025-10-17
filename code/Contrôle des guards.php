<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$router = $container->get(\Unicaen\Framework\Router\Router::class);

$routes = $router->getRoutes();

$ok = [
    // divers
    "/ocra_service_manager_yuml",
    "/maintenance",
    "/icons",
    "/cache/js[/:version]",
    "/cache/css[/:version]",

    // unicaen/authentification
    "/auth",
    "/auth/creation-compte",
    "/auth/change-password",
    "/auth/change-email",
    "/utilisateur",
    "/utilisateur/:action[/:id]",

    // unicaen/import
    "/import",
    "/import/tableau-bord",
    "/import/maj-vues-fonctions",
    "/import/sources",
    "/import/sources/edition[/:source]",
    "/import/sources/suppression/:source",
    "/import/differentiel",
    "/import/tables/modifier/:table",
    "/import/tables/trier",
    "/import/tables/synchro-switch",
    "/import/tables/add-non-referenced",
    "/import/tables",
    "/import/differentiel/maj-vue-materialisee[/:table]",
    "/import/differentiel/synchronisation[/:table]",
    "/import/differentiel/details[/:table]",

    // unicaen/code
    "/unicaen-code[/:view]",

    // unicaen/signature
    "/signature/internal",
    "/signature/internal/visa/:key",
    "/signature/internal/document/:key",
    "/signature/process",

    // unicaen/siham
    "/siham",
];


$guards = $container->get(\Unicaen\Framework\Authorize\GuardProvider::class)->getAll();

foreach($routes as $route) {
    $controller = $route->getController();
    $action = $route->getAction();

    $privileges = $route->getPrivileges();

    if (in_array($route->getRoute(), $ok)){
        continue;
    }

    if (empty($controller)){
        // pas d'action derrière
        continue;
    }
    if (empty($action)){
        continue;
    }

    if (!empty($privileges)){
        // privileges OK
        continue;
    }

    $guard = $guards[$controller][$action] ?? null;
    $privileges = $guard?->privileges;

    if (!empty($privileges)){
        // privileges OK
        continue;
    }

    if (!empty($guard->roles)){
        continue;
    }
    if (!empty($guard->assertion)){
        continue;
    }

    echo '<h2>'.$route->getRoute().'</h2>';
    //$url = $this->url($route->getRoute());
    //echo $url;
    dump($controller.'.'.$action);
    dump($route);
    dump($guard);
}