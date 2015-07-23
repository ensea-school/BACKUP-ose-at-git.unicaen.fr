<h1>Génération des aware traits d\'accès aux services</h1>
<?php

/**
 * @var $this \Zend\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName string
 */

$outputdir = '/tmp/serviceTraits/';

$sIntrospection = $controller->getServiceLocator()->get('UnicaenCode\Introspection');
/* @var $sIntrospection \UnicaenCode\Service\Introspection */

$sCodeGenerator = $controller->getServiceLocator()->get('UnicaenCode\CodeGenerator');
/* @var $sCodeGenerator \UnicaenCode\Service\CodeGenerator */



$services = $sIntrospection->getServices('Application\\Service\\', 'AbstractService');

$sCodeGenerator->setTemplate('ServiceTrait');
foreach( $services as $entityClass ){
    $sCodeGenerator->setParams( compact('entityClass') );
    $sCodeGenerator->generateToFile($outputdir, $entityClass.'AwareTrait.php');
}

?>
Résultats dans <b><?php echo $outputdir ?></b>