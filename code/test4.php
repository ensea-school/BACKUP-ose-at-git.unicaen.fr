<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */


/** @var \Application\Service\ScenarioService $s */
$s = $sl->get('applicationScenario');

$bdd = new \Application\Connecteur\Bdd\BddConnecteur();
$bdd->setEntityManager( $s->getEntityManager() );

$bdd->execPlsql('OSE_CHARGENS.DUPLIQUER(:source, :destination, :utilisateur);', [
    'source'      => 1,
    'destination' => 20,
    'utilisateur' => 2,
]);

