<?php

namespace Workflow\Tbl\Process;


use Application\Service\Traits\AnneeServiceAwareTrait;
use Exception;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Event;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of WorkflowProcess
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class WorkflowProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
    use BddAwareTrait;
    use AnneeServiceAwareTrait;
    use WorkflowServiceAwareTrait;

    private array $tblData = [];



    public function run(TableauBord $tableauBord, array $params = []): void
    {
        if (empty($params)) {
            $annees = $this->getServiceAnnee()->getActives(true);
            foreach ($annees as $annee) {
                $this->run($tableauBord, ['ANNEE_ID' => $annee->getId()]);
            }
        } else {
            echo $this->sqlActivationEtapes();
        }
    }



    private function makeSql(): string
    {
        return "";
    }



    private function sqlActivationEtapes(): string
    {
        $tests = [
            // Candidatures, missions, indemnités fin contrat
            [
                'etapes' => [WorkflowEtape::CANDIDATURE_SAISIE, WorkflowEtape::CANDIDATURE_VALIDATION],
                'sql'    => 'si.offre_emploi_postuler',
            ],
            [
                'etapes' => [
                    WorkflowEtape::MISSION_SAISIE, WorkflowEtape::MISSION_VALIDATION,
                    WorkflowEtape::MISSION_SAISIE_REALISE, WorkflowEtape::MISSION_VALIDATION_REALISE,
                ],
                'sql'    => 'si.mission',
            ],
            [
                'etapes' => [WorkflowEtape::MISSION_PRIME],
                'sql'    => 'si.mission_indemnitees',
            ],


            // Données personnelles
            [
                'etapes' => [WorkflowEtape::DONNEES_PERSO_SAISIE, WorkflowEtape::DONNEES_PERSO_VALIDATION],
                'sql'    => 'si.dossier',
            ],
            [
                'etapes' => [WorkflowEtape::DONNEES_PERSO_COMPL_SAISIE, WorkflowEtape::DONNEES_PERSO_COMPL_VALIDATION],
                'sql'    => 'si.dossier',
            ],


            // Pièces justificatives
            [
                'etapes' => [WorkflowEtape::PJ_SAISIE, WorkflowEtape::PJ_VALIDATION],
                'sql'    => "CASE
    WHEN EXISTS(
    SELECT statut_id FROM type_piece_jointe_statut tpjs WHERE tpjs.histo_destruction IS NULL AND tpjs.statut_id = si.id AND si.pj_active = 1
  ) THEN 1 ELSE 0 END",
            ],
            [
                'etapes' => [WorkflowEtape::PJ_COMPL_SAISIE, WorkflowEtape::PJ_COMPL_VALIDATION],
                'sql'    => "CASE
    WHEN EXISTS(
    SELECT statut_id FROM type_piece_jointe_statut tpjs WHERE tpjs.histo_destruction IS NULL AND tpjs.statut_id = si.id AND si.pj_active = 1
  ) THEN si.pj_active ELSE 0 END",
            ],


            // Agréments, contrat, export RH
            [
                'etapes' => [WorkflowEtape::CONSEIL_RESTREINT],
                'sql'    => 'si.conseil_restreint',
            ],
            [
                'etapes' => [WorkflowEtape::CONSEIL_ACADEMIQUE],
                'sql'    => 'si.conseil_aca',
            ],
            [
                'etapes' => [WorkflowEtape::CONTRAT],
                'sql'    => 'si.contrat',
            ],
            [
                'etapes' => [WorkflowEtape::EXPORT_RH],
                'sql'    => '1', // @todo à retravailler
            ],


            // Enseignements
            [
                'etapes' => [WorkflowEtape::ENSEIGNEMENT_SAISIE, WorkflowEtape::ENSEIGNEMENT_VALIDATION],
                'sql'    => 'si.service_prevu',
            ],
            [
                'etapes' => [WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE, WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE],
                'sql'    => 'si.service_realise',
            ],


            // Référentiel
            [
                'etapes' => [WorkflowEtape::REFERENTIEL_SAISIE, WorkflowEtape::REFERENTIEL_VALIDATION],
                'sql'    => 'si.referentiel_prevu',
            ],
            [
                'etapes' => [WorkflowEtape::REFERENTIEL_SAISIE_REALISE, WorkflowEtape::REFERENTIEL_VALIDATION_REALISE],
                'sql'    => 'si.referentiel_realise',
            ],


            // Clôture & paiements
            [
                'etapes' => [WorkflowEtape::CLOTURE_REALISE],
                'sql'    => 'si.cloture',
            ],
            [
                'etapes' => [WorkflowEtape::DEMANDE_MEP, WorkflowEtape::SAISIE_MEP],
                'sql'    => 'si.paiement',
            ],
        ];


        $result = "";
        foreach ($tests as $test) {
            if ($result != "") {
                $result .= "\n\n";
            }

            $result .= "WHEN e.code = '" . implode("' OR e.code = '", $test['etapes']) . "' THEN\n";
            $result .= "  " . $test['sql'];
        }

        return $result;
    }
}