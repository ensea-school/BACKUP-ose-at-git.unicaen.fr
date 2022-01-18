<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$class = \Plafond\Entity\Db\PlafondApplication::class;


echo substr($class, strrpos($class, '\\') + 1);


