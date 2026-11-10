<?php

namespace Workflow\Service;

use Application\Entity\Db\Perimetre;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\TypeIntervenant;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;

/**
 * Export / import du paramétrage du workflow (ordre, libellés et conditions des étapes) au format JSON.
 *
 * Permet de récupérer la configuration d'un établissement et de la rejouer sur une autre instance
 * pour reproduire un problème dans les mêmes conditions.
 * Les étapes, périmètres et types d'intervenants sont désignés par leurs codes : le fichier est donc
 * indépendant des identifiants de la base d'origine.
 */
class TransfertParametrageService
{
    use ContextServiceAwareTrait;
    use EntityManagerAwareTrait;
    use WorkflowServiceAwareTrait;

    const FORMAT         = 'ose-workflow-parametrage';
    const FORMAT_VERSION = 1;



    public function __construct(
        private readonly string $version,
    )
    {
    }



    public function exporter(): array
    {
        $context = $this->getServiceContext();

        $etapes = [];
        foreach ($this->getServiceWorkflow()->getEtapes() as $etape) {
            $dependances = [];
            foreach ($etape->getDependances() as $dependance) {
                $dependances[] = [
                    'etapePrecedante' => $dependance->getEtapePrecedante()->getCode(),
                    'active'          => $dependance->isActive(),
                    'avancement'      => $dependance->getAvancement(),
                    'perimetre'       => $dependance->getPerimetre()?->getCode(),
                    'typeIntervenant' => $dependance->getTypeIntervenant()?->getCode(),
                ];
            }

            $etapes[] = [
                'code'               => $etape->getCode(),
                'ordre'              => $etape->getOrdre(),
                'perimetre'          => $etape->getPerimetre()->getCode(),
                'libelleIntervenant' => $etape->getLibelleIntervenant(),
                'libelleAutres'      => $etape->getLibelleAutres(),
                'descNonFranchie'    => $etape->getDescNonFranchie(),
                'descSansObjectif'   => $etape->getDescSansObjectif(),
                'dependances'        => $dependances,
            ];
        }

        return [
            'format'        => self::FORMAT,
            'formatVersion' => self::FORMAT_VERSION,
            'origine'       => [
                'etablissement' => (string)$context->getEtablissement()?->getLibelle(),
                'annee'         => $context->getAnnee()->getId(),
                'version'       => $this->version,
                'date'          => date('c'),
                'utilisateur'   => (string)$context->getUtilisateur(),
            ],
            'etapes'        => $etapes,
        ];
    }



    public function exporterJson(): string
    {
        return json_encode($this->exporter(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }



    /**
     * Applique le paramétrage fourni à l'année sélectionnée et, si $anneesSuivantes, à toutes les années suivantes.
     * Sinon, les années suivantes sont laissées telles qu'elles étaient.
     * Les années passées ne sont jamais modifiées.
     *
     * L'import est différentiel : seuls les éléments qui diffèrent sont modifiés, et ils le sont via
     * l'ORM, comme une saisie à l'écran (historique compris). Tout est fait dans une seule transaction.
     *
     * @return array{etapes: int, ajoutees: int, modifiees: int, supprimees: int, anneesAlignees: int, anneeFin: ?int, avertissements: string[]}
     */
    public function importerJson(string $json, bool $anneesSuivantes = true): array
    {
        try {
            $data = json_decode($json, true, 32, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new ImportInvalideException(['Le fichier n\'est pas un fichier JSON valide (' . $e->getMessage() . ').']);
        }

        return $this->importer(is_array($data) ? $data : [], $anneesSuivantes);
    }



    /**
     * Contrôle la structure et le contenu du fichier. Retourne la liste des erreurs (vide si le fichier est conforme).
     *
     * @return string[]
     */
    public function valider(array $data): array
    {
        if (($data['format'] ?? null) !== self::FORMAT) {
            return ['Le fichier n\'est pas un export de configuration du workflow OSE.'];
        }
        if (!is_int($data['formatVersion'] ?? null)) {
            return ['La version du format est absente ou invalide.'];
        }
        if ($data['formatVersion'] > self::FORMAT_VERSION) {
            return ['Ce fichier a été produit par une version plus récente d\'OSE (format '
                . $data['formatVersion'] . '), il ne peut pas être importé ici.'];
        }
        if (!is_array($data['etapes'] ?? null) || !array_is_list($data['etapes']) || empty($data['etapes'])) {
            return ['La liste des étapes est absente ou vide.'];
        }

        $erreurs = [];
        $codes   = [];
        foreach ($data['etapes'] as $i => $etape) {
            $lieu = 'Étape n° ' . ($i + 1);
            if (!is_array($etape)) {
                $erreurs[] = "$lieu : description invalide.";
                continue;
            }
            if (!is_string($etape['code'] ?? null) || '' === $etape['code']) {
                $erreurs[] = "$lieu : code absent ou invalide.";
                continue;
            }
            $lieu = 'Étape « ' . $etape['code'] . ' »';
            if (isset($codes[$etape['code']])) {
                $erreurs[] = "$lieu : présente plusieurs fois.";
            }
            $codes[$etape['code']] = true;

            if (!is_int($etape['ordre'] ?? null)) {
                $erreurs[] = "$lieu : ordre absent ou invalide.";
            }
            foreach ([
                         'libelleIntervenant' => 'libellé intervenant',
                         'libelleAutres'      => 'libellé gestionnaire',
                         'descNonFranchie'    => 'message si non franchie',
                         'descSansObjectif'   => 'message si sans objectif',
                     ] as $champ => $libelle) {
                $valeur = $etape[$champ] ?? null;
                if ('descSansObjectif' === $champ && null === $valeur) {
                    continue;
                }
                if (!is_string($valeur) || '' === trim($valeur)) {
                    $erreurs[] = "$lieu : $libelle absent ou invalide.";
                } elseif (mb_strlen($valeur) > 256) {
                    $erreurs[] = "$lieu : $libelle trop long (256 caractères maximum).";
                }
            }

            if (!is_array($etape['dependances'] ?? null) || !array_is_list($etape['dependances'])) {
                $erreurs[] = "$lieu : liste des dépendances absente ou invalide.";
                continue;
            }
            $cles = [];
            foreach ($etape['dependances'] as $j => $dep) {
                $lieuDep = "$lieu, dépendance n° " . ($j + 1);
                if (!is_array($dep)) {
                    $erreurs[] = "$lieuDep : description invalide.";
                    continue;
                }
                if (!is_string($dep['etapePrecedante'] ?? null) || '' === $dep['etapePrecedante']) {
                    $erreurs[] = "$lieuDep : étape requise absente ou invalide.";
                } elseif ($dep['etapePrecedante'] === $etape['code']) {
                    $erreurs[] = "$lieuDep : une étape ne peut pas dépendre d'elle-même.";
                }
                if (!is_bool($dep['active'] ?? null)) {
                    $erreurs[] = "$lieuDep : état actif/inactif absent ou invalide.";
                }
                if (!is_int($dep['avancement'] ?? null) || !isset(self::avancementsValides()[$dep['avancement']])) {
                    $erreurs[] = "$lieuDep : niveau d'avancement absent ou invalide.";
                }
                foreach (['perimetre' => 'périmètre', 'typeIntervenant' => 'type d\'intervenant'] as $champ => $libelle) {
                    if (null !== ($dep[$champ] ?? null) && !is_string($dep[$champ])) {
                        $erreurs[] = "$lieuDep : $libelle invalide.";
                    }
                }
                $cle = $this->cleDependance((string)($dep['etapePrecedante'] ?? ''), is_string($dep['typeIntervenant'] ?? null) ? $dep['typeIntervenant'] : null);
                if (isset($cles[$cle])) {
                    $erreurs[] = "$lieuDep : doublon (même étape requise et même type d'intervenant).";
                }
                $cles[$cle] = true;
            }
        }

        return $erreurs;
    }



    private static function avancementsValides(): array
    {
        return [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => true,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => true,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => true,
        ];
    }



    public function importer(array $data, bool $anneesSuivantes = true): array
    {
        $erreurs = $this->valider($data);
        if ($erreurs) {
            throw new ImportInvalideException($erreurs);
        }

        $resultat = ['etapes' => 0, 'ajoutees' => 0, 'modifiees' => 0, 'supprimees' => 0, 'anneesAlignees' => 0,
                     'anneeFin' => null, 'avertissements' => []];

        $origine = $data['origine'] ?? [];
        if (($origine['version'] ?? null) && $origine['version'] !== $this->version) {
            $resultat['avertissements'][] = 'Fichier exporté depuis OSE ' . $origine['version']
                . ', cette instance est en ' . $this->version . '.';
        }
        $anneeCourante = $this->getServiceContext()->getAnnee();
        if (($origine['annee'] ?? null) && (int)$origine['annee'] !== $anneeCourante->getId()) {
            $resultat['avertissements'][] = 'Paramétrage de l\'année ' . $origine['annee']
                . ' importé dans l\'année ' . $anneeCourante->getLibelle() . '.';
        }

        $this->getServiceWorkflow()->clearEtapesCache();
        $etapes = $this->getServiceWorkflow()->getEtapes();

        // Contrôles préalables : on ne modifie rien si le fichier fait référence à des éléments inconnus
        $fichier = [];
        $erreurs = [];
        foreach ($data['etapes'] as $etapeData) {
            $code = $etapeData['code'] ?? null;
            if (!isset($etapes[$code])) {
                $resultat['avertissements'][] = 'Étape « ' . $code . ' » inconnue de cette instance : ignorée.';
                continue;
            }
            $dependances = [];
            foreach ($etapeData['dependances'] ?? [] as $depData) {
                $precedante = $depData['etapePrecedante'] ?? null;
                if (!isset($etapes[$precedante])) {
                    $resultat['avertissements'][] = 'Condition de « ' . $code . ' » sur l\'étape inconnue « ' . $precedante . ' » : ignorée.';
                    continue;
                }
                try {
                    $depData['perimetreEntity']       = $this->getPerimetre($depData['perimetre'] ?? null);
                    $depData['typeIntervenantEntity'] = $this->getTypeIntervenant($depData['typeIntervenant'] ?? null);
                } catch (\RuntimeException $e) {
                    $erreurs[] = 'Étape « ' . $code . ' » : ' . $e->getMessage();
                    continue;
                }
                $dependances[] = $depData;
            }
            $etapeData['dependances'] = $dependances;
            $fichier[$code]           = $etapeData;
        }
        if (empty($fichier)) {
            $erreurs[] = 'Aucune étape du fichier ne correspond à cette instance.';
        }
        if ($erreurs) {
            throw new ImportInvalideException(array_values(array_unique($erreurs)));
        }
        foreach (array_diff_key($etapes, $fichier) as $code => $etape) {
            $resultat['avertissements'][] = 'Étape « ' . $etape->getLibelleAutres() . ' » absente du fichier : conservée en fin de workflow.';
        }

        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {
            $annee   = $anneeCourante->getId();
            $suivantes = $this->anneesSuivantes($annee);

            if ($anneesSuivantes) {
                // Le paramétrage est appliqué à l'année sélectionnée et à toutes les suivantes
                $resultat = array_merge($resultat, $this->appliquerAnnee($annee, $fichier));
                foreach ($suivantes as $suivante) {
                    $compteurs = $this->appliquerAnnee($suivante, $fichier);
                    if (array_sum($compteurs) > 0) {
                        $resultat['anneesAlignees']++;
                    }
                }
                $resultat['anneeFin'] = max([$annee, ...$suivantes]);

                // Les années suivantes redeviennent la continuité de l'année sélectionnée : une modification
                // ultérieure de celle-ci à l'écran s'y reportera de nouveau automatiquement
                $connexion = $em->getConnection();
                foreach (['workflow_etape', 'workflow_etape_dependance'] as $table) {
                    $connexion->executeStatement(
                        "UPDATE $table SET histo_modificateur_id = NULL WHERE annee_id > :annee AND histo_destruction IS NULL",
                        ['annee' => $annee]
                    );
                }
            } else {
                // Année sélectionnée seule : l'enregistrement déclenche le report automatique d'OSE vers les
                // années suivantes. On photographie donc ces années avant, puis on les remet dans leur état.
                $instantanes = [];
                foreach ($suivantes as $suivante) {
                    $instantanes[$suivante] = $this->instantane($suivante);
                }
                $resultat = array_merge($resultat, $this->appliquerAnnee($annee, $fichier));
                foreach ($instantanes as $suivante => $instantane) {
                    $this->appliquerAnnee($suivante, $instantane);
                }
                $resultat['anneeFin'] = $annee;
            }

            $em->commit();
        } catch (\Throwable $e) {
            $em->rollback();
            // Les entités chargées gardent les valeurs annulées : on les oublie pour relire la base
            $em->clear();
            throw $e;
        } finally {
            $this->getServiceWorkflow()->clearEtapesCache();
        }

        return $resultat;
    }



    /**
     * Paramétrage d'une année, sous la forme attendue par appliquerAnnee().
     */
    private function instantane(int $annee): array
    {
        $this->getServiceWorkflow()->clearEtapesCache();

        $instantane = [];
        foreach ($this->getServiceWorkflow()->getEtapes($annee) as $code => $etape) {
            $dependances = [];
            foreach ($etape->getDependances() as $dependance) {
                $dependances[] = [
                    'etapePrecedante'       => $dependance->getEtapePrecedante()->getCode(),
                    'active'                => $dependance->isActive(),
                    'avancement'            => $dependance->getAvancement(),
                    'perimetreEntity'       => $dependance->getPerimetre(),
                    'typeIntervenant'       => $dependance->getTypeIntervenant()?->getCode(),
                    'typeIntervenantEntity' => $dependance->getTypeIntervenant(),
                ];
            }
            $instantane[$code] = [
                'ordre'              => $etape->getOrdre(),
                'libelleIntervenant' => $etape->getLibelleIntervenant(),
                'libelleAutres'      => $etape->getLibelleAutres(),
                'descNonFranchie'    => $etape->getDescNonFranchie(),
                'descSansObjectif'   => $etape->getDescSansObjectif(),
                'dependances'        => $dependances,
            ];
        }

        return $instantane;
    }



    /**
     * @return int[]
     */
    private function anneesSuivantes(int $annee): array
    {
        $dql = 'SELECT DISTINCT IDENTITY(we.annee) annee FROM ' . WorkflowEtape::class . ' we
                WHERE we.annee > :annee AND we.histoDestruction IS NULL ORDER BY annee';

        return array_map('intval', array_column(
            $this->getEntityManager()->createQuery($dql)->setParameter('annee', $annee)->getScalarResult(),
            'annee'
        ));
    }



    /**
     * Aligne le paramétrage d'une année sur le fichier. Seuls les éléments qui diffèrent sont enregistrés.
     *
     * @return array{etapes: int, ajoutees: int, modifiees: int, supprimees: int}
     */
    private function appliquerAnnee(int $annee, array $fichier): array
    {
        $em        = $this->getEntityManager();
        $compteurs = ['etapes' => 0, 'ajoutees' => 0, 'modifiees' => 0, 'supprimees' => 0];

        $this->getServiceWorkflow()->clearEtapesCache();
        $etapes  = $this->getServiceWorkflow()->getEtapes($annee);
        $fichier = array_intersect_key($fichier, $etapes);

        // Ordre : celui du fichier, puis les étapes absentes du fichier dans leur ordre actuel
        uasort($fichier, fn(array $a, array $b) => $a['ordre'] <=> $b['ordre']);
        $ordre = 1;
        foreach (array_merge(array_keys($fichier), array_keys(array_diff_key($etapes, $fichier))) as $code) {
            $etape   = $etapes[$code];
            $valeurs = $fichier[$code] ?? [];
            $avant   = [$etape->getOrdre(), $etape->getLibelleIntervenant(), $etape->getLibelleAutres(),
                        $etape->getDescNonFranchie(), $etape->getDescSansObjectif()];

            $etape->setOrdre($ordre++);
            if ($valeurs) {
                $etape->setLibelleIntervenant($valeurs['libelleIntervenant']);
                $etape->setLibelleAutres($valeurs['libelleAutres']);
                $etape->setDescNonFranchie($valeurs['descNonFranchie']);
                $etape->setDescSansObjectif($valeurs['descSansObjectif'] ?? null);
            }

            $apres = [$etape->getOrdre(), $etape->getLibelleIntervenant(), $etape->getLibelleAutres(),
                      $etape->getDescNonFranchie(), $etape->getDescSansObjectif()];
            if ($avant !== $apres) {
                $em->persist($etape);
                $em->flush($etape);
                $compteurs['etapes']++;
            }
        }

        // Dépendances existantes relues en base : les reports automatiques des années précédentes
        // ont pu en créer ou en supprimer sans que les collections en mémoire le sachent
        $dql = 'SELECT d, ep, es FROM ' . WorkflowEtapeDependance::class . ' d
                JOIN d.etapeSuivante es JOIN d.etapePrecedante ep
                WHERE es.annee = :annee AND d.histoDestruction IS NULL';
        $existantes = [];
        foreach ($em->createQuery($dql)->setParameter('annee', $annee)->getResult() as $dependance) {
            /** @var WorkflowEtapeDependance $dependance */
            $existantes[$dependance->getEtapeSuivante()->getCode()][] = $dependance;
        }

        foreach ($fichier as $code => $etapeData) {
            $this->importerDependances($etapes[$code], $etapeData['dependances'], $etapes, $existantes[$code] ?? [], $compteurs);
        }

        return $compteurs;
    }



    /**
     * @param WorkflowEtape[]           $etapes     étapes de la même année
     * @param WorkflowEtapeDependance[] $actuelles  dépendances actuellement en base pour l'étape
     */
    private function importerDependances(WorkflowEtape $etape, array $dependancesFichier, array $etapes, array $actuelles, array &$compteurs): void
    {
        $em = $this->getEntityManager();

        // Les conditions existantes sont identifiées comme en base : étape requise + type d'intervenant
        $existantes = [];
        foreach ($actuelles as $dependance) {
            $existantes[$this->cleDependance($dependance->getEtapePrecedante()->getCode(), $dependance->getTypeIntervenant()?->getCode())][] = $dependance;
        }

        foreach ($dependancesFichier as $depData) {
            if (!isset($etapes[$depData['etapePrecedante']])) {
                continue; // étape requise absente de cette année
            }
            $cle = $this->cleDependance($depData['etapePrecedante'], $depData['typeIntervenant'] ?? null);

            /** @var WorkflowEtapeDependance|null $dependance */
            $dependance = empty($existantes[$cle]) ? null : array_shift($existantes[$cle]);
            if (!$dependance) {
                $dependance = new WorkflowEtapeDependance();
                $dependance->setAnnee($etape->getAnnee());
                $dependance->setEtapeSuivante($etape);
                $dependance->setEtapePrecedante($etapes[$depData['etapePrecedante']]);
                $dependance->setTypeIntervenant($depData['typeIntervenantEntity']);
                $etape->addDependance($dependance);
                $compteurs['ajoutees']++;
            } elseif ($dependance->isActive() === $depData['active']
                && $dependance->getAvancement() === $depData['avancement']
                && $dependance->getPerimetre() === $depData['perimetreEntity']) {
                continue; // identique
            } else {
                $compteurs['modifiees']++;
            }

            $dependance->setActive($depData['active']);
            $dependance->setAvancement($depData['avancement']);
            $dependance->setPerimetre($depData['perimetreEntity']);
            $em->persist($dependance);
            $em->flush($dependance);
        }

        foreach ($existantes as $restantes) {
            foreach ($restantes as $dependance) {
                $etape->removeDependance($dependance);
                $em->remove($dependance);
                $em->flush($dependance);
                $compteurs['supprimees']++;
            }
        }
    }



    private function cleDependance(string $etapePrecedante, ?string $typeIntervenant): string
    {
        return $etapePrecedante . '|' . ($typeIntervenant ?? '');
    }



    private function getPerimetre(?string $code): ?Perimetre
    {
        if (null === $code) {
            return null;
        }
        $perimetre = $this->getEntityManager()->getRepository(Perimetre::class)->findOneBy(['code' => $code]);
        if (!$perimetre) {
            throw new \RuntimeException('Périmètre « ' . $code . ' » inconnu de cette instance.');
        }

        return $perimetre;
    }



    private function getTypeIntervenant(?string $code): ?TypeIntervenant
    {
        if (null === $code) {
            return null;
        }
        $type = $this->getEntityManager()->getRepository(TypeIntervenant::class)->findOneBy(['code' => $code]);
        if (!$type) {
            throw new \RuntimeException('Type d\'intervenant « ' . $code . ' » inconnu de cette instance.');
        }

        return $type;
    }
}
