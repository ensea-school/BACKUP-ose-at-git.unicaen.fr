<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/* @var $a \BjyAuthorize\Service\Authorize */
$a = $container->get(\BjyAuthorize\Service\Authorize::class);

$gs = $a->getGuards();
foreach ($gs as $g) {
    if ($g instanceof \UnicaenAuth\Guard\PrivilegeController) {
        $pgc = $g;
    }
}


$r = $g->getRules()['allow'];
