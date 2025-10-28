<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

use Unicaen\Framework\Router\Router;

$router = $container->get(\Unicaen\Framework\Router\Router::class);

$routes = $router->getRoutes();

$ok = [
    // divers
    "/ocra_service_manager_yuml",

    "/utilisateur",
    "/utilisateur/:action[/:id]",
];


$guardProvider = $container->get(\Unicaen\Framework\Authorize\GuardProvider::class);
$authorize = $container->get(\Unicaen\Framework\Authorize\Authorize::class);

foreach ($routes as $route) {
    $controller = $route->getController();
    $action     = $route->getAction();

    if (in_array($route->getRoute(), $ok)) {
        continue;
    }

    if (empty($controller)) {
        // pas d'action derrière
        continue;
    }
    if (empty($action)) {
        continue;
    }

    $guard  = $guardProvider->get($controller, $action);

    $actionRule = $guardProvider->get($controller, $action);
    if ($actionRule && ($actionRule->privileges || $actionRule->roles || $actionRule->assertion)) {
        continue;
    }

    $controllerRule = $guardProvider->get($controller);
    if ($controllerRule && ($controllerRule->privileges || $controllerRule->roles || $controllerRule->assertion)) {
        continue;
    }

    if (!(bool)($actionRule || $controllerRule)) {
        echo '<h2>' . $route->getName() . '</h2>';
        echo "uri: ".$route->getRoute().'<br />';
        echo "controller: ".$controller.'<br />';
        echo "action: ".$action.'<br />';

        dump($controller . '.' . $action);
        dump($route);
    }
}