<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */


use Intervenant\Entity\Db\Intervenant;

$params = \UnicaenCode\Util::codeGenerator()->generer([
                                                          'intervenant' => [
                                                              'type'  => 'text',
                                                              'label' => 'ID Intervenant',
                                                          ],
                                                      ]);

if (!$params['intervenant']) {
    return;
}

$entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
$tableauBordService = $container->get(\UnicaenTbl\Service\TableauBordService::class);
$ws = $container->get(\Workflow\Service\WorkflowService::class);

/** @var \Workflow\Tbl\Process\WorkflowProcess $tblWorkflow */
$tblWorkflow = $tableauBordService->getTableauBord('workflow')->getProcess();
$intervenant = $entityManager->getRepository(Intervenant::class)->find($params['intervenant']);

try {
    $tableauBordService->calculer('workflow', ['intervenant_id' => $intervenant->getId()]);
}catch(\Throwable $t){
    echo '<div class="alert alert-danger">'.$t->getMessage().'</div>';
}

$workflow = $ws->getEtapes($intervenant->getAnnee());
$debugTrace = $tblWorkflow->debugTrace($intervenant);
$fdr = $ws->getFeuilleDeRoute($intervenant);
$fdrEtapes = $fdr->getEtapes();

echo $intervenant->getPrenom().' '.$intervenant->getNomUsuel().'<br />'.$intervenant->getStatut()->getTypeIntervenant();

echo $this->vue('workflow/feuille-de-route', ['intervenant' => $intervenant->getId()]);

foreach( $workflow as $wfEtape ){
    if (isset($debugTrace[$wfEtape->getCode()]) || isset($fdrEtapes[$wfEtape->getCode()])) {
        echo '<h2>' . $wfEtape->getLibelle() . '</h2>';
        echo '<div style="margin-left: 2em">';
        if (isset($debugTrace[$wfEtape->getCode()])) {
            $dt = $debugTrace[$wfEtape->getCode()];
            echo '<div><b>Périmètre étape :</b>' . $dt->etape->getPerimetre() . '</div>';

            echo '<h4>Dépendances :</h4>';
            echo '<div style="margin-left: 2em">';
            foreach ($dt->etape->getDependances() as $dependance) {
                if ($dependance->isActive()) {
                    echo '<h5>Dépendance à l\'étape ' . $dependance->getEtapePrecedante()->getLibelle() . '</h5>';
                    if ($dependance->getTypeIntervenant()) {
                        echo '<div><b>Type Intervenant :</b>' . $dependance->getTypeIntervenant() . '</div>';
                    }
                    echo '<div><b>Périmètre :</b>' . $dependance->getPerimetre() . '</div>';
                    echo '<div><b>Avancement :</b>' . $dependance->getAvancementLibelle() . '</div>';
                    if (isset($debugTrace[$dependance->getEtapePrecedante()->getCode()])) {
                        $precStructs = $debugTrace[$dependance->getEtapePrecedante()->getCode()]->structures;
                        if (!empty($precStructs)) {
                            showStructures($precStructs);
                        }
                    }
                }
            }
            echo '</div>';


            if (!empty($dt->structures)) {
                showStructures($dt->structures);
            }

            dump($dt);
        }

        if (isset($fdrEtapes[$wfEtape->getCode()])){
            dump($fdrEtapes[$wfEtape->getCode()]);
        }

        echo '</div>';
    }


}


/**
 * @param array|\Workflow\Tbl\Process\Model\IntervenantEtapeStructure[] $structures
 * @return void
 */
function showStructures(array $structures): void
{
    echo '<h4>Structures :</h4>';
    echo '<div style="margin-left: 2em">';
    echo '<table class="table table-bordered table-xs">';
    echo '<tr><th>Structure</th><th>Atteignable</th><th>Objectif</th><th>Partiel</th><th>Realisation</th></tr>';
    foreach ($structures as $structure) {
        echo '<tr><td>'.$structure->structure.'</td>' .
            '<td>'.($structure->atteignable ? 'Oui' : 'Non').'</td>' .
            '<td>'.$structure->objectif.'</td>' .
            '<td>'.$structure->partiel.'</td>' .
            '<td>'.$structure->realisation.'</td>' .
            '</tr>';

    }
    echo '</table>';
    echo '</div>';
}