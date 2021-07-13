<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


$cg = \UnicaenCode\Util::codeGenerator();


$params = [
    'generator' => 'AwareInterface',
    //'write'     => false,
    'echo'      => true,

    'classname' => \Plafond\Service\PlafondService::class,
    //'useGetter' => true,
    'subDir'    => false,
];


$cg->generate($params);


$cg = \UnicaenCode\Util::codeGenerator();


$params = [
    'generator' => 'Factory',
    //'write'     => false,
    'echo'      => true,

    'classname' => \Plafond\Form\PlafondApplicationForm::class,
    'subDir'    => true,
    'type'      => 'Form',
];


$cg->generate($params);