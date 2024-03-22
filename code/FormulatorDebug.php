<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Formule\Service\FormulatorService $fs */
$fs = $container->get(\Formule\Service\FormulatorService::class);

$dir = getcwd() . '/data/formules/';
$fichiers = scandir($dir);
$options = [];
foreach($fichiers as $fichier){
    if (!str_starts_with($fichier,'.')){
        $fichier = str_replace('.ods', '', $fichier);
        $options[$fichier] = $fichier;
    }
}

$params = \UnicaenCode\Util::codeGenerator()->generer([
    'formule' => [
        'type' => 'select',
        'label' => 'Formule à générer',
        'options' => $options,
    ],
]);

if (!$params['formule']){
    return;
}


/** @var \Formule\Service\FormulatorService $formulator */
$formulator = $container->get(\Formule\Service\FormulatorService::class);

/** @var \Formule\Service\TraducteurService $traducteur */
$traducteur = $container->get(\Formule\Service\TraducteurService::class);
$traducteur->setDebug(true);

$filename = $dir.'/'.$params['formule'].'.ods';
$tableur = $formulator->charger($filename);

phpDump($formulator->traduire($tableur));
