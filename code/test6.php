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
        if ($params['targetClass']) {
            \UnicaenCode\Util::codeGenerator()->generer('awareTrait', [
                'class'     => $params['targetClass'],
                'useGetter' => true,
                'subDir'    => $params['subDir'],
                'expanded'  => false,
            ]);
        } else {
            echo "ATTENTION : L'awareTrait $trait ne fait référence à aucune classe connue et identifiée<br />\n";
        }
    }
}

$interfaces = $introspection->getInterfaces();
foreach ($interfaces as $interface) {
    $params = $introspection->getInterfaceParams($interface);
    if ($params['aware']) {
        if ($params['targetClass']) {
            \UnicaenCode\Util::codeGenerator()->generer('awareInterface', [
                'class'     => $params['targetClass'],
                'useGetter' => true,
                'subDir'    => $params['subDir'],
                'expanded'  => false,
            ]);
        } else {
            echo "ATTENTION : L'awareInterface' $interface ne fait référence à aucune classe connue et identifiée<br />\n";
        }
    }
}