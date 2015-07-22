<?php

use UnicaenCode\Util;


$outputdir = '/tmp/entityInterfaces/';

$sIntrospection = $controller->getServiceLocator()->get('UnicaenCode\Introspection');
/* @var $sIntrospection \UnicaenCode\Service\Introspection */

$sCodeGenerator = $controller->getServiceLocator()->get('UnicaenCode\CodeGenerator');
/* @var $sCodeGenerator \UnicaenCode\Service\CodeGenerator */

$entities = $sIntrospection->getEntities();

$sCodeGenerator->setTemplate('EntityInterface');
foreach( $entities as $entity ){

    $entityPath  = Util::namespaceClass($entity);
    $entityClass = Util::baseClassName($entity);
    $entityParam = lcfirst($entityClass);

    $sCodeGenerator->setParams( compact('entityPath', 'entityClass', 'entityParam') );
    $sCodeGenerator->generateToFile($outputdir, $entityClass.'AwareInterface.php');
}
?>
<h1>Génération des aware interfaces de getters/setters d\'entités</h1>
Résultats dans <b><?php echo $outputdir ?></b>