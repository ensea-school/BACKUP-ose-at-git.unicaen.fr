<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

$command = 'unoconv -f pdf -o /app/test.pdf /app/test.odt';
//$command = 'systemctl list-units';
//$command = 'ls';

exec($command, $output, $return);
var_dump($output);
var_dump($return);