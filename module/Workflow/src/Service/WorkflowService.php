<?php

namespace Workflow\Service;

use Application\Provider\Tbl\TblProvider;
use Application\Service\AbstractService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Traversable;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuthentification\Service\Traits\AuthorizeServiceAwareTrait;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;
use Workflow\Entity\Db\TblWorkflow;
use Workflow\Entity\Db\WfEtape;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;

/**
 * Description of WorkflowService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class WorkflowService extends AbstractService
{
    const ETAPES_CACHE_ID = 'Workflow_Service_WorkflowService_getEtapes';

    use ContextServiceAwareTrait;
    use EntityManagerAwareTrait;
    use AuthorizeServiceAwareTrait;
    use TableauBordServiceAwareTrait;

    /**
     * @var array Feuilles de route
     */
    private array $feuillesDeRoute = [];

    private array $workflowEtapes = [];



    /**
     * @return array|WorkflowEtape[]
     */
    public function getEtapes(): array
    {
        if (empty($this->workflowEtapes)) {
            $this->workflowEtapes = [];

            $dql = "
            SELECT 
                we, d, wep
            FROM 
                " . WorkflowEtape::class . " we
                LEFT JOIN we.dependances d
                LEFT JOIN d.etapePrecedante wep
            ORDER BY 
                we.ordre, wep.ordre";

            $query = $this->getEntityManager()->createQuery($dql);
            $query->enableResultCache(true);
            $query->setResultCacheId(self::ETAPES_CACHE_ID);
            $iterable = $query->getResult();
            foreach ($iterable as $we) {
                $this->workflowEtapes[$we->getCode()] = $we;
            }

            $dataFile = require getcwd() . '/data/workflow_etapes.php';
            foreach ($dataFile as $weCode => $weData) {
                if (isset($weData['contraintes']) && !empty($weData['contraintes'])) {
                    foreach ($weData['contraintes'] as $contrainte) {
                        $wec = $this->workflowEtapes[$contrainte];
                        $this->workflowEtapes[$weCode]->__addContrainte($wec);
                    }
                }
            }
        }
        return $this->workflowEtapes;
    }



    public function clearEtapesCache(): self
    {
        $em = $this->getEntityManager();

        $cache = $em->getConfiguration()->getResultCache();
        $cache->deleteItem(self::ETAPES_CACHE_ID);
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
            $em->flush($etape);
            $order++;
        }
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



    /**
     * @param WfEtapeService|WorkflowEtape|TblWorkflow|string $etape
     * @param Intervenant|null                                $intervenant
     * @param Structure|null                                  $structure
     * @return WorkflowEtape
     */
    public function getPreviousAccessibleEtape($etape, Intervenant $intervenant = null, Structure $structure = null)
    {
        [$etapeCode, $intervenant, $structure] = $this->prepareEtapeParams($etape, $intervenant, $structure);

        $fdr       = $this->getFeuilleDeRoute($intervenant, $structure);
        $isCurrent = false;
        foreach ($fdr as $etape) {
            if ($isCurrent && $etape->isAtteignable() && $etape->getUrl() && $this->isAllowed($etape)) {
                return $etape;
            }
            if ($etape->getEtape()->getCode() == $etapeCode) {
                $isCurrent = true;
            }
        }

        return null;
    }



    protected function prepareEtapeParams($etape, Intervenant $intervenant = null, Structure $structure = null)
    {
        switch (true) {
            case $etape === WfEtape::CURRENT || empty($etape):
                $etape     = $this->getEtapeCourante($intervenant, $structure);
                $etapeCode = $etape->getEtape()->getCode();
                break;
            case $etape === WfEtape::NEXT:
                $etape     = $this->getNextEtape($this->getEtapeCourante($intervenant, $structure), $intervenant, $structure);
                $etapeCode = $etape->getEtape()->getCode();
                break;
            case $etape instanceof WfEtape:
                $etapeCode = $etape->getCode();
                break;
            case $etape instanceof WorkflowEtape:
                $etapeCode = $etape->getEtape()->getCode();
                if (!$intervenant) $intervenant = $etape->getIntervenant();
                if (!$structure) $structure = $etape->getStructure();
                break;
            case $etape instanceof TblWorkflow:
                $etapeCode = $etape->getEtape()->getCode();
                if (!$intervenant) $intervenant = $etape->getIntervenant();
                if (!$structure) $structure = $etape->getStructure();
                break;
            case $etape instanceof TypeVolumeHoraire:
                $mapping   = [
                    TypeVolumeHoraire::CODE_PREVU   => WfEtape::CODE_SERVICE_SAISIE,
                    TypeVolumeHoraire::CODE_REALISE => WfEtape::CODE_SERVICE_SAISIE_REALISE,
                ];
                $etapeCode = $mapping[$etape->getCode()];
                break;
            default:
                $etapeCode = (string)$etape;
        }

        return [
            $etapeCode,
            $intervenant,
            $structure,
        ];
    }



    /**
     * @param Intervenant|null $intervenant
     * @param Structure|null   $structure
     *
     * @return WorkflowEtape|null
     */
    public function getEtapeCourante(Intervenant $intervenant = null, Structure $structure = null)
    {
        $fdr = $this->getFeuilleDeRoute($intervenant, $structure);

        foreach ($fdr as $etape) {
            if ($etape->isCourante()) return $etape;
        }

        return null;
    }



    /**
     *
     * @param Intervenant|null $intervenant
     * @param Structure|null   $structure
     *
     * @return WorkflowEtape[]
     */
    public function getFeuilleDeRoute(Intervenant $intervenant = null, Structure $structure = null)
    {
        return null;
        if (!$intervenant || !$structure) {
            /* Filtrage en fonction du contexte */
            if (!$role = $this->getServiceContext()->getSelectedIdentityRole()) return null;
            if (!$intervenant && $role->getIntervenant()) {
                $intervenant = $role->getIntervenant();
            }
            if (!$structure && $role->getStructure()) {
                $structure = $role->getStructure();
            }
        }

        if (!$intervenant) {
            throw new \LogicException('L\'intervenant n\'a pas été précisé');
        }

        $iid = $intervenant->getId();
        $sid = $structure ? $structure->getId() : 0;

        if (true || !isset($this->feuillesDeRoute[$iid][$sid])) {
            $wie = $this->getEtapes($intervenant, $structure);

            $this->feuillesDeRoute[$iid][$sid] = [];
            foreach ($wie as $e) {
                $eid = $e->getEtape()->getId();
                if (!isset($this->feuillesDeRoute[$iid][$sid][$eid])) {

                    $we = new WorkflowEtape();
                    $we->setIntervenant($intervenant);
                    $we->setStructure($structure);
                    $we->setEtape($e->getEtape());

                    $url = $this->getUrl($e->getEtape()->getRoute(), ['intervenant' => $intervenant->getId()]);
                    $we->setUrl($url);

                    $this->feuillesDeRoute[$iid][$sid][$eid] = $we;
                }
                $this->feuillesDeRoute[$iid][$sid][$eid]->addEtape($e);
            }
            $this->calculEtats($this->feuillesDeRoute[$iid][$sid]);
        }

        $feuillleDeRoute = $this->feuillesDeRoute[$iid][$sid];

        return $feuillleDeRoute;
    }



    /**
     * @param Intervenant|null $intervenant
     *
     * @return TblWorkflow[]
     */
    protected function getEtapesOld(Intervenant $intervenant, ?Structure $structure = null, bool $calcIfEmpty = true)
    {

        $dql = "
        SELECT
          we, tw, str, dblo, dep
        FROM
          Workflow\Entity\Db\TblWorkflow tw
          JOIN tw.etape we
          LEFT JOIN tw.structure str
          LEFT JOIN tw.etapeDeps dblo
          LEFT JOIN dblo.wfEtapeDep dep
        WHERE
          tw.intervenant = :intervenant
          " . ($structure ? "AND (tw.structure IS NULL OR str.ids LIKE :structure)" : '') . "
        ORDER BY
          we.ordre, str.libelleCourt
        ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('intervenant', $intervenant);
        if ($structure) $query->setParameter('structure', $structure->idsFilter());
        $etapes = $query->getResult();

        if (empty($etapes) && $calcIfEmpty) {
            $this->calculerTableauxBord([], $intervenant);

            return $this->getEtapes($intervenant, $structure, false);
        }

        /* @var $etapes TblWorkflow[] */
        if ($this->getServiceContext()->getSelectedIdentityRole()->getIntervenant()) {
            foreach ($etapes as $etape) {
                $we = $etape->getEtape();
                if ($we->getRouteIntervenant()) {
                    $we->setRoute($we->getRouteIntervenant());
                }
            }
        }

        return $etapes;
    }



    /**
     * @param array|string    $tableauxBords
     * @param Intervenant|int $intervenant
     */
    public function calculerTableauxBord(array|string|null $tableauxBords, Intervenant|int $intervenant): array
    {
        $errors = [];

        $deps = [
            TblProvider::FORMULE                 => [TblProvider::AGREMENT, TblProvider::PAIEMENT],
            TblProvider::PIECE_JOINTE_DEMANDE    => [TblProvider::PIECE_JOINTE],
            TblProvider::PIECE_JOINTE_FOURNIE    => [TblProvider::PIECE_JOINTE],
            TblProvider::CANDIDATURE             => [TblProvider::WORKFLOW],
            TblProvider::AGREMENT                => [TblProvider::WORKFLOW],
            TblProvider::CLOTURE_REALISE         => [TblProvider::WORKFLOW],
            TblProvider::CONTRAT                 => [TblProvider::WORKFLOW],
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
                if ($intervenant instanceof \Intervenant\Entity\Db\Intervenant) {
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



    /**
     * @param WfEtapeService|WorkflowEtape|TblWorkflow|string $etape
     * @param Intervenant|null                                $intervenant
     * @param Structure|null                                  $structure
     *
     * @return WorkflowEtape
     */
    public function getEtape($etape, Intervenant $intervenant = null, Structure $structure = null)
    {
        [$etapeCode, $intervenant, $structure] = $this->prepareEtapeParams($etape, $intervenant, $structure);

        $fdr = $this->getFeuilleDeRoute($intervenant, $structure);
        if ($fdr) {
            foreach ($fdr as $etape) {
                if ($etape->getEtape()->getCode() == $etapeCode) {
                    return $etape;
                }
            }
        }

        return null;
    }



    /**
     * Generates a url given the name of a route.
     *
     * @param string            $name               Name of the route
     * @param array             $params             Parameters for the link
     * @param array|Traversable $options            Options for the route
     * @param bool              $reuseMatchedParams Whether to reuse matched parameters
     *
     * @return string Url                         For the link href attribute
     * @see    \Laminas\Mvc\Router\RouteInterface::assemble()
     *
     */
    protected function getUrl($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        $url = \AppAdmin::container()->get('ViewHelperManager')->get('url');

        /* @var $url \Laminas\View\Helper\Url */
        return $url->__invoke($name, $params, $options, $reuseMatchedParams);
    }



    /**
     * @param $data WorkflowEtape[]
     */
    protected function calculEtats($data)
    {
        $couranteDefined = false;

        foreach ($data as $etape) {

            $atteignable = false;
            $franchies   = 0;
            $etapes      = $etape->getEtapes();
            foreach ($etapes as $sEtape) {
                if ($sEtape->getAtteignable()) $atteignable = true;

                $franchies += $sEtape->getFranchie();
            }
            $etape->setAtteignable($atteignable);
            $etape->setFranchie($franchies / count($etapes));

            if (!$couranteDefined && $atteignable && $etape->getFranchie() < 1 && $etape->getUrl()) {
                /* Une étape courante doit :
                 * - être atteignable
                 * - ne pas être déjà intégralement franchie
                 * - pouvoir être accessible via son URL (en fonction des droits)
                 * - être la première étape disponible dans la feuille de route
                 */
                $etape->setCourante(true);
                $couranteDefined = true;
            } else {
                $etape->setCourante(false);
            }
        }
    }



    /**
     * @param WfEtapeService|WorkflowEtape|TblWorkflow|string $etape
     * @param Intervenant|null                                $intervenant
     * @param Structure|null                                  $structure
     *
     * @return WorkflowEtape
     */
    public function getNextEtape($etape, Intervenant $intervenant = null, Structure $structure = null)
    {
        [$etapeCode, $intervenant, $structure] = $this->prepareEtapeParams($etape, $intervenant, $structure);

        $fdr       = $this->getFeuilleDeRoute($intervenant, $structure);
        $isCurrent = false;
        foreach ($fdr as $etape) {
            if ($isCurrent) {
                return $etape;
            }
            if ($etape->getEtape()->getCode() == $etapeCode) {
                $isCurrent = true;
            }
        }

        return null;
    }



    public function isAllowed($etape)
    {
        if ($etape instanceof WorkflowEtape) {
            $etape = $etape->getEtape();
        }
        if (!$etape instanceof WfEtape) {
            throw new \Exception('L\'étape fournie n\'est pas de classe WfEtape');
        }

        $resource = \Application\Util::routeToActionResource($etape->getRoute());

        return $this->getServiceAuthorize()->isAllowed($resource);
    }



    /**
     * @param WfEtapeService|WorkflowEtape|TblWorkflow|string $etape
     * @param Intervenant|null                                $intervenant
     * @param Structure|null                                  $structure
     *
     * @return WorkflowEtape
     */
    public function getNextAccessibleEtape($etape, Intervenant $intervenant = null, Structure $structure = null)
    {
        [$etapeCode, $intervenant, $structure] = $this->prepareEtapeParams($etape, $intervenant, $structure);

        $fdr       = $this->getFeuilleDeRoute($intervenant, $structure);
        $isCurrent = false;
        foreach ($fdr as $etape) {
            if ($isCurrent && $etape->isAtteignable() && $etape->getFranchie() < 1 && $etape->getUrl() && $this->isAllowed($etape)) {
                return $etape;
            }
            if ($etape->getEtape()->getCode() == $etapeCode) {
                $isCurrent = true;
            }
        }

        return $this->getEtapeCourante($intervenant, $structure);
    }



    /**
     * @return $this
     * @throws \Doctrine\DBAL\DBALException
     */
    public function calculerTout()
    {
        $this->getServiceTableauBord()->calculer(TblProvider::WORKFLOW);

        return $this;
    }
}