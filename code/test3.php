<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


$dir = getcwd() . '/data/formules/';

$fichiers = scandir($dir);

$fichiers = [
//    'FORMULE_ARTOIS.ods',
//    'FORMULE_ASSAS.ods',
//    'FORMULE_AVIGNON.ods',
//    'FORMULE_COTE_AZUR.ods',
//    'FORMULE_DAUPHINE.ods',
//    'FORMULE_GUYANE.ods',
//    'FORMULE_INSA_LYON.ods',
    'FORMULE_LILLE.ods',
//    'FORMULE_LYON2.ods',
//    'FORMULE_MONTPELLIER.ods', // ne passe pas : nouveau format
//    'FORMULE_NANTERRE.ods',
//    'FORMULE_PARIS.ods',
//    'FORMULE_PARIS1.ods',
//    'FORMULE_PARIS8.ods',
//    'FORMULE_PARIS8_2021.ods',
//    'FORMULE_PICARDIE.ods',
//    'FORMULE_POITIERS.ods',
//    'FORMULE_POITIERS_2021.ods',
//    'FORMULE_RENNES1.ods',
//    'FORMULE_RENNES2.ods',
//    'FORMULE_REUNION.ods',
//    'FORMULE_REUNION_2022.ods',
//    'FORMULE_ROUEN.ods',
//    'FORMULE_ROUEN_2022.ods',
//    'FORMULE_SACLAY.ods',
//    'FORMULE_SORBONNE_NOUVELLE.ods',
//    'FORMULE_ST_ETIENNE.ods',
//    'FORMULE_UBO.ods', // ne passe pas
//    'FORMULE_ULHN.ods',
//    'FORMULE_ULHN_2021.ods', // ne passe pas : ancien format
//    'FORMULE_UNICAEN.ods',
//    'FORMULE_UNICAEN_2016.ods', // ne passe pas : très ancien format
//    'FORMULE_UNISTRA.ods', // ne passe pas : ancien format
//    'FORMULE_UPEC.ods',
//    'FORMULE_UPEC_2022.ods',
//    'FORMULE_UVSQ.ods',
//    'TEST.ods',
];

/** @var \Formule\Service\FormulatorService $formulator */
$formulator = $container->get(\Formule\Service\FormulatorService::class);

foreach ($fichiers as $fichier) {
    if (!str_starts_with($fichier, '.')) {
        $filename = $dir . $fichier;
        echo '<h1>' . $fichier . '</h1>';
        echo '<div style="margin-left: 3em">';
        try {
            $tableur = $formulator->charger($filename);
            $tableur->test();
        }catch(\Exception $e){
            echo $e->getMessage();
        }
        echo '</div>';
    }
}