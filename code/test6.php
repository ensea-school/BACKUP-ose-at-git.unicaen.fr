<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Entity\Db\ElementPedagogique;

$sl->get('applicationelementpedagogique')->getEntityManager()->getFilters()->enable('historique')->init([
    \Application\Entity\Db\VolumeHoraireEns::class,
]);


/** @var ElementPedagogique $ep */
$ep = $sl->get('applicationelementpedagogique')->get(45791);

$vhes = $ep->getVolumeHoraireEns();

foreach( $vhes as $vhe ){
    var_dump($vhe->getId() );
}