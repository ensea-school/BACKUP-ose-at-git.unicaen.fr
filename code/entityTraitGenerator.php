<h1>Génération des aware traits de getters/setters d\'entités</h1>
<?php

/**
 * @var $this \Zend\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName string
 */

use UnicaenCode\Util;

$outputdir = '/tmp/entityTraits/';

$sIntrospection = $controller->getServiceLocator()->get('UnicaenCode\Introspection');
/* @var $sIntrospection \UnicaenCode\Service\Introspection */

$sCodeGenerator = $controller->getServiceLocator()->get('UnicaenCode\CodeGenerator');
/* @var $sCodeGenerator \UnicaenCode\Service\CodeGenerator */

$entities = $sIntrospection->getEntities();

$sCodeGenerator->setTemplate('EntityTrait');
foreach( $entities as $entity ){

    $entityPath  = Util::namespaceClass($entity);
    $entityClass = Util::baseClassName($entity);
    $entityParam = lcfirst($entityClass);

    $sCodeGenerator->setParams( compact('entityPath', 'entityClass', 'entityParam') );
    $sCodeGenerator->generateToFile($outputdir, $entityClass.'AwareTrait.php');
}
?>
Résultats dans <b><?php echo $outputdir ?></b>