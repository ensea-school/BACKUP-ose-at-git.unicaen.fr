<?php

namespace Workflow\Service;

use Application\Service\AbstractService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traversable;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenAuthentification\Service\Traits\AuthorizeServiceAwareTrait;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;
use Workflow\Entity\Db\TblWorkflow;
use Workflow\Entity\Db\WfEtape;
use Workflow\Entity\WorkflowEtape;

/**
 * Description of WorkflowService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class WorkflowService extends AbstractService
{
    use ContextServiceAwareTrait;
    use AuthorizeServiceAwareTrait;
    use TableauBordServiceAwareTrait;

    /**
     * @var array Feuilles de route
     */
    private $feuillesDeRoute = [];



    /**
     * @param WfEtapeService|WorkflowEtape|TblWorkflow|string $etape
     * @param Intervenant|null                                $intervenant
     * @param Structure|null                                  $structure
     *
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
                if (!$intervenant) {
                    $intervenant = $etape->getIntervenant();
                }
                if (!$structure) {
                    $structure = $etape->getStructure();
                }
                break;
            case $etape instanceof TblWorkflow:
                $etapeCode = $etape->getEtape()->getCode();
                if (!$intervenant) {
                    $intervenant = $etape->getIntervenant();
                }
                if (!$structure) {
                    $structure = $etape->getStructure();
                }
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
            if ($etape->isCourante()) {
                return $etape;
            }
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
        if (!$intervenant || !$structure) {
            /* Filtrage en fonction du contexte */
            if (!$role = $this->getServiceContext()->getSelectedIdentityRole()) {
                return null;
            }
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
    protected function getEtapes(Intervenant $intervenant, ?Structure $structure = null, bool $calcIfEmpty = true)
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
        if ($structure) {
            $query->setParameter('structure', $structure->idsFilter());
        }
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
            'formule'                 => ['agrement', 'paiement'],
            'candidature'             => ['workflow'],
            'agrement'                => ['workflow'],
            'cloture_realise'         => ['workflow'],
            'contrat'                 => ['workflow'],
            'dossier'                 => ['workflow'],
            'paiement'                => ['workflow'],
            'piece_jointe'            => ['workflow'],
            'service'                 => ['workflow'],
            'mission'                 => ['workflow'],
            'mission_prime'           => ['workflow'],
            'referentiel'             => ['workflow'],
            'validation_enseignement' => ['workflow'],
            'validation_referentiel'  => ['workflow'],
            'workflow'                => [],
            'plafond_intervenant'     => [],
            'plafond_structure'       => [],
            'plafond_referentiel'     => [],
            'plafond_element'         => [],
            'plafond_volume_horaire'  => [],

        ];

        if ($tableauxBords) {
            if (is_string($tableauxBords)) {
                $tableauxBords = [$tableauxBords];
            }
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
                if ($sEtape->getAtteignable()) {
                    $atteignable = true;
                }

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
        $this->getServiceTableauBord()->calculer('workflow');

        return $this;
    }
}
