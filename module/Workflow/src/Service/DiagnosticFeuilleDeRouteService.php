<?php

namespace Workflow\Service;

use Application\Entity\Db\Perimetre;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Unicaen\BddAdmin\Bdd;
use Unicaen\Framework\Router\Router;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenTbl\Service\TableauBordService;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Model\FeuilleDeRouteEtape;
use Workflow\Tbl\Process\Model\IntervenantEtape;
use Workflow\Tbl\Process\Model\IntervenantEtapeStructure;
use Workflow\Tbl\Process\WorkflowProcess;

/**
 * Diagnostic de la feuille de route d'un intervenant : pour chaque étape du workflow, explique pourquoi elle est
 * (ou n'est pas) proposée, accessible, visible, et ce que donne chacune de ses conditions.
 *
 * Le workflow est recalculé en mémoire avec le même code que le tableau de bord, sans rien enregistrer.
 */
class DiagnosticFeuilleDeRouteService
{
    use ContextServiceAwareTrait;
    use EntityManagerAwareTrait;
    use WorkflowServiceAwareTrait;

    const ETAT_NON_PREVUE  = 'non-prevue';
    const ETAT_DESACTIVEE  = 'desactivee';
    const ETAT_FRANCHIE    = 'franchie';
    const ETAT_EN_COURS    = 'en-cours';
    const ETAT_A_FAIRE     = 'a-faire';
    const ETAT_BLOQUEE     = 'bloquee';

    const CONDITION_REMPLIE      = 'remplie';
    const CONDITION_NON_REMPLIE  = 'non-remplie';
    const CONDITION_PARTIELLE    = 'partielle';
    const CONDITION_IGNOREE      = 'ignoree';

    const ETATS_LIBELLES = [
        self::ETAT_NON_PREVUE => 'Non prévue',
        self::ETAT_DESACTIVEE => 'Désactivée',
        self::ETAT_FRANCHIE   => 'Terminée',
        self::ETAT_EN_COURS   => 'En cours',
        self::ETAT_A_FAIRE    => 'À faire',
        self::ETAT_BLOQUEE    => 'Bloquée',
    ];

    /** @var array<int,string> */
    private array $structures = [];



    public function __construct(
        private readonly TableauBordService $tableauBordService,
        private readonly Bdd                $bdd,
        private readonly Router             $router,
    )
    {
    }



    public function diagnostiquer(Intervenant $intervenant): array
    {
        /** @var WorkflowProcess $process */
        $process    = $this->tableauBordService->getTableauBord(TblProvider::WORKFLOW)->getProcess();
        $diagnostic = $process->diagnostiquer($intervenant);

        /** @var IntervenantEtape[] $workflow */
        $workflow = $diagnostic['workflow'];
        $etapes   = $this->getServiceWorkflow()->getEtapes($intervenant->getAnnee());
        $regles   = $this->reglesParEtape($process->reglesActivation());
        $this->chargerStructures($workflow);

        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        $courante       = $feuilleDeRoute->getCourante()?->getCode();
        $statut         = $intervenant->getStatut();

        $lignes = [];
        foreach ($etapes as $code => $etape) {
            $lignes[] = $this->diagnostiquerEtape(
                $etape,
                $workflow[$code] ?? null,
                $diagnostic['activation'][$code] ?? false,
                $regles[$code] ?? null,
                $diagnostic['dependances'][$code] ?? [],
                $workflow,
                $code === WorkflowEtape::EXPORT_RH && !$diagnostic['exportRhActif'],
                $diagnostic['sansDonnees'][$code] ?? false,
                $code === $courante,
                $feuilleDeRoute->get($code),
                $statut->getLibelle(),
            );
        }

        $ecarts = $this->ecartsAvecFeuilleEnregistree($intervenant, $workflow, $etapes);

        $resultat = [
            'intervenant' => [
                'nom'             => (string)$intervenant,
                'statut'          => $statut->getLibelle(),
                'typeIntervenant' => $statut->getTypeIntervenant()->getLibelle(),
                // getEtapes() charge l'année en objet partiel (id seul) : le libellé est reconstruit
                'annee'           => $intervenant->getAnnee()->getId() . '/' . ($intervenant->getAnnee()->getId() + 1),
                'structure'       => $intervenant->getStructure()?->getLibelleCourt(),
            ],
            'utilisateur' => [
                'nom'       => (string)$this->getServiceContext()->getUtilisateur(),
                'role'      => $this->getServiceContext()->getAffectation()?->getRole()?->getLibelle()
                    ?? ($this->getServiceContext()->getIntervenant() ? 'Intervenant' : null),
                'structure' => $this->getServiceContext()->getStructure()?->getLibelleCourt(),
            ],
            'aJour'       => empty($ecarts),
            'ecarts'      => $ecarts,
            'etapes'      => $lignes,
            'date'        => date('d/m/Y à H:i'),
        ];
        $resultat['texte'] = $this->texte($resultat);

        return $resultat;
    }



    private function diagnostiquerEtape(
        WorkflowEtape        $etape,
        ?IntervenantEtape    $calcul,
        bool                 $activee,
        ?string              $regle,
        array                $traces,
        array                $workflow,
        bool                 $exportRhDesactive,
        bool                 $sansDonnees,
        bool                 $courante,
        ?FeuilleDeRouteEtape $enregistree,
        string               $statut,
    ): array
    {
        $ligne = [
            'code'       => $etape->getCode(),
            'ordre'      => $etape->getOrdre(),
            'libelle'    => $etape->getLibelleAutres(),
            'courante'   => $courante,
            'activation' => [
                'prevue' => $activee && !$exportRhDesactive,
                'regle'  => $regle,
                'texte'  => null,
            ],
            'visibilite' => null,
            'avancement' => null,
            'structures' => [],
            'conditions' => [],
            'alerte'     => null,
        ];

        // 1. L'étape est-elle prévue pour cet intervenant ?
        if (!$activee) {
            $ligne['etat']                = self::ETAT_NON_PREVUE;
            $ligne['activation']['texte'] = $regle
                ? "L'option « $regle » n'est pas activée dans le paramétrage du statut « $statut »."
                : "Cette étape n'est rattachée à aucune option du statut.";
            $ligne['resume']              = 'Étape non prévue pour cet intervenant : ' . lcfirst($ligne['activation']['texte']);

            return $this->finaliser($ligne);
        }
        if ($exportRhDesactive) {
            $ligne['etat']                = self::ETAT_DESACTIVEE;
            $ligne['activation']['texte'] = "L'export RH n'est pas activé dans la configuration de l'application (export-rh / actif).";
            $ligne['resume']              = 'Étape désactivée : ' . lcfirst($ligne['activation']['texte']);

            return $this->finaliser($ligne);
        }
        $ligne['activation']['texte'] = $regle
            ? "Prévue car l'option « $regle » est activée dans le statut « $statut »."
            : 'Prévue pour tous les statuts.';

        // 2. Avancement, globalement et par composante
        $objectif = $realisation = $partiel = 0.0;
        $atteignable = false;
        foreach ($calcul->structures as $strId => $str) {
            $objectif    += $str->objectif;
            $realisation += $str->realisation;
            $partiel     += $str->partiel;
            $atteignable = $atteignable || $str->atteignable;

            $ligne['structures'][] = [
                'libelle'     => $this->libelleStructure($strId),
                'atteignable' => $str->atteignable,
                'objectif'    => $str->objectif,
                'realisation' => $str->realisation,
                'pourc'       => $this->pourc($str->objectif, $str->realisation),
            ];
        }
        $ligne['avancement'] = [
            'objectif'    => $objectif,
            'realisation' => $realisation,
            'partiel'     => $partiel,
            'pourc'       => $this->pourc($objectif, $realisation),
        ];

        // 3. Conditions
        $bloquantes = [];
        foreach ($traces as $trace) {
            $condition            = $this->diagnostiquerCondition($trace, $calcul, $workflow);
            $ligne['conditions'][] = $condition;
            if (in_array($condition['resultat'], [self::CONDITION_NON_REMPLIE, self::CONDITION_PARTIELLE])) {
                $bloquantes[] = $condition['etapeRequise'];
            }
        }

        // 4. État global (même règle que la feuille de route en périmètre établissement)
        // Même règle que le badge « Fait » de la feuille de route
        if (100 === $ligne['avancement']['pourc']) {
            $ligne['etat']   = self::ETAT_FRANCHIE;
            $ligne['resume'] = 'Étape terminée.';
        } elseif ($realisation > 0) {
            $ligne['etat']   = self::ETAT_EN_COURS;
            $ligne['resume'] = 'Étape commencée (' . $ligne['avancement']['pourc'] . ' %).';
        } elseif ($atteignable) {
            $ligne['etat']   = self::ETAT_A_FAIRE;
            $ligne['resume'] = empty($bloquantes)
                ? 'Étape accessible : toutes ses conditions sont remplies.'
                : 'Étape accessible pour une partie des composantes seulement.';
        } else {
            $ligne['etat']   = self::ETAT_BLOQUEE;
            if ($bloquantes) {
                $ligne['resume'] = 'Étape bloquée par ' . (count($bloquantes) > 1 ? 'les conditions sur ' : 'la condition sur ')
                    . implode(', ', array_map(fn($e) => "« $e »", array_unique($bloquantes))) . '.';
            } elseif ($sansDonnees) {
                $ligne['resume'] = 'Étape bloquée car aucune donnée ne l\'alimente encore pour cet intervenant '
                    . '(rien à saisir ou à valider à ce stade, par exemple aucune heure en amont). '
                    . 'Aucune condition du workflow n\'est en cause.';
            } else {
                $ligne['resume'] = 'Étape bloquée.';
            }
        }

        // Une étape commencée reste accessible même si une condition n'est plus remplie (FeuilleDeRouteEtape::isAllowed())
        if ($bloquantes && in_array($ligne['etat'], [self::ETAT_FRANCHIE, self::ETAT_EN_COURS])) {
            $plusieurs = count(array_unique($bloquantes)) > 1;
            $ligne['alerte'] = 'Étape déjà réalisée alors que ' . ($plusieurs ? 'les conditions sur ' : 'la condition sur ')
                . implode(', ', array_map(fn($e) => "« $e »", array_unique($bloquantes)))
                . ($plusieurs ? ' ne sont pas remplies' : ' n\'est pas remplie') . ' aujourd\'hui : '
                . 'elle a pu être saisie avant un changement de paramétrage ou de données. Elle reste accessible car commencée.';
        }

        // 5. Visibilité pour l'utilisateur connecté
        $ligne['visibilite'] = $this->visibilite($etape, $enregistree);

        return $this->finaliser($ligne);
    }



    private function diagnostiquerCondition(array $trace, IntervenantEtape $calcul, array $workflow): array
    {
        /** @var WorkflowEtapeDependance $dependance */
        $dependance = $trace['dependance'];
        $precedante = $dependance->getEtapePrecedante();
        $niveau     = RapportParametrageService::NIVEAUX_AVANCEMENT[$dependance->getAvancement()] ?? 'Aucune règle';
        $perimetre  = Perimetre::COMPOSANTE === $dependance->getPerimetre()?->getCode()
            ? 'dans la même composante' : 'quelle que soit la composante';
        $type       = $dependance->getTypeIntervenant()
            ? 'pour les intervenants « ' . $dependance->getTypeIntervenant()->getLibelle() . ' » uniquement' : null;

        $condition = [
            'etapeRequise' => $precedante->getLibelleAutres(),
            'regle'        => implode(' · ', array_filter([$niveau, $perimetre, $type])),
            'precision'    => $precedante->getAvancements()[$dependance->getAvancement()] ?? null,
            'resultat'     => self::CONDITION_IGNOREE,
            'texte'        => '',
            'structures'   => [],
        ];

        switch ($trace['statut']) {
            case WorkflowProcess::DEPENDANCE_INACTIVE:
                $condition['texte'] = 'Ignorée : condition désactivée dans le paramétrage du workflow.';
                break;
            case WorkflowProcess::DEPENDANCE_CLOTURE_NON_REQUISE:
                $condition['texte'] = 'Ignorée : le statut de l\'intervenant ne demande pas la clôture du service réalisé.';
                break;
            case WorkflowProcess::DEPENDANCE_AUTRE_TYPE_INTERVENANT:
                $condition['texte'] = 'Ignorée : condition réservée aux intervenants de type « '
                    . $dependance->getTypeIntervenant()->getLibelle() . ' », cet intervenant est d\'un autre type.';
                break;
            case WorkflowProcess::DEPENDANCE_PRECEDANTE_ABSENTE:
                $condition['texte'] = 'Ignorée : l\'étape requise n\'est pas prévue pour cet intervenant.';
                break;
            default:
                $ok = $ko = [];
                foreach ($trace['structures'] as $strId => $resultat) {
                    $libelle = $this->libelleStructure($strId);
                    $precs   = $resultat['precedantes'];

                    $objectif    = array_sum(array_map(fn(IntervenantEtapeStructure $p) => $p->objectif, $precs));
                    $realisation = array_sum(array_map(fn(IntervenantEtapeStructure $p) => $p->realisation, $precs));

                    $detail = empty($precs)
                        ? 'aucune donnée pour l\'étape requise ' . ($perimetre === 'dans la même composante' ? 'dans cette composante' : '')
                        : 'étape requise réalisée à ' . $this->pourc($objectif, $realisation) . ' %';

                    $condition['structures'][] = ['libelle' => $libelle, 'ok' => $resultat['ok'], 'detail' => trim($detail)];
                    $resultat['ok'] ? $ok[] = $libelle : $ko[] = $strId ? $libelle . ' (' . trim($detail) . ')' : trim($detail);
                }

                if (empty($ko)) {
                    $condition['resultat'] = self::CONDITION_REMPLIE;
                    $condition['texte']    = 'Remplie.';
                } elseif (empty($ok)) {
                    $condition['resultat'] = self::CONDITION_NON_REMPLIE;
                    $condition['texte']    = 'Non remplie : ' . implode(' ; ', $ko) . '.';
                } else {
                    $condition['resultat'] = self::CONDITION_PARTIELLE;
                    $condition['texte']    = 'Remplie pour ' . implode(', ', $ok) . ', mais pas pour : ' . implode(' ; ', $ko) . '.';
                }
        }

        return $condition;
    }



    /**
     * Visibilité de l'étape pour l'utilisateur connecté, comme dans la feuille de route
     * (FeuilleDeRouteEtape::isVisible()).
     */
    private function visibilite(WorkflowEtape $etape, ?FeuilleDeRouteEtape $enregistree): array
    {
        $context = $this->getServiceContext();
        $route   = $etape->getRoute();
        if ($context->getIntervenant() && !$context->getAffectation()) {
            $route = $etape->getRouteIntervenant() ?: $route;
        }

        if (!$this->router->hasRoute($route)) {
            return ['visible' => false, 'texte' => "La page associée à l'étape (route « $route ») n'existe pas."];
        }

        $visible = $enregistree ? $enregistree->isVisible() : true;

        return [
            'visible' => $visible,
            'texte'   => $visible ? null
                : "Le rôle n'a pas accès à la page associée à l'étape (privilège ou règle d'accès sur la route « $route »).",
        ];
    }



    /**
     * Compare le recalcul en direct à la feuille de route enregistrée (tableau de bord workflow).
     *
     * @param IntervenantEtape[] $workflow
     * @param WorkflowEtape[]    $etapes
     * @return string[] libellés des étapes qui diffèrent
     */
    private function ecartsAvecFeuilleEnregistree(Intervenant $intervenant, array $workflow, array $etapes): array
    {
        $enregistre = [];
        $sql        = 'SELECT etape_code, structure_id, atteignable, objectif, realisation FROM tbl_workflow WHERE intervenant_id = :intervenant';
        foreach ($this->bdd->select($sql, ['intervenant' => $intervenant->getId()]) as $d) {
            mpg_lower($d);
            $enregistre[$d['etape_code']][(int)$d['structure_id']] = [
                (bool)$d['atteignable'], round((float)$d['objectif'], 2), round((float)$d['realisation'], 2),
            ];
        }

        $calcule = [];
        foreach ($workflow as $code => $ie) {
            foreach ($ie->structures as $strId => $str) {
                $calcule[$code][$strId] = [$str->atteignable, round($str->objectif, 2), round($str->realisation, 2)];
            }
        }

        $ecarts = [];
        foreach (array_unique(array_merge(array_keys($enregistre), array_keys($calcule))) as $code) {
            $a = $enregistre[$code] ?? [];
            $b = $calcule[$code] ?? [];
            ksort($a);
            ksort($b);
            if ($a != $b) {
                $ecarts[] = isset($etapes[$code]) ? $etapes[$code]->getLibelleAutres() : $code;
            }
        }

        return $ecarts;
    }



    /**
     * @return array<string,string> code d'étape => libellé de l'option du statut qui l'active
     */
    private function reglesParEtape(array $regles): array
    {
        $parEtape = [];
        foreach ($regles as $regle) {
            foreach ($regle['etapes'] as $code) {
                $parEtape[$code] = $regle['libelle'];
            }
        }

        return $parEtape;
    }



    /**
     * @param IntervenantEtape[] $workflow
     */
    private function chargerStructures(array $workflow): void
    {
        $ids = [];
        foreach ($workflow as $ie) {
            foreach (array_keys($ie->structures) as $id) {
                if ($id) {
                    $ids[$id] = $id;
                }
            }
        }
        $this->structures = [];
        if ($ids) {
            foreach ($this->getEntityManager()->getRepository(Structure::class)->findBy(['id' => array_values($ids)]) as $structure) {
                /** @var Structure $structure */
                $this->structures[$structure->getId()] = $structure->getLibelleCourt();
            }
        }
    }



    private function libelleStructure(int $id): string
    {
        return $id ? ($this->structures[$id] ?? "Structure n° $id") : 'Établissement';
    }



    private function pourc(float $objectif, float $realisation): int
    {
        return (int)round($realisation / max($objectif, $realisation, 1) * 100);
    }



    private function finaliser(array $ligne): array
    {
        $ligne['etatLibelle'] = self::ETATS_LIBELLES[$ligne['etat']];

        return $ligne;
    }



    /**
     * Version texte du diagnostic, à coller dans une demande d'assistance.
     */
    private function texte(array $d): string
    {
        $i      = $d['intervenant'];
        $lignes = [
            'DIAGNOSTIC DE LA FEUILLE DE ROUTE — ' . $d['date'],
            "Intervenant : {$i['nom']} — statut « {$i['statut']} » ({$i['typeIntervenant']}) — année {$i['annee']}",
            'Consulté par : ' . implode(' — ', array_filter([$d['utilisateur']['nom'], $d['utilisateur']['role'], $d['utilisateur']['structure']])),
            $d['aJour']
                ? 'Feuille de route enregistrée : à jour.'
                : 'Feuille de route enregistrée : PAS À JOUR (écarts sur : ' . implode(', ', $d['ecarts']) . ') → cliquer sur « Actualiser ».',
            '',
        ];
        foreach ($d['etapes'] as $e) {
            $lignes[] = sprintf('%02d. [%s] %s%s', $e['ordre'], mb_strtoupper($e['etatLibelle']), $e['libelle'], $e['courante'] ? '  ← étape en cours' : '');
            $lignes[] = '    ' . $e['resume'];
            if ($e['alerte']) {
                $lignes[] = '    /!\\ ' . $e['alerte'];
            }
            if ($e['activation']['prevue']) {
                $lignes[] = '    ' . $e['activation']['texte'];
            }
            if ($e['visibilite'] && !$e['visibilite']['visible']) {
                $lignes[] = '    Masquée dans la feuille de route de l\'auteur du diagnostic : ' . lcfirst($e['visibilite']['texte']);
            }
            foreach ($e['conditions'] as $c) {
                $lignes[] = "    - Requiert « {$c['etapeRequise']} » ({$c['regle']}) : {$c['texte']}";
            }
        }

        return implode("\n", $lignes);
    }
}
