<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Service\ElementPedagogique;


/** @var ElementPedagogique $sep *
$sep = $sl->get('applicationElementPedagogique');

$ep = $sep->get(4500);
//$ep = $sep->get(7535);

$sep->forcerTauxMixite($ep, 0.5, 0.5,0);
*/



//var_dump($ep);

$epid = 45788;

/* @var \Application\Entity\Db\ElementPedagogique $ep */
$ep = $sl->get('applicationElementPedagogique')->get($epid);

$tis = $ep->getTypesInterventionPossibles();

foreach( $tis as $ti ){
    var_dump($ti->getCode());
}