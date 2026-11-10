<?php

namespace Workflow\Service;

use Application\Entity\Db\Perimetre;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dompdf\Dompdf;
use Laminas\View\Renderer\PhpRenderer;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;

/**
 * Rapport PDF du paramétrage du workflow, destiné à l'assistance à distance :
 * paramétrage complet de l'année courante, écarts au paramétrage standard
 * (data/workflow_etapes.php) et incohérences détectées.
 */
class RapportParametrageService
{
    use ContextServiceAwareTrait;
    use WorkflowServiceAwareTrait;

    const NIVEAU_ERREUR    = 'Erreur';
    const NIVEAU_ATTENTION = 'Attention';

    const NIVEAUX_AVANCEMENT = [
        WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Commencée',
        WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'Terminée en partie',
        WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Entièrement terminée',
    ];



    public function __construct(
        private readonly PhpRenderer $renderer,
        private readonly string      $version,
    )
    {
    }



    public function genererPdf(): string
    {
        $html = $this->renderer->render('workflow/administration/rapport-pdf', ['rapport' => $this->analyser()]);

        $dompdf = new Dompdf(['defaultFont' => 'DejaVu Sans']);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }



    public function getNomFichier(string $prefixe = 'workflow-parametrage', string $extension = 'pdf'): string
    {
        $context       = $this->getServiceContext();
        $etablissement = (string)$context->getEtablissement()?->getLibelle();
        $etablissement = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', str_replace(['\'', '’'], ' ', $etablissement)) ?: '';
        // Selon la libc, iconv laisse une marque devant la lettre translittérée (ex. : Universit'e)
        $etablissement = (string)preg_replace('/[^a-z0-9 -]/i', '', $etablissement);
        $etablissement = trim((string)preg_replace('/[ -]+/', '-', $etablissement), '-');

        return implode('_', array_filter([
            $prefixe,
            $etablissement,
            $context->getAnnee()->getId(),
            date('Ymd-His'),
        ])) . '.' . $extension;
    }



    /**
     * Rassemble les données du rapport pour l'année courante.
     */
    public function analyser(): array
    {
        $context = $this->getServiceContext();

        /** @var WorkflowEtape[] $etapes */
        //On récupére le workflow tel qu'il est paramétré actuellement dans l'application
        $etapes   = array_values($this->getServiceWorkflow()->getEtapes());
        //On récupére le workflow standart à la livraison de OSE
        $standard = require getcwd() . '/data/workflow_etapes.php';

        $numeros = [];
        foreach ($etapes as $index => $etape) {
            $numeros[$etape->getCode()] = $index + 1;
        }
        $numerosStandard = array_flip(array_keys($standard));

        $debloque = [];
        foreach ($etapes as $etape) {
            foreach ($etape->getDependances() as $dependance) {
                if ($dependance->isActive()) {
                    $debloque[$dependance->getEtapePrecedante()->getCode()][] = $etape->getLibelleAutres();
                }
            }
        }

        $lignes = [];
        foreach ($etapes as $etape) {
            $code        = $etape->getCode();
            $dependances = [];
            foreach ($etape->getDependances() as $dependance) {
                $dependances[] = $this->ligneDependance($dependance, $numeros);
            }

            $lignes[] = [
                'numero'             => $numeros[$code],
                'numeroStandard'     => isset($numerosStandard[$code]) ? $numerosStandard[$code] + 1 : null,
                'code'               => $code,
                'libelleAutres'      => $etape->getLibelleAutres(),
                'libelleIntervenant' => $etape->getLibelleIntervenant(),
                'descNonFranchie'    => $etape->getDescNonFranchie(),
                'descSansObjectif'   => $etape->getDescSansObjectif(),
                'perimetre'          => $etape->getPerimetre()->getLibelle(),
                'dependances'        => $dependances,
                'debloque'           => array_values(array_unique($debloque[$code] ?? [])),
            ];
        }

        $ecarts    = $this->calculerEcarts($etapes, $lignes, $standard);
        $anomalies = $this->detecterAnomalies($etapes, $numeros, $standard);

        $nbDependances = array_sum(array_map(fn(array $l) => count($l['dependances']), $lignes));
        $nbActives     = array_sum(array_map(
            fn(array $l) => count(array_filter($l['dependances'], fn(array $d) => $d['active'])),
            $lignes
        ));

        return [
            'infos'     => [
                'etablissement' => (string)$context->getEtablissement()?->getLibelle(),
                'annee'         => (string)$context->getAnnee()->getLibelle(),
                'date'          => date('d/m/Y à H:i'),
                'utilisateur'   => (string)$context->getUtilisateur(),
                'version'       => $this->version,
            ],
            'stats'     => [
                'etapes'      => count($lignes),
                'actives'     => $nbActives,
                'desactivees' => $nbDependances - $nbActives,
                'ecarts'      => count($ecarts),
                'anomalies'   => count($anomalies),
            ],
            'etapes'    => $lignes,
            'ecarts'    => $ecarts,
            'anomalies' => $anomalies,
        ];
    }



    private function ligneDependance(WorkflowEtapeDependance $dependance, array $numeros): array
    {
        $precedante  = $dependance->getEtapePrecedante();
        $avancement  = $dependance->getAvancement();
        $descriptions = $precedante->getAvancements();

        return [
            'precedante'          => $precedante->getLibelleAutres(),
            'precedanteCode'      => $precedante->getCode(),
            'precedanteNumero'    => $numeros[$precedante->getCode()] ?? null,
            'avancement'          => $avancement,
            'niveau'              => self::NIVEAUX_AVANCEMENT[$avancement] ?? 'Aucune règle fixée',
            'regle'               => $descriptions[$avancement] ?? '',
            'perimetre'           => (string)$dependance->getPerimetre()?->getLibelle(),
            'perimetreCode'       => $dependance->getPerimetre()?->getCode(),
            'typeIntervenant'     => $dependance->getTypeIntervenant()?->getLibelle(),
            'typeIntervenantCode' => $dependance->getTypeIntervenant()?->getCode(),
            'active'              => $dependance->isActive(),
        ];
    }



    /**
     * Compare le paramétrage de l'établissement au paramétrage standard de la version installée.
     */
    private function calculerEcarts(array $etapes, array $lignes, array $standard): array
    {
        $parCode = [];
        foreach ($etapes as $etape) {
            $parCode[$etape->getCode()] = $etape;
        }

        $positionsStandard = [];
        foreach ($lignes as $ligne) {
            if (null !== $ligne['numeroStandard']) {
                $positionsStandard[$ligne['code']] = $ligne['numeroStandard'];
            }
        }
        $deplacees = array_flip($this->etapesDeplacees($positionsStandard));

        $ecarts = [];
        foreach ($lignes as $ligne) {
            $code  = $ligne['code'];
            $ajout = function (string $element, ?string $standard, ?string $actuel) use (&$ecarts, $ligne) {
                $ecarts[] = [
                    'numero'   => $ligne['numero'],
                    'etape'    => $ligne['libelleAutres'],
                    'element'  => $element,
                    'standard' => $standard,
                    'actuel'   => $actuel,
                ];
            };

            if (!isset($standard[$code])) {
                $ajout('Étape', 'Absente du standard', $code);
                continue;
            }
            $std = $standard[$code];

            if (isset($deplacees[$code])) {
                $ajout('Position', 'N° ' . $ligne['numeroStandard'], 'N° ' . $ligne['numero']);
            }
            foreach ([
                         'libelleIntervenant' => ['libelle_intervenant', 'Libellé intervenant'],
                         'libelleAutres'      => ['libelle_autres', 'Libellé gestionnaire'],
                         'descNonFranchie'    => ['desc_non_franchie', 'Message si non franchie'],
                         'descSansObjectif'   => ['desc_sans_objectif', 'Message si sans objectif'],
                     ] as $champ => [$cle, $libelle]) {
                if (($ligne[$champ] ?? null) !== ($std[$cle] ?? null)) {
                    $ajout($libelle, $std[$cle] ?? '(vide)', $ligne[$champ] ?? '(vide)');
                }
            }
            if ($parCode[$code]->getPerimetre()->getCode() !== $std['perimetre']) {
                $ajout('Périmètre de l\'étape', $std['perimetre'], $parCode[$code]->getPerimetre()->getCode());
            }

            $stdDeps = $std['dependances'] ?? [];
            $vues    = [];
            foreach ($ligne['dependances'] as $d) {
                $prec = $d['precedanteCode'];
                if (!isset($stdDeps[$prec]) || isset($vues[$prec])) {
                    $ajout('Condition ajoutée', '(aucune)', $this->resumerDependance($d));
                    continue;
                }
                $vues[$prec] = true;
                $stdDep      = $stdDeps[$prec];
                $objet       = 'Condition « ' . $d['precedante'] . ' » : ';

                if (!$d['active']) {
                    $ajout($objet . 'état', 'Active', 'Désactivée');
                }
                $stdAvancement = $stdDep['avancement'] ?? WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT;
                if ($d['avancement'] !== $stdAvancement) {
                    $ajout($objet . 'avancement', self::NIVEAUX_AVANCEMENT[$stdAvancement], $d['niveau']);
                }
                $stdPerimetre = $stdDep['perimetre'] ?? Perimetre::ETABLISSEMENT;
                if ($d['perimetreCode'] !== $stdPerimetre) {
                    $ajout($objet . 'périmètre', $stdPerimetre, (string)$d['perimetreCode']);
                }
                $stdType = $stdDep['type_intervenant'] ?? null;
                if ($d['typeIntervenantCode'] !== $stdType) {
                    $ajout($objet . 'intervenants', $stdType ?? 'Tous', $d['typeIntervenantCode'] ?? 'Tous');
                }
            }

            foreach ($stdDeps as $prec => $stdDep) {
                if (!isset($vues[$prec])) {
                    $libelle = isset($parCode[$prec]) ? $parCode[$prec]->getLibelleAutres() : $prec;
                    $ajout('Condition supprimée', $libelle, '(aucune)');
                }
            }
        }

        return $ecarts;
    }



    /**
     * Retourne les étapes réellement déplacées : celles hors de la plus longue suite déjà dans l'ordre
     * standard. Déplacer une étape décale toutes les autres, qui ne sont donc pas signalées.
     *
     * @param array<string,int> $positions code => position standard, dans l'ordre actuel
     * @return string[]
     */
    private function etapesDeplacees(array $positions): array
    {
        if (empty($positions)) {
            return [];
        }

        $codes     = array_keys($positions);
        $valeurs   = array_values($positions);
        $nb        = count($valeurs);
        $longueurs = array_fill(0, $nb, 1);
        $parents   = array_fill(0, $nb, -1);
        $meilleur  = 0;

        for ($i = 0; $i < $nb; $i++) {
            for ($j = 0; $j < $i; $j++) {
                if ($valeurs[$j] < $valeurs[$i] && $longueurs[$j] + 1 > $longueurs[$i]) {
                    $longueurs[$i] = $longueurs[$j] + 1;
                    $parents[$i]   = $j;
                }
            }
            if ($longueurs[$i] > $longueurs[$meilleur]) {
                $meilleur = $i;
            }
        }

        $enPlace = [];
        for ($i = $meilleur; $i !== -1; $i = $parents[$i]) {
            $enPlace[] = $codes[$i];
        }

        return array_values(array_diff($codes, $enPlace));
    }



    private function resumerDependance(array $d): string
    {
        return sprintf(
            '« %s » : %s, %s, %s%s',
            $d['precedante'],
            mb_strtolower($d['niveau']),
            $d['perimetreCode'] ?? 'sans périmètre',
            $d['typeIntervenant'] ?? 'tous intervenants',
            $d['active'] ? '' : ' (désactivée)'
        );
    }



    /**
     * @param WorkflowEtape[] $etapes
     */
    private function detecterAnomalies(array $etapes, array $numeros, array $standard): array
    {
        $anomalies = [];

        foreach ($etapes as $etape) {
            $code   = $etape->getCode();
            $numero = $numeros[$code];
            $ajout  = function (string $niveau, string $constat) use (&$anomalies, $etape, $numero) {
                $anomalies[] = [
                    'niveau'  => $niveau,
                    'numero'  => $numero,
                    'etape'   => $etape->getLibelleAutres(),
                    'constat' => $constat,
                ];
            };

            foreach ($etape->getContraintes() as $contrainte) {
                if ($numeros[$contrainte->getCode()] > $numero) {
                    $ajout(self::NIVEAU_ERREUR, 'Par conception, cette étape doit être placée après « '
                        . $contrainte->getLibelleAutres() . ' » (N° ' . $numeros[$contrainte->getCode()] . ').');
                }
            }

            $precedantes = [];
            foreach ($etape->getDependances() as $dependance) {
                $precedante = $dependance->getEtapePrecedante();
                $libelle    = '« ' . $precedante->getLibelleAutres() . ' »';
                $position   = $numeros[$precedante->getCode()] ?? null;

                if (null === $position) {
                    $ajout(self::NIVEAU_ERREUR, 'Condition portant sur une étape absente du workflow : ' . $precedante->getCode() . '.');
                } elseif ($position >= $numero) {
                    $ajout(self::NIVEAU_ERREUR, "L'étape requise $libelle est placée après l'étape qui en dépend (N° $position).");
                }

                // Un niveau repris tel quel du standard n'est pas imputable au paramétrage de l'établissement
                $avancementStandard = $standard[$code]['dependances'][$precedante->getCode()]['avancement'] ?? null;
                if ($dependance->getAvancement() !== $avancementStandard
                    && !isset($precedante->getAvancements()[$dependance->getAvancement()])) {
                    $niveau = self::NIVEAUX_AVANCEMENT[$dependance->getAvancement()] ?? (string)$dependance->getAvancement();
                    $ajout(self::NIVEAU_ATTENTION, "Le niveau d'avancement « $niveau » n'est pas prévu pour l'étape requise $libelle.");
                }

                if (!$dependance->getPerimetre()) {
                    $ajout(self::NIVEAU_ATTENTION, "La condition sur $libelle n'a pas de périmètre.");
                }

                if (isset($precedantes[$precedante->getCode()])) {
                    $ajout(self::NIVEAU_ATTENTION, "Plusieurs conditions portent sur l'étape requise $libelle.");
                }
                $precedantes[$precedante->getCode()] = true;
            }
        }

        usort($anomalies, fn(array $a, array $b) => [$a['niveau'] !== self::NIVEAU_ERREUR, $a['numero']]
            <=> [$b['niveau'] !== self::NIVEAU_ERREUR, $b['numero']]);

        return $anomalies;
    }
}
