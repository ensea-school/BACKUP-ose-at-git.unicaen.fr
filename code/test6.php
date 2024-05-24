<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


$expr = "of:=IF([.E25]=\"Non\";0;IF([.J25]=\"TP\";1;HLOOKUP([.J25];[$'Saisie & résultats'.S$2:.V$3];2;0)))";

$expr = "of:=[$'Saisie & résultats'.S$2:.V$3]";


$exprs = [
    "of:=[$'Saisie & résultats'.S$2:.V$3]",
    "of:=[\$Test.S$2:.V$3]",
    "of:=[.S$2:.V$3]",
    "of:=[$'Saisie & résultats'.S$2]",
    "of:=[\$Test.S$2]",
    "of:=[.S$2]",
];

foreach( $exprs as $expr) {
    $formule = new \Unicaen\OpenDocument\Calc\Formule($expr);
    echo \Unicaen\OpenDocument\Calc\Display::formule($formule);
}

/*
$expr = "of:=SUMIF([.A$25:.A$505];i_structure_code;[.AO$25:.AO$50])";
$formule = new \Unicaen\OpenDocument\Calc\Formule($expr);
echo \Unicaen\OpenDocument\Calc\Display::formule($formule);
*/