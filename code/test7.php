<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$nav = $container->get(\Framework\Navigation\Navigation::class);


\UnicaenApp\Util::topChrono();
$pages = $nav->getHome()->getVisiblePages();
\UnicaenApp\Util::topChrono();

//dd($container->get('config')['bjyauthorize']['guards']['UnicaenPrivilege\Guard\PrivilegeController']);




$router = $container->get(\Framework\Router\Router::class);

$oldPlugin = $container->get('ControllerPluginManager')->get('url');


$jeu = [
    [null, [], []],
    [null, [], [], true],
    ['of', [], ],
    ['of', [], ['force_canonical' => true]],
    ['intervenant/agrement/ajouter', ['typeAgrement' => 1,'intervenant'  => 500]],
    ['intervenant/agrement/ajouter', ['typeAgrement' => 1,'intervenant'  => 500, 'structure' => 15]],
    ['intervenant/contrat', ['intervenant' => 800], ['force_canonical' => true], true],
    ['service/resume', [], ['query' => ['action' => 'trier', 'tri' => 'i=ntervénant']]],
    ['chargens/formation/json'],
];

foreach( $jeu as $params ){
    $oldUrl = $oldPlugin->fromRoute(...$params);
    $newUrl = $router->url(...$params);
    dump($oldUrl);
    dump($newUrl);
}

//dd($_SERVER);


