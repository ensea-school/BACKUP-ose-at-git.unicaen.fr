<?php

namespace Workflow\Service;

use Application\Entity\Db\Annee;
use Application\Provider\Tbl\TblProvider;
use Application\Service\AbstractService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\View\Helper\Url;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuthentification\Service\Traits\AuthorizeServiceAwareTrait;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Model\FeuilleDeRoute;

/**
 * Description of WorkflowService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class WorkflowService extends AbstractService
{
    const ETAPES_CACHE_ID = 'Workflow_Service_getEtapes';

    use ContextServiceAwareTrait;
    use EntityManagerAwareTrait;
    use AuthorizeServiceAwareTrait;
    use TableauBordServiceAwareTrait;
    use BddAwareTrait;

    /**
     * @var array|FeuilleDeRoute[]
     */
    private array $feuillesDeRoute = [];

    /** @var array|WorkflowEtape[][] */
    private array $workflowEtapes = [];



    public function __construct(
        private readonly Url $urlManager)
    {
    }



    /**
     * @return array|WorkflowEtape[]
     */
    public function getEtapes(Annee|int|null $annee = null): array
    {
        if ($annee instanceof Annee) {
            $anneeId = $annee->getId();
        } elseif (isset($annee)) {
            $anneeId = $annee;
        } else {
            $anneeId = $this->getServiceContext()->getAnnee()->getId();
        }

        if (empty($this->workflowEtapes[$anneeId])) {
            $this->workflowEtapes[$anneeId] = [];

            $dql = "
            SELECT 
                we, partial a.{id}, d, wep, weperimetre
            FROM 
                " . WorkflowEtape::class . " we
                JOIN we.annee a
                LEFT JOIN we.dependances d WITH d.histoDestruction IS NULL
                LEFT JOIN d.etapePrecedante wep
                LEFT JOIN we.perimetre weperimetre
            WHERE
                a.id = :annee
                AND we.histoDestruction IS NULL
            ORDER BY 
                we.ordre, wep.ordre";

            $query = $this->getEntityManager()->createQuery($dql);
            $query->setParameter('annee', $anneeId);
            $query->enableResultCache(true);
            $query->setResultCacheId(self::ETAPES_CACHE_ID . '_' . $anneeId);

            /** @var WorkflowEtape[] $iterable */
            $iterable = $query->getResult();
            foreach ($iterable as $we) {
                if ($we->getAnnee()->getId() == $anneeId) {
                    $this->workflowEtapes[$anneeId][$we->getCode()] = $we;
                }
            }

            $dataFile = require getcwd() . '/data/workflow_etapes.php';
            foreach ($dataFile as $weCode => $weData) {
                if (isset($weData['contraintes']) && !empty($weData['contraintes'])) {
                    foreach ($weData['contraintes'] as $contrainte) {
                        $wec = $this->workflowEtapes[$anneeId][$contrainte];
                        $this->workflowEtapes[$anneeId][$weCode]->__addContrainte($wec);
                    }
                }
                foreach ($weData['avancements'] as $avancement => $description) {
                    if ($description) {
                        $this->workflowEtapes[$anneeId][$weCode]->__addAvancement($avancement, $description);
                    }
                }
            }
        }
        return $this->workflowEtapes[$anneeId];
    }



    public function clearEtapesCache(): self
    {
        $em = $this->getEntityManager();

        $cache = $em->getConfiguration()->getResultCache();
        $items = [];
        for ($a = Annee::MIN_DATA; $a <= Annee::MAX; $a++) {
            $items[] = self::ETAPES_CACHE_ID . '_' . $a;
        }
        $cache->deleteItems($items);
        $this->workflowEtapes = [];

        return $this;
    }



    /**
     * Le tableau sera une liste de codes d'étapes ordonnancée
     * $liste est une liste des codes d'étapes
     *
     * @param array $liste
     * @return self
     */
    public function trier(array $liste): self
    {
        $em     = $this->getEntityManager();
        $etapes = $this->getEtapes();

        /* Tests de faisabilité */
        $precedantes = [];
        foreach ($liste as $code) {
            if (!isset($etapes[$code])) {
                throw new \Exception('L\'étape de workflow au code ' . $code . ' n\'existe pas');
            }
            foreach ($precedantes as $precedante) {
                $this->triCheckEtape($precedante, $etapes[$code]);
            }
            $precedantes[] = $etapes[$code];
        }

        $order = 1;
        foreach ($liste as $code) {
            if (!isset($etapes[$code])) {
                throw new \Exception('L\'étape dont le code est "' . $code . '" n\'existe pas');
            }
            $etape = $etapes[$code];
            $etape->setOrdre($order);
            $em->persist($etape);
            $order++;
        }
        $em->flush();
        $this->clearEtapesCache();

        return $this;
    }



    private function triCheckEtape(WorkflowEtape $etapePrecedante, WorkflowEtape $etapeSuivante): void
    {
        $plib = '"' . $etapePrecedante->getLibelleAutres() . '"';
        $slib = '"' . $etapeSuivante->getLibelleAutres() . '"';

        if (in_array($etapeSuivante, $etapePrecedante->getContraintes())) {
            throw new \Exception("Par conception, l'étape $plib ne peut pas être positionnée avant $slib");
        }

        foreach ($etapePrecedante->getDependances() as $dependance) {
            if ($dependance->getEtapePrecedante() === $etapeSuivante) {
                throw new \Exception("Une dépendance empêche de positionner l'étape $plib avant $slib");
            }
        }
    }



    public function saveEtape(WorkflowEtape $etape): self
    {
        $em = $this->getEntityManager();

        $em->persist($etape);
        $em->flush($etape);

        $this->clearEtapesCache();

        return $this;
    }



    public function saveEtapeDependance(WorkflowEtapeDependance $dependance): self
    {
        $em = $this->getEntityManager();

        $em->persist($dependance);
        $em->flush($dependance);

        $this->saveEtape($dependance->getEtapeSuivante());

        return $this;
    }



    public function deleteEtapeDependance(WorkflowEtapeDependance $dependance): self
    {
        $this->getEntityManager()->remove($dependance);
        $this->getEntityManager()->flush($dependance);

        $this->clearEtapesCache();

        return $this;
    }



    public function getFeuilleDeRoute(Intervenant $intervenant, ?Structure $structure = null): FeuilleDeRoute
    {
        // Si la feuille de route n'existe pas, on la crée
        if (!array_key_exists($intervenant->getId(), $this->feuillesDeRoute)) {
            $this->feuillesDeRoute[$intervenant->getId()] =
                new FeuilleDeRoute($this, $intervenant, $this->getEtapes());
        }

        // On lui injecte la structure au besoin
        if (!$structure) {
            // Si la structure n'est pas précisée, alors on utilise la structure du contexte
            $structure = $this->getServiceContext()->getStructure();
        }
        $this->feuillesDeRoute[$intervenant->getId()]->setStructure($structure);

        return $this->feuillesDeRoute[$intervenant->getId()];
    }



    public function refreshFeuilleDeRoute(Intervenant|int $intervenant): void
    {
        if ($intervenant instanceof Intervenant) {
            $intervenant = $intervenant->getId();
        }

        if (array_key_exists($intervenant, $this->feuillesDeRoute)) {
            $this->feuillesDeRoute[$intervenant]->refresh();
        }
    }



    public function calculerTableauxBord(array|string|null $tableauxBords, Intervenant|int $intervenant): array
    {
        $errors = [];

        $deps = [
            TblProvider::SERVICE_DU              => [TblProvider::FORMULE],
            TblProvider::FORMULE                 => [TblProvider::AGREMENT, TblProvider::PAIEMENT],
            TblProvider::CANDIDATURE             => [TblProvider::WORKFLOW],
            TblProvider::AGREMENT                => [TblProvider::WORKFLOW],
            TblProvider::CLOTURE_REALISE         => [TblProvider::WORKFLOW],
            TblProvider::CONTRAT                 => [TblProvider::VALIDATION_ENSEIGNEMENT, TblProvider::VALIDATION_REFERENTIEL],
            TblProvider::DOSSIER                 => [TblProvider::WORKFLOW],
            TblProvider::PAIEMENT                => [TblProvider::WORKFLOW],
            TblProvider::PIECE_JOINTE            => [TblProvider::WORKFLOW],
            TblProvider::SERVICE                 => [TblProvider::WORKFLOW],
            TblProvider::MISSION                 => [TblProvider::WORKFLOW],
            TblProvider::MISSION_PRIME           => [TblProvider::WORKFLOW],
            TblProvider::REFERENTIEL             => [TblProvider::WORKFLOW],
            TblProvider::VALIDATION_ENSEIGNEMENT => [TblProvider::WORKFLOW],
            TblProvider::VALIDATION_REFERENTIEL  => [TblProvider::WORKFLOW],
            TblProvider::WORKFLOW                => [],
            TblProvider::PLAFOND_INTERVENANT     => [],
            TblProvider::PLAFOND_STRUCTURE       => [],
            TblProvider::PLAFOND_REFERENTIEL     => [],
            TblProvider::PLAFOND_ELEMENT         => [],
            TblProvider::PLAFOND_VOLUME_HORAIRE  => [],

        ];

        if ($tableauxBords) {
            if (is_string($tableauxBords)) $tableauxBords = [$tableauxBords];
            $tbls = [];
            foreach ($tableauxBords as $tblName) {
                $this->addTbl($tblName, $tbls, $deps);
            }
        } else {
            $tbls = $deps;
        }

        foreach ($deps as $dep => $null) {
            if (isset($tbls[$dep])) {
                if ($intervenant instanceof Intervenant) {
                    $value = $intervenant->getId();
                } else {
                    $value = $intervenant;
                }

                try {
                    $this->getServiceTableauBord()->calculer($dep, ['INTERVENANT_ID' => $value]);
                } catch (\Exception $e) {
                    $errors[$dep] = $e;
                }
            }
        }

        /* Mise à jour des entités */
        if (array_key_exists($intervenant->getId(), $this->feuillesDeRoute)) {
            foreach ($this->feuillesDeRoute[$intervenant->getId()] as $fdr) {
                foreach ($fdr as $etp) {
                    /** @var $etp WorkflowEtape */
                    foreach ($etp->getEtapes() as $etape) {
                        $this->getEntityManager()->refresh($etape);
                    }
                }
            }
        }

        return $errors;
    }



    private function addTbl($tblName, &$tbls, $deps)
    {
        $tbls[$tblName] = 1;
        if (isset($deps[$tblName])) {
            foreach ($deps[$tblName] as $dep) {
                $this->addTbl($dep, $tbls, $deps);
            }
        }
    }



    public function getUrl(?string $name = null, array $params = [], array $options = [], bool $reuseMatchedParams = false): string
    {
        return $this->urlManager->__invoke($name, $params, $options, $reuseMatchedParams);
    }



    public function calculerTout(): self
    {
        $this->getServiceTableauBord()->calculer(TblProvider::WORKFLOW);

        return $this;
    }
}