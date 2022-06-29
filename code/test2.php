<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


$file  = getcwd() . '/cache/Téléchargement/OSE-calculHC-Rennes2-20211101.xlsx';
$sheet = 1;
//$file  = getcwd() . '/cache/test.ods';
//$sheet = 0;
/*
$document = new \Unicaen\OpenDocument\Document();
$document->loadFromFile($file);

$s = $document->getCalc()->getSheet($sheet);
//xmlDump($document->getContent());
$c = $s->getCell('BG15');

$rc = array_keys($s->getRefCells($c));
var_dump($rc);
//echo $s->html();
  */

$formule = '[.AG15]>=[.AF16]';

foreach ($formules as $f) {
    $formule = new \Unicaen\OpenDocument\Calc\Formule($f);
    $formule->analyse();
    $formule->displayTerms();
    $formule->displayExprs();
}