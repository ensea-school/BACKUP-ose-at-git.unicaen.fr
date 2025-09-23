<?php

namespace Workflow\Tbl\Process;


use Application\Cache\Traits\CacheContainerTrait;
use Application\Entity\Db\Perimetre;
use Application\Service\AnneeService;
use Intervenant\Entity\Db\Intervenant;
use Unicaen\BddAdmin\Bdd;
use UnicaenTbl\Event;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddService;
use UnicaenTbl\TableauBord;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Service\WorkflowService;
use Workflow\Tbl\Process\Model\IntervenantEtape;
use Workflow\Tbl\Process\Model\IntervenantEtapeStructure;

/**
 * Description of WorkflowProcess
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class WorkflowProcess implements ProcessInterface
{
    use CacheContainerTrait;

    /**
     * @var array|IntervenantEtape[][]
     */
    private array $workflows = [];



    public function __construct(
        private readonly BddService      $bddService,
        private readonly Bdd             $bdd,
        private readonly AnneeService    $anneeService,
        private readonly WorkflowService $workflowService,
    )
    {
    }



    /**
     * @param Intervenant $intervenant
     * @return IntervenantEtape[]
     * @throws \Exception
     */
    public function debugTrace(Intervenant $intervenant): array
    {
        $this->load(['intervenant_id' => $intervenant->getId()]);
        $this->calculDependances($this->workflows[$intervenant->getId()]);

        return $this->workflows[$intervenant->getId()];
    }



    public function run(TableauBord $tableauBord, array $params = []): void
    {
        mpg_lower($params);

        if (empty($params)) {
            $annees = $this->anneeService->getActives(true);
            foreach ($annees as $annee) {
                $this->run($tableauBord, ['annee_id' => $annee->getId()]);
            }
        } else {
            $this->load($params);
            foreach ($this->workflows as $intervenantId => $wf) {
                $this->calculDependances($this->workflows[$intervenantId]);
            }
            $this->save($tableauBord, $params);
        }
    }



    protected function calculDependances(array &$workflow)
    {
        foreach ($workflow as $etapeCode => $etape) {
            $dependances = $etape->etape->getDependances();
            foreach ($dependances as $dependance) {
                if (!$dependance->isActive()) {
                    // La dépendance est inactive => on passe
                    continue;
                }
                if ($dependance->getTypeIntervenant() && $etape->typeIntervenantId !== $dependance->getTypeIntervenant()->getId()) {
                    // La dépendance est relative à un autre type d'intervenant
                    continue;
                }
                $etapePrec = $workflow[$dependance->getEtapePrecedante()->getCode()] ?? null;
                if (!$etapePrec) {
                    // Le test n'est pas pertinent => l'étape précédente n'existe pas
                    continue;
                }
                foreach ($etape->structures as $structure) {
                    $precStructures = $etapePrec->structures;
                    foreach ($precStructures as $strId => $precStructure) {
                        if (!$this->inPerimetre($dependance->getPerimetre()->getCode(), $structure->structure, $strId)) {
                            unset($precStructures[$strId]);
                        }
                    }

                    $isOk = $this->isDependanceOk($dependance->getAvancement(), $precStructures);
                    if (!$isOk) {
                        $structure->atteignable         = false;
                        $structure->whyNonAtteignable[] = $etapePrec->etape->getDescNonFranchie();
                    }
                }
            }
        }
    }



    /**
     * @param int                               $avancement
     * @param array|IntervenantEtapeStructure[] $precs
     * @return bool
     */
    public function isDependanceOk(int $avancement, array $precs): bool
    {
        $debute   = false;
        $partiel  = false;
        $integral = true;

        foreach ($precs as $prec) {
            if ($prec->realisation > 0 || $prec->partiel > 0) {
                $debute = true;
            }
            if ($prec->partiel > 0) {
                $partiel = true;
            }
            if ($prec->realisation >= $prec->objectif) {
                $partiel = true;
            } else {
                $integral = false;
            }
        }

        switch ($avancement) {
            case WorkflowEtapeDependance::AVANCEMENT_DEBUTE:
                if ($debute) {
                    return true;
                }
            case WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT:
                if ($partiel) {
                    return true;
                }
            case WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT:
                if ($integral) {
                    return true;
                }
        }

        return false;
    }



    private function inPerimetre(string $perimetre, int $structure, int $precStructure)
    {
        switch ($perimetre) {
            case Perimetre::ETABLISSEMENT:
                return true;
            case Perimetre::COMPOSANTE:
                return $structure == $precStructure || 0 === $precStructure;
        }
    }



    protected function load(array $params = []): void
    {
        $cache = $this->getCacheContainer();

        //if (empty($cache->alimentationSql)) {
        $cache->alimentationSql = $this->makeSql();
        //}

        $this->workflows = [];

        $sql  = $this->bddService->injectKey($cache->alimentationSql, $params);
        $stmt = $this->bdd->selectEach($sql);
        while ($d = $stmt->next()) {
            mpg_lower($d);

            $etapes = $this->workflowService->getEtapes((int)$d['annee_id']);

            $intevenant = (int)$d['intervenant_id'];
            $structure  = (int)$d['structure_id'];
            $etape      = $etapes[$d['etape_code']];

            if (!array_key_exists($intevenant, $this->workflows)) {
                $this->workflows[$intevenant] = [];
            }

            if (!array_key_exists($etape->getCode(), $this->workflows[$intevenant])) {
                $ie                      = new IntervenantEtape($etape);
                $ie->annee               = (int)$d['annee_id'];
                $ie->typeIntervenantId   = (int)$d['type_intervenant_id'];
                $ie->typeIntervenantCode = $d['type_intervenant_code'];
                $ie->statut              = (int)$d['statut_id'];
                $ie->intervenant         = $intevenant;

                $this->workflows[$intevenant][$etape->getCode()] = $ie;
            } else {
                $ie = $this->workflows[$intevenant][$etape->getCode()];
            }

            if (array_key_exists($structure, $ie->structures)) {
                throw new \Exception('Erreur workflow pour l\'intervenant ID=' . $intevenant . ' : la structure ID=' . $structure . ' est référencée en double');
            }

            $ies              = new IntervenantEtapeStructure();
            $ies->structure   = $structure;
            $ies->atteignable = (bool)$d['atteignable'];
            $ies->objectif    = (float)$d['objectif'];
            $ies->partiel     = (float)$d['partiel'];
            $ies->realisation = (float)$d['realisation'];

            $ie->structures[$structure] = $ies;
        }
    }



    protected function save(TableauBord $tableauBord, array $params): void
    {
        $data = [];

        foreach ($this->workflows as $workflow) {
            foreach ($workflow as $etape) {
                foreach ($etape->structures as $etapeStructure) {
                    $d = [
                        'annee_id'              => $etape->annee,
                        'type_intervenant_id'   => $etape->typeIntervenantId,
                        'type_intervenant_code' => $etape->typeIntervenantCode,
                        'statut_id'             => $etape->statut,
                        'intervenant_id'        => $etape->intervenant,
                        'etape_id'              => $etape->etape->getId(),
                        'etape_code'            => $etape->etape->getCode(),
                        'structure_id'          => $etapeStructure->structure ?: null,
                        'atteignable'           => $etapeStructure->atteignable,
                        'objectif'              => $etapeStructure->objectif,
                        'partiel'               => $etapeStructure->partiel,
                        'realisation'           => $etapeStructure->realisation,
                        'why_non_atteignable'   => empty($etapeStructure->whyNonAtteignable) ? null : json_encode($etapeStructure->whyNonAtteignable),
                    ];
                    mpg_upper($d);
                    $data[] = $d;
                }
            }
        }

        $tableName = 'tbl_workflow';
        mpg_upper($tableName);

        // Enregistrement en BDD
        $key = $tableauBord->getOption('key');

        $table = $this->bdd->getTable($tableName);

        //         on force la DDL pour éviter de faire des requêtes en plus
        //        $table->setDdl(['sequence' => $tableauBord->getOption('sequence'), 'columns' => array_fill_keys($tableauBord->getOption('cols'), [])]);

        $options = [
            'where'              => $params,
            'return-insert-data' => false,
            'transaction'        => !isset($params['intervenant_id']),
            'callback'           => function (string $action, int $progress, int $total) use ($tableauBord) {
                $tableauBord->onAction(Event::PROGRESS, $progress, $total);
            },
        ];

        $table->merge($data, $key, $options);

        // on force le refresh des feuilles de route déjà chargées
        foreach ($this->workflows as $intervenant => $workflow) {
            $this->workflowService->refreshFeuilleDeRoute($intervenant);
        }
    }



    private function makeSql(): string
    {
        $dems       = "\n" . $this->sqlActivationEtapes();
        $subQueries = "\n" . $this->sqlAlimentation() . "\n";

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
          JOIN workflow_etape           e ON e.annee_id = i.annee_id AND 1 = CASE $dems END
          LEFT JOIN ($subQueries) w ON w.intervenant_id = i.id AND w.etape_code = e.code
        WHERE
          1=1
          /*@intervenant_id=i.id*/
          /*@annee_id=i.annee_id*/
          /*@statut_id=i.statut_id*/
        ORDER BY
          e.ordre
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
            [//DEMANDEE_APRES_RECRUTEMENT
                'etapes' => [WorkflowEtape::PJ_SAISIE, WorkflowEtape::PJ_VALIDATION],
                'sql'    => "CASE
    WHEN EXISTS(
    SELECT statut_id FROM type_piece_jointe_statut tpjs WHERE tpjs.histo_destruction IS NULL AND tpjs.statut_id = si.id AND si.pj_active = 1 AND tpjs.demandee_apres_recrutement = 0
  ) THEN 1 ELSE 0 END",
            ],
            [
                'etapes' => [WorkflowEtape::PJ_COMPL_SAISIE, WorkflowEtape::PJ_COMPL_VALIDATION],
                'sql'    => "CASE
    WHEN EXISTS(
    SELECT statut_id FROM type_piece_jointe_statut tpjs WHERE tpjs.histo_destruction IS NULL AND tpjs.statut_id = si.id AND si.pj_active = 1 AND tpjs.demandee_apres_recrutement = 1
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
        $filter = 'v_tbl_workflow_%';
        mpg_upper($filter);

        $views = $this->bdd->view()->get($filter);
        $sql   = "";
        foreach ($views as $view) {
            if ($sql != "") {
                $sql .= "\n\nUNION ALL\n\n";
            }

            $vdef = substr($view['definition'], strpos($view['definition'], "SELECT"));
            $sql  .= $vdef;
        }
        return $sql;
    }
}