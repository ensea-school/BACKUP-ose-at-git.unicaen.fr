<?php

namespace Workflow\Tbl\Process;


use Application\Service\Traits\AnneeServiceAwareTrait;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;
use Workflow\Tbl\Process\Model\WfEtape;

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
            $this->load($params);
        }
    }



    public function load(array $params = []): void
    {
        $sql = $this->makeSql();
        $sql = $this->getServiceBdd()->injectKey($sql, $params);

        $etapes = $this->getServiceWorkflow()->getEtapes();

        $stmt = $this->bdd->selectEach($sql);
        while( $d = $stmt->next()){
            $wfEtape = new WfEtape();
            $wfEtape->annee = (int)$d['ANNEE_ID'];
            $wfEtape->intervenant = (int)$d['INTERVENANT_ID'];
            $wfEtape->etape = $etapes[$d['ETAPE_CODE']];
            $wfEtape->structure = (int)$d['STRUCTURE_ID'];

            $wfEtape->atteignable = (bool)$d['ATTEIGNABLE'];

            $wfEtape->objectif = (float)$d['OBJECTIF'];
            $wfEtape->partiel = (float)$d['PARTIEL'];
            $wfEtape->realisation = (float)$d['REALISATION'];

            if (!array_key_exists($wfEtape->intervenant, $this->tblData)) {
                $this->tblData[$wfEtape->intervenant] = [];
            }
            $this->tblData[$wfEtape->intervenant][] = $wfEtape;
        }
    }



    private function makeSql(): string
    {
        $dems = "\n".$this->sqlActivationEtapes();
        $subQueries = "\n".$this->sqlAlimentation()."\n";

        return "
        SELECT
          i.annee_id                                           annee_id,
          i.id                                                 intervenant_id,
          e.code                                               etape_code,
          w.structure_id                                       structure_id,
          COALESCE(w.objectif,0)                               objectif,
          COALESCE(w.partiel,0)                                partiel,
          CASE WHEN w.intervenant_id IS NULL THEN 0 ELSE 1 END atteignable,
          ROUND(COALESCE(w.realisation,0),2)                   realisation,
          i.statut_id                                          statut_id,
          ti.id                                                type_intervenant_id,
          ti.code                                              type_intervenant_code
        FROM
          intervenant                   i
          JOIN statut                  si ON si.id = i.statut_id
          JOIN type_intervenant        ti ON ti.id = si.type_intervenant_id
          JOIN workflow_etape           e ON 1 = CASE $dems END
          LEFT JOIN ($subQueries) w ON w.intervenant_id = i.id AND w.etape_code = e.code
        WHERE
          w.intervenant_id IS NOT NULL
          /*@INTERVENANT_ID=i.id*/
          /*@ANNEE_ID=i.annee_id*/
          /*@STATUT_ID=i.statut_id*/
        ";
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



    protected function sqlAlimentation(): string
    {
        $views = $this->bdd->view()->get('V_TBL_WORKFLOW_%');
        $sql = "";
        foreach( $views as $view ) {
            if ($sql != "") {
                $sql .= "\n\nUNION ALL\n\n";
            }

            $vdef = substr($view['definition'], strpos($view['definition'], "SELECT"));
            $sql .= $vdef;
        }
        return $sql;
    }
}