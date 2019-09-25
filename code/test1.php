<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Interop\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$r = \UnicaenCode\Util::introspection()->getForms();
//$r = $container->get('FormElementManager')->get('UnicaenImport\Form\SourceForm');
phpDump($r);



