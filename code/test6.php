<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Controller\PlafondController;
use Doctrine\ORM\EntityManager;

$paf = new PlafondController();

var_dump($sl->has(EntityManager::class));

\UnicaenApp\Util::topChrono();
for( $i = 0; $i < 1; $i++ ){
    \Application\Util::injectFromTraits($sl,$paf);
}
echo \UnicaenApp\Util::topChrono();
