<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$introspection = \UnicaenCode\Util::introspection();

$traits = $introspection->getTraits();

foreach ($traits as $trait) {
    $params = $introspection->getTraitParams($trait);
    if ($params['aware']) {
        var_dump($params);
    }
    \UnicaenCode\Util::codeGenerator()->generer('awareTrait', [
        'class'     => $trait['targetClass'],
        'useGetter' => true,
        'subDir'    => $params['subDir'],
    ]);
}