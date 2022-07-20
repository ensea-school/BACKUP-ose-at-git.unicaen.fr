<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Application\Service\ContextService $c */
$c = $container->get(\Application\Service\ContextService::class);

$roleId = $c->getSelectedIdentityRole()->getRoleId();
var_dump($roleId);


$p = $c->getIntervenant()->getStatut()->getPrivileges();
foreach ($p as $priv => $ok) {
    if (!$ok) unset($p[$priv]);
}

//$p = $r->getPrivileges();

var_dump(array_keys($p));