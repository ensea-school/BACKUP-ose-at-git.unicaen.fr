<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$c = $container->get('config');

$d = $c['bjyauthorize']['resource_providers']['BjyAuthorize\Provider\Resource\Config'];

var_dump($d);