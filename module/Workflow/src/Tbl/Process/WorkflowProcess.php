<?php

namespace Workflow\Tbl\Process;


use Intervenant\Entity\Db\Statut;
use Unicaen\Framework\Application\Application;
use Unicaen\Framework\Cache\CacheContainerTrait;
use Application\Entity\Db\Perimetre;
use Application\Service\AnneeService;
use Intervenant\Entity\Db\Intervenant;
use Unicaen\BddAdmin\Bdd;
use Unicaen\Framework\Container\Autowire;
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

    // Résultat de l'évaluation d'une dépendance, pour le diagnostic de la feuille de route
    const DEPENDANCE_INACTIVE                = 'inactive';
    const DEPENDANCE_CLOTURE_NON_REQUISE     = 'cloture-non-requise';
    const DEPENDANCE_AUTRE_TYPE_INTERVENANT  = 'autre-type-intervenant';
    const DEPENDANCE_PRECEDANTE_ABSENTE      = 'precedante-absente';
    const DEPENDANCE_EVALUEE                 = 'evaluee';

    /**
     * @var array|IntervenantEtape[][]
     */
    private array $workflows = [];



    public function __construct(
        private readonly BddService      $bddService,
        private readonly Bdd             $bdd,
        private readonly AnneeService    $anneeService,
        private readonly WorkflowService $workflowService,

        #[Autowire(config: 'export-rh/actif')]
        private readonly bool            $exportRhEnabled,
    )
    {
    }



    /**
     * Recalcule en mémoire (sans rien enregistrer) le workflow d'un intervenant, en conservant le détail
     * de chaque décision : étapes activées par le statut et évaluation de chaque dépendance.
     *
     * @return array{workflow: IntervenantEtape[], dependances: array, sansDonnees: array<string,bool>, activation: array<string,bool>, exportRhActif: bool}
     */
    public function diagnostiquer(Intervenant $intervenant): array
    {
        $this->load(['intervenant_id' => $intervenant->getId()]);
        $workflow = $this->workflows[$intervenant->getId()] ?? [];

        $this->traitementExportRh($workflow);

        // Avant les dépendances, une étape est non atteignable uniquement si aucune donnée source ne l'alimente
        $sansDonnees = [];
        foreach ($workflow as $code => $etape) {
            $sansDonnees[$code] = !array_filter($etape->structures, fn(IntervenantEtapeStructure $s) => $s->atteignable);
        }

        $trace = [];
        $this->calculDependances($workflow, $trace);

        // Étapes activées par le statut de l'intervenant, y compris celles qui n'apparaissent pas dans son workflow
        $sql = "
        SELECT e.code etape_code, CASE " . $this->sqlActivationEtapes() . " END actif
        FROM intervenant i
          JOIN statut si ON si.id = i.statut_id
          JOIN workflow_etape e ON e.annee_id = i.annee_id AND e.histo_destruction IS NULL
        WHERE i.id = :intervenant";
        $activation = [];
        foreach ($this->bdd->select($sql, ['intervenant' => $intervenant->getId()]) as $d) {
            mpg_lower($d);
            $activation[$d['etape_code']] = (bool)$d['actif'];
        }

        return [
            'workflow'      => $workflow,
            'dependances'   => $trace,
            'sansDonnees'   => $sansDonnees,
            'activation'    => $activation,
            'exportRhActif' => $this->exportRhEnabled,
        ];
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
                $this->traitementExportRh($this->workflows[$intervenantId]);
                $this->calculDependances($this->workflows[$intervenantId]);
            }
            $this->save($tableauBord, $params);
        }
    }



    protected function traitementExportRh(array &$workflow): void
    {
        if (!$this->exportRhEnabled && array_key_exists(WorkflowEtape::EXPORT_RH, $workflow)) {
            unset($workflow[WorkflowEtape::EXPORT_RH]);
        }
    }



    /**
     * @param array|null $trace si fourni, reçoit le détail de l'évaluation de chaque dépendance (diagnostic)
     */
    protected function calculDependances(array &$workflow, ?array &$trace = null): void
    {
        foreach ($workflow as $etapeCode => $etape) {
            $dependances = $etape->etape->getDependances();
            foreach ($dependances as $dependance) {
                if (!$dependance->isActive()) {
                    // La dépendance est inactive => on passe
                    $this->tracer($trace, $etapeCode, $dependance, self::DEPENDANCE_INACTIVE);
                    continue;
                }
                $isClotureDependance = $dependance->getEtapePrecedante()->getCode() === WorkflowEtape::CLOTURE_REALISE;
                if ($isClotureDependance && !$etape->cloture) {
                    // La clôture est requise par le statut, pas par le type d'intervenant.
                    $this->tracer($trace, $etapeCode, $dependance, self::DEPENDANCE_CLOTURE_NON_REQUISE);
                    continue;
                }
                if (!$isClotureDependance && $dependance->getTypeIntervenant() && $etape->typeIntervenantId !== $dependance->getTypeIntervenant()->getId()) {
                    // La dépendance est relative à un autre type d'intervenant
                    $this->tracer($trace, $etapeCode, $dependance, self::DEPENDANCE_AUTRE_TYPE_INTERVENANT);
                    continue;
                }
                $etapePrec = $workflow[$dependance->getEtapePrecedante()->getCode()] ?? null;
                if (!$etapePrec) {
                    // Le test n'est pas pertinent => l'étape précédente n'existe pas
                    $this->tracer($trace, $etapeCode, $dependance, self::DEPENDANCE_PRECEDANTE_ABSENTE);
                    continue;
                }
                $structuresTrace = [];
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
                    $structuresTrace[$structure->structure] = ['ok' => $isOk, 'precedantes' => $precStructures];
                }
                $this->tracer($trace, $etapeCode, $dependance, self::DEPENDANCE_EVALUEE, $structuresTrace);
            }
        }
    }



    private function tracer(?array &$trace, string $etapeCode, WorkflowEtapeDependance $dependance, string $statut, array $structures = []): void
    {
        if (null === $trace) {
            return;
        }
        $trace[$etapeCode][] = ['dependance' => $dependance, 'statut' => $statut, 'structures' => $structures];
    }



    /**
     * @param int                               $avancement
     * @param array|IntervenantEtapeStructure[] $precs
     * @return bool
     */
    public function isDependanceOk(int $avancement, array $precs): bool
    {
        if (empty($precs)) {
            return false;
        }

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
            if ($prec->realisation > 0 && $prec->realisation >= $prec->objectif) {
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
                break;
            case WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT:
                if ($partiel) {
                    return true;
                }
                break;
            case WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT:
                if ($integral) {
                    return true;
                }
                break;
        }

        return false;
    }



    private function inPerimetre(string $perimetre, int $structure, int $precStructure)
    {
        switch ($perimetre) {
            case Perimetre::ETABLISSEMENT:
                return true;
            case Perimetre::COMPOSANTE:
                return $structure == $precStructure || 0 === $precStructure || 0 === $structure;
        }
    }



    protected function load(array $params = []): void
    {
        $cache = $this->getCacheContainer();

        if (empty($cache->alimentationSql) || Application::getInstance()->inDev()) {
            $cache->alimentationSql = $this->makeSql();
        }

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
                $ie->cloture             = (bool)$d['cloture'];
                $ie->intervenant         = $intevenant;

                $this->workflows[$intevenant][$etape->getCode()] = $ie;
            } else {
                $ie = $this->workflows[$intevenant][$etape->getCode()];
            }

            if (array_key_exists($structure, $ie->structures)) {
                if ($structure == 0) {
                    continue;
                }
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
          si.cloture                                           cloture,
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



    /**
     * Règles d'activation des étapes en fonction du paramétrage du statut de l'intervenant.
     *
     * @return array<array{etapes: string[], sql: string, libelle: string}>
     */
    public function reglesActivation(): array
    {
        return [
            // Candidatures, missions, indemnités fin contrat
            [
                'etapes' => [WorkflowEtape::CANDIDATURE_SAISIE,
                             WorkflowEtape::CANDIDATURE_VALIDATION],
                'sql'     => 'si.offre_emploi_postuler',
                'libelle' => 'Postuler à une offre d\'emploi',
            ],
            [
                'etapes' => [
                    WorkflowEtape::MISSION_SAISIE,
                    WorkflowEtape::MISSION_VALIDATION,
                    WorkflowEtape::MISSION_SAISIE_REALISE,
                    WorkflowEtape::MISSION_VALIDATION_REALISE,
                ],
                'sql'     => 'si.mission',
                'libelle' => 'Missions : visualisation/modification de mission',
            ],
            [
                'etapes' => [WorkflowEtape::MISSION_PRIME],
                'sql'     => 'si.mission_indemnitees',
                'libelle' => 'Gestion des indemnités de fin de contrat',
            ],


            // Données personnelles
            [
                'etapes' => [WorkflowEtape::DONNEES_PERSO_SAISIE,
                             WorkflowEtape::DONNEES_PERSO_VALIDATION],
                'sql'     => 'CASE WHEN si.dossier = 1 AND (
     si.dossier_statut = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . ' 
  OR si.dossier_identite_comp = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR si.dossier_contact = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR dossier_adresse = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR dossier_banque = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR dossier_insee = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR dossier_employeur = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR dossier_autre_1 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR dossier_autre_2 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR dossier_autre_3 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR dossier_autre_4 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  OR dossier_autre_5 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES . '
  ) THEN 1 ELSE 0 END',
                'libelle' => 'Données personnelles activées, avec au moins une rubrique demandée avant le recrutement',
            ],
            [
                'etapes' => [WorkflowEtape::DONNEES_PERSO_COMPL_SAISIE,
                             WorkflowEtape::DONNEES_PERSO_COMPL_VALIDATION],
                'sql'     => 'CASE WHEN si.dossier = 1 AND (
     si.dossier_statut = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . ' 
  OR si.dossier_identite_comp = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR si.dossier_contact = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR dossier_adresse = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR dossier_banque = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR dossier_insee = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR dossier_employeur = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR dossier_autre_1 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR dossier_autre_2 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR dossier_autre_3 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR dossier_autre_4 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  OR dossier_autre_5 = ' . Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT . '
  ) THEN 1 ELSE 0 END',
                'libelle' => 'Données personnelles activées, avec au moins une rubrique demandée après le recrutement',
            ],


            // Pièces justificatives
            [
                'etapes' => [WorkflowEtape::PJ_SAISIE,
                             WorkflowEtape::PJ_VALIDATION],
                'sql'     => "CASE
    WHEN EXISTS(
    SELECT statut_id FROM type_piece_jointe_statut tpjs WHERE tpjs.histo_destruction IS NULL AND tpjs.statut_id = si.id AND si.pj_active = 1 AND tpjs.demandee_apres_recrutement = 0
  ) THEN 1 ELSE 0 END",
                'libelle' => 'Pièces justificatives activées, avec au moins une pièce demandée avant le recrutement',
            ],
            [
                'etapes' => [WorkflowEtape::PJ_COMPL_SAISIE,
                             WorkflowEtape::PJ_COMPL_VALIDATION],
                'sql'     => "CASE
    WHEN EXISTS(
    SELECT statut_id FROM type_piece_jointe_statut tpjs WHERE tpjs.histo_destruction IS NULL AND tpjs.statut_id = si.id AND si.pj_active = 1 AND tpjs.demandee_apres_recrutement = 1
  ) THEN si.pj_active ELSE 0 END",
                'libelle' => 'Pièces justificatives activées, avec au moins une pièce demandée après le recrutement',
            ],


            // Agréments, contrat, export RH
            [
                'etapes' => [WorkflowEtape::CONSEIL_RESTREINT],
                'sql'     => 'si.conseil_restreint',
                'libelle' => 'Conseil restreint',
            ],
            [
                'etapes' => [WorkflowEtape::CONSEIL_ACADEMIQUE],
                'sql'     => 'si.conseil_aca',
                'libelle' => 'Conseil académique',
            ],
            [
                'etapes' => [WorkflowEtape::CONTRAT],
                'sql'     => 'si.contrat',
                'libelle' => 'Contrat',
            ],
            [
                'etapes' => [WorkflowEtape::EXPORT_RH],
                'sql'     => 'si.export_rh',
                'libelle' => 'Export vers le logiciel RH',
            ],


            // Enseignements
            [
                'etapes' => [WorkflowEtape::ENSEIGNEMENT_SAISIE,
                             WorkflowEtape::ENSEIGNEMENT_VALIDATION],
                'sql'     => 'si.service_prevu',
                'libelle' => 'Enseignements : prévisionnel',
            ],
            [
                'etapes' => [WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE,
                             WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE],
                'sql'     => 'si.service_realise',
                'libelle' => 'Enseignements : réalisé',
            ],


            // Référentiel
            [
                'etapes' => [WorkflowEtape::REFERENTIEL_SAISIE,
                             WorkflowEtape::REFERENTIEL_VALIDATION],
                'sql'     => 'si.referentiel_prevu',
                'libelle' => 'Référentiel : prévisionnel',
            ],
            [
                'etapes' => [WorkflowEtape::REFERENTIEL_SAISIE_REALISE,
                             WorkflowEtape::REFERENTIEL_VALIDATION_REALISE],
                'sql'     => 'si.referentiel_realise',
                'libelle' => 'Référentiel : réalisé',
            ],


            // Clôture & paiements
            [
                'etapes' => [WorkflowEtape::CLOTURE_REALISE],
                'sql'     => 'si.cloture',
                'libelle' => 'Clôture du service réalisé',
            ],
            [
                'etapes' => [WorkflowEtape::DEMANDE_MEP,
                             WorkflowEtape::SAISIE_MEP],
                'sql'     => 'si.paiement',
                'libelle' => 'Paiement',
            ],
        ];
    }



    private function sqlActivationEtapes(): string
    {
        $tests = $this->reglesActivation();


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
