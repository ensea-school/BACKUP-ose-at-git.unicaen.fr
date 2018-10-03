<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Entity\Db\ModeleContrat;
use Application\Service\ModeleContratService;

/** @var ModeleContrat[] $mcs */
$mcs = $sl->get(ModeleContratService::class)->getList();

foreach( $mcs as $mc){
    var_dump($mc);

    var_dump($mc->hasFichier());
}