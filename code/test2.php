<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$sql = 'update intervenant set utilisateur_code = \'dd\' where id = 196998;';
$container->get(\Application\Constants::BDD)->getConnection()->execute($sql);