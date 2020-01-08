<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
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

$repertoire = '/app/CV OSE/';
$typeMime   = 'application/msword';

$dirs = scandir($repertoire);
//$dirs = ['139755.doc'];
/** @var IntervenantService $si */
$si = $container->get(IntervenantService::class);

$typePieceJointe = $container->get(TypePieceJointeService::class)->get(1);
$tvPJ            = $container->get(TypeValidationService::class)->get(4);
$tvFichier       = $container->get(TypeValidationService::class)->get(5);

foreach ($dirs as $i => $dir) {
    if (0 <= $i && $i < 9999999) {
        if ($dir == '.' || $dir == '..') continue;
        $code = explode('.', $dir)[0];

        echo ($i + 1) . '/' . (count($dirs) - 2) . ' ... ' . $code . ' - ';

        $intervenant = $si->getBySourceCode($code);

        if ($intervenant) {
            try {

                $sql     = "
                SELECT
                  count(*) NB
                FROM
                  fichier f
                  JOIN piece_jointe_fichier pjf ON pjf.fichier_id = f.id
                  JOIN piece_jointe pj ON pj.id = pjf.piece_jointe_id
                WHERE
                  f.nom = :nomFichier
                  AND pj.intervenant_id = :intervenant 
                ";
                $fExists = $si->getEntityManager()->getConnection()->fetchAll($sql, ['nomFichier' => $dir, 'intervenant' => $intervenant->getId()]);
                $fExists = (int)$fExists[0]['NB'] > 0;

                if ($fExists) {
                    echo 'CV Existe déjà';
                } else {
                    $fichier = new Fichier();
                    $fichier->setContenu(file_get_contents($repertoire . $dir));
                    $fichier->setNom($dir);
                    $fichier->setTaille(filesize($repertoire . $dir));
                    $fichier->setTypeMime($typeMime);

                    $valFichier = new Validation();
                    $valFichier->setIntervenant($intervenant);
                    $valFichier->setTypeValidation($tvFichier);
                    $valFichier->setStructure($intervenant->getStructure());
                    $container->get(ValidationService::class)->save($valFichier);
                    $fichier->setValidation($valFichier);

                    $pj = $container->get(PieceJointeService::class)->getByType($intervenant, $typePieceJointe);
                    if (!$pj || !$pj->estNonHistorise()) {
                        $pj = new PieceJointe();
                        $pj->setIntervenant($intervenant);
                        $pj->setType($typePieceJointe);
                        $container->get(PieceJointeService::class)->save($pj);
                    }
                    $container->get(FichierService::class)->save($fichier);

                    $sql = "INSERT INTO piece_jointe_fichier(piece_jointe_id,fichier_id) values (" . $pj->getId() . "," . $fichier->getId() . ")";
                    $si->getEntityManager()->getConnection()->exec($sql);

                    /* Validation */
                    if (!($pj->getValidation() && $pj->getValidation()->estNonHistorise())) {
                        $validation = new Validation();
                        $validation->setIntervenant($intervenant);
                        $validation->setTypeValidation($tvPJ);
                        $validation->setStructure($intervenant->getStructure());
                        $container->get(ValidationService::class)->save($validation);
                        $pj->setValidation($validation);
                        $container->get(PieceJointeService::class)->save($pj);
                    }

                    $container->get(WorkflowService::class)->calculerTableauxBord([], $intervenant);
                    echo 'CV Inséré';
                }
            } catch (\Exception $e) {
                echo 'ERREUR inconnue';
            }
        } else {
            echo 'ERREUR : intervenant inexistant';
        }
        echo "\n";
    }
}
