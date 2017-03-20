<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */


/** @var \Application\Service\SeuilChargeService $s */
$s = $sl->get('applicationSeuilCharge');



//var_dump( $s->getBy(1,0,0,1) );

var_dump(stringToFloat('9 011,2'));