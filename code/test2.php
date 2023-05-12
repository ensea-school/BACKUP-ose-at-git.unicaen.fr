<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Application\Connecteur\LdapConnecteur $c */
$c = $container->get(\Application\Connecteur\LdapConnecteur::class);

?>
