<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

use Application\Entity\Db\Fichier;
use Application\Service\FichierService;
use Intervenant\Service\IntervenantService;
use PieceJointe\Entity\Db\PieceJointe;
use PieceJointe\Service\PieceJointeService;
use PieceJointe\Service\TypePieceJointeService;
use Workflow\Entity\Db\Validation;
use Workflow\Service\TypeValidationService;
use Workflow\Service\ValidationService;

$repertoire = '/app/cache/2020/';
$typeMime   = 'application/msword';

$dirs = scandir($repertoire);
//$dirs = ['139755.doc'];
/** @var IntervenantService $si */
$si = $container->get(IntervenantService::class);

$typePieceJointe = $container->get(TypePieceJointeService::class)->get(1);
$tvPJ            = $container->get(TypeValidationService::class)->get(4);
$tvFichier       = $container->get(TypeValidationService::class)->get(5);
$deposants       = [1, 4]; // IS utilisateurs de OseAppli et Laurent

function makeNomPj(string $codeIntervenant)
{
    return 'CV-GEN-2020.doc';
}

$sql = "
SELECT
  i.id  intervenant_id,
  i.code intervenant_code,
  pj.id piece_jointe_id,
  f.id  fichier_id,
  f.nom fichier_nom,
  u.id deposant_id,
  u.display_name deposant
FROM
            intervenant            i
  LEFT JOIN piece_jointe          pj ON pj.intervenant_id = i.id AND pj.histo_destruction IS NULL AND pj.type_piece_jointe_id = :type_piece_jointe
  LEFT JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
  LEFT JOIN fichier                f ON f.id = pjf.fichier_id AND f.histo_destruction IS NULL
  LEFT JOIN utilisateur            u ON u.id = f.histo_createur_id
WHERE
  i.annee_id = 2020
";

$intervenants          = [];
$intervenantsInvalides = [];

$da = $si->getEntityManager()->getConnection()->fetchAllAssociative($sql, [
    'type_piece_jointe' => $typePieceJointe->getId(),
]);
foreach ($da as $d) {
    $code = $d['INTERVENANT_CODE'];
    if (!isset($intervenants[$code])) {
        $intervenants[$code] = [
            'intervenant-id'  => (int)$d['INTERVENANT_ID'],
            'piece-jointe-id' => $d['PIECE_JOINTE_ID'],
            'deja-fait'       => false,
            'fichier'         => null,
        ];
    }
    if (!$intervenants[$code]['deja-fait']) {
        $deposantId                       = (int)$d['DEPOSANT_ID'];
        $intervenants[$code]['deja-fait'] = (
            in_array($deposantId, $deposants)
            && $d['FICHIER_NOM'] == makeNomPj($code)
        );
    }
}

$dirs = scandir($repertoire);
foreach ($dirs as $dir) {
    if ($dir == '.' || $dir == '..') continue;
    $code = substr($dir, 0, -4);
    if (!isset($intervenants[$code])) {
        $intervenantsInvalides[] = $code;
    } else {
        $intervenants[$code]['fichier'] = $repertoire . $dir;
    }
}

if (count($intervenantsInvalides) > 0) {
    echo '<h1>Intervenants Invalides</h1>';
    echo implode(',<br /> ', $intervenantsInvalides);
    die();
}

foreach ($intervenants as $code => $intervenant) {
    if ($intervenant['deja-fait']) {
        unset($intervenants[$code]);
    }
    if (!$intervenant['fichier']) {
        unset($intervenants[$code]);
    }
}

//var_dump($intervenants);
//die();

$count = count($intervenants);
$index = 0;
foreach ($intervenants as $code => $interv) {
    $index++;
    echo "Intervenant $index / $count, code $code ... ";
    $intervenant = $si->get($interv['intervenant-id']);
    try {
        $fichier = new Fichier();
        $fichier->setContenu(file_get_contents($interv['fichier']));
        $fichier->setNom(makeNomPj($code));
        $fichier->setTaille(filesize($interv['fichier']));
        $fichier->setTypeMime($typeMime);

        $valFichier = new Validation();
        $valFichier->setIntervenant($intervenant);
        $valFichier->setTypeValidation($tvFichier);
        $valFichier->setStructure($intervenant->getStructure());
        $container->get(ValidationService::class)->save($valFichier);
        $fichier->setValidation($valFichier);

        if ($interv['piece-jointe-id']) {
            $pj = $container->get(PieceJointeService::class)->get($interv['piece-jointe-id']);
        } else {
            $pj = new PieceJointe();
            $pj->setIntervenant($intervenant);
            $pj->setType($typePieceJointe);
            $container->get(PieceJointeService::class)->save($pj);
        }

        $container->get(FichierService::class)->save($fichier, 'bdd');

        $sql = "INSERT INTO piece_jointe_fichier(piece_jointe_id,fichier_id) values (" . $pj->getId() . "," . $fichier->getId() . ")";
        $si->getEntityManager()->getConnection()->executeStatement($sql);

        // Validation
        if (!($pj->getValidation() && $pj->getValidation()->estNonHistorise())) {
            $validation = new Validation();
            $validation->setIntervenant($intervenant);
            $validation->setTypeValidation($tvPJ);
            $validation->setStructure($intervenant->getStructure());
            $container->get(ValidationService::class)->save($validation);
            $pj->setValidation($validation);
            $container->get(PieceJointeService::class)->save($pj);
        }
        //$container->get(WorkflowService::class)->calculerTableauxBord([], $intervenant);
    } catch (\Exception $e) {
        echo 'ERREUR intervenant ' . $code . ' : ' . $e->getMessage();
    }
    echo "\n";
}