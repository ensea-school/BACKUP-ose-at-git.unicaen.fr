<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Entity\Db\Fichier;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\Validation;
use Application\Service\FichierService;
use Application\Service\IntervenantService;
use Application\Service\PieceJointeService;
use Application\Service\TypePieceJointeService;
use Application\Service\TypeValidationService;
use Application\Service\ValidationService;
use Application\Service\WorkflowService;

$dirs = scandir('/home/laurent/cv');
//$dirs = ['2624.pdf'];
/** @var IntervenantService $si */
$si = $sl->get(IntervenantService::class);

$typePieceJointe = $sl->get(TypePieceJointeService::class)->get(1);
$tvPJ = $sl->get(TypeValidationService::class)->get(4);
$tvFichier = $sl->get(TypeValidationService::class)->get(5);

foreach( $dirs as $dir ){
    if ($dir == '.' || $dir == '..') continue;

    $code = explode('.', $dir)[0];

    $intervenant = $si->getBySourceCode($code);

    $fichier = new Fichier();
    $fichier->setContenu(file_get_contents('/home/laurent/cv/'.$dir));
    $fichier->setNom($dir);
    $fichier->setTaille(filesize('/home/laurent/cv/'.$dir));
    $fichier->setTypeMime('application/pdf');

    $valFichier = new Validation();
    $valFichier->setIntervenant($intervenant);
    $valFichier->setTypeValidation($tvFichier);
    $valFichier->setStructure($intervenant->getStructure());
    $sl->get(ValidationService::class)->save($valFichier);
    $fichier->setValidation($valFichier);

    $pj = $sl->get(PieceJointeService::class)->getByType($intervenant, $typePieceJointe);
    if (!$pj || !$pj->estNonHistorise()) {
        $pj = new PieceJointe();
        $pj->setIntervenant($intervenant);
        $pj->setType($typePieceJointe);
        $sl->get(PieceJointeService::class)->save($pj);
    }
    $sl->get(FichierService::class)->save($fichier);

    $sql = "INSERT INTO piece_jointe_fichier(piece_jointe_id,fichier_id) values (".$pj->getId().",".$fichier->getId().")";

    /* Validation */
    if (!($pj->getValidation() && $pj->getValidation()->estNonHistorise())){
        $validation = new Validation();
        $validation->setIntervenant($intervenant);
        $validation->setTypeValidation($tvPJ);
        $validation->setStructure($intervenant->getStructure());
        $sl->get(ValidationService::class)->save($validation);
        $pj->setValidation($validation);
        $sl->get(PieceJointeService::class)->save($pj);
    }

    $sl->get(WorkflowService::class)->calculerTableauxBord([], $intervenant);

    echo $dir.' '.$intervenant->getCode()."<br />";
}
