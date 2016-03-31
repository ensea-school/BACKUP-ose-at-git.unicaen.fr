<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\WfEtape;
use Application\Entity\Db\WfIntervenantEtape;
use Application\Entity\WorkflowEtape;
use Application\Service\Traits\ContextAwareTrait;

/**
 * Description of WorkflowService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class WorkflowService extends AbstractService
{
    use ContextAwareTrait;

    /**
     * @var array Feuilles de route
     */
    private $feuillesDeRoute = [];



    protected function prepareEtapeParams($etape, IntervenantEntity $intervenant = null, StructureEntity $structure = null)
    {
        switch(true){
            case $etape === WfEtape::CURRENT || empty($etape):
                $etape = $this->getEtapeCourante($intervenant, $structure);
                $etapeCode = $etape->getEtape()->getCode();
            break;
            case $etape === WfEtape::NEXT:
                $etape = $this->getNextEtape($this->getEtapeCourante($intervenant, $structure), $intervenant, $structure);
                $etapeCode = $etape->getEtape()->getCode();
            break;
            case $etape instanceof WfEtape:
                $etapeCode = $etape->getCode();
            break;
            case $etape instanceof WorkflowEtape:
                $etapeCode = $etape->getEtape()->getCode();
                if (!$intervenant) $intervenant = $etape->getIntervenant();
                if (!$structure)    $structure = $etape->getStructure();
            break;
            case $etape instanceof WfIntervenantEtape:
                $etapeCode = $etape->getEtape()->getCode();
                if (!$intervenant) $intervenant = $etape->getIntervenant();
                if (!$structure)    $structure = $etape->getStructure();
            break;
            case $etape instanceof TypeVolumeHoraire:
                $mapping = [
                    TypeVolumeHoraire::CODE_PREVU => WfEtape::CODE_SERVICE_SAISIE,
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
            $structure
        ];
    }



    /**
     * @param WfEtape|WorkflowEtape|WfIntervenantEtape|string $etape
     * @param IntervenantEntity|null                          $intervenant
     * @param StructureEntity|null                            $structure
     *
     * @return WorkflowEtape
     */
    public function getEtape($etape, IntervenantEntity $intervenant = null, StructureEntity $structure = null)
    {
        list($etapeCode, $intervenant, $structure) = $this->prepareEtapeParams($etape, $intervenant, $structure);

        $fdr = $this->getFeuilleDeRoute($intervenant, $structure);
        foreach ($fdr as $etape) {
            if ($etape->getEtape()->getCode() == $etapeCode) {
                return $etape;
            }
        }

        return null;
    }



    /**
     * @param WfEtape|WorkflowEtape|WfIntervenantEtape|string $etape
     * @param IntervenantEntity|null                          $intervenant
     * @param StructureEntity|null                            $structure
     *
     * @return WorkflowEtape
     */
    public function getNextEtape($etape, IntervenantEntity $intervenant = null, StructureEntity $structure = null)
    {
        list($etapeCode, $intervenant, $structure) = $this->prepareEtapeParams($etape, $intervenant, $structure);

        $fdr = $this->getFeuilleDeRoute($intervenant, $structure);
        $isCurrent = false;
        foreach ($fdr as $etape) {
            if ($isCurrent){
                return $etape;
            }
            if ($etape->getEtape()->getCode() == $etapeCode) {
                $isCurrent = true;
            }
        }

        return null;
    }



    /**
     * @param WfEtape|WorkflowEtape|WfIntervenantEtape|string $etape
     * @param IntervenantEntity|null                          $intervenant
     * @param StructureEntity|null                            $structure
     *
     * @return WorkflowEtape
     */
    public function getNextAccessibleEtape($etape, IntervenantEntity $intervenant = null, StructureEntity $structure = null)
    {
        list($etapeCode, $intervenant, $structure) = $this->prepareEtapeParams($etape, $intervenant, $structure);

        $fdr = $this->getFeuilleDeRoute($intervenant, $structure);
        $isCurrent = false;
        foreach ($fdr as $etape) {
            if ($isCurrent && $etape->isAtteignable() && $etape->getUrl()){
                return $etape;
            }
            if ($etape->getEtape()->getCode() == $etapeCode) {
                $isCurrent = true;
            }
        }

        return null;
    }



    /**
     * @param IntervenantEntity|null $intervenant
     * @param StructureEntity|null   $structure
     *
     * @return WorkflowEtape|null
     */
    public function getEtapeCourante(IntervenantEntity $intervenant = null, StructureEntity $structure = null)
    {
        $fdr = $this->getFeuilleDeRoute($intervenant, $structure);

        foreach ($fdr as $etape) {
            if ($etape->isCourante()) return $etape;
        }

        return null;
    }



    /**
     *
     * @param IntervenantEntity|null $intervenant
     * @param StructureEntity|null   $structure
     *
     * @return WorkflowEtape[]
     */
    public function getFeuilleDeRoute(IntervenantEntity $intervenant = null, StructureEntity $structure = null)
    {
        if (!$intervenant || !$structure) {
            /* Filtrage en fonction du contexte */
            if (! $role = $this->getServiceContext()->getSelectedIdentityRole()) return null;
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

        if (!isset($this->feuillesDeRoute[$iid][$sid])) {
            $wie = $this->getEtapes($intervenant, $structure);

            $this->feuillesDeRoute[$iid][$sid] = [];
            foreach ($wie as $e) {
                $eid = $e->getEtape()->getId();
                if (!isset($this->feuillesDeRoute[$iid][$sid][$eid])) {

                    $we = new WorkflowEtape();
                    $we->setIntervenant($intervenant);
                    $we->setStructure($structure);
                    $we->setEtape($e->getEtape());

                    $url = $this->getUrl($e->getEtape()->getRoute(), ['intervenant' => $intervenant->getSourceCode()]);
                    $we->setUrl($url);

                    $this->feuillesDeRoute[$iid][$sid][$eid] = $we;
                }
                $this->feuillesDeRoute[$iid][$sid][$eid]->addEtape($e);
            }
            $this->calculEtats($this->feuillesDeRoute[$iid][$sid]);
        }

        return $this->feuillesDeRoute[$iid][$sid];
    }



    /**
     * @return $this
     * @throws \Doctrine\DBAL\DBALException
     */
    public function calculerTout()
    {
        $sql = "BEGIN OSE_EVENT.CALCULER_TOUT( 'OSE_WORKFLOW' ); END;";
        $this->getEntityManager()->getConnection()->executeQuery($sql);

        return $this;
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
                /* @var $wei WfIntervenantEtape */
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
     * @param IntervenantEntity|null $intervenant
     *
     * @return WfIntervenantEtape[]
     */
    protected function getEtapes(IntervenantEntity $intervenant, StructureEntity $structure = null)
    {

        $dql = "
        SELECT
          we, tw, str, dblo, dep
        FROM
          Application\Entity\Db\TblWorkflow tw
          JOIN tw.etape we
          LEFT JOIN tw.structure str
          LEFT JOIN tw.etapeDeps dblo
          LEFT JOIN dblo.wfEtapeDep dep
        WHERE
          tw.intervenant = :intervenant
          " . ($structure ? "AND (tw.structure IS NULL OR tw.structure = :structure)" : '') . "
        ORDER BY
          we.ordre, str.libelleCourt
        ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('intervenant', $intervenant);
        if ($structure) $query->setParameter('structure', $structure);
        $etapes = $query->getResult();

        return $etapes;
    }



    /**
     * Generates a url given the name of a route.
     *
     * @see    Zend\Mvc\Router\RouteInterface::assemble()
     *
     * @param  string            $name               Name of the route
     * @param  array             $params             Parameters for the link
     * @param  array|Traversable $options            Options for the route
     * @param  bool              $reuseMatchedParams Whether to reuse matched parameters
     *
     * @return string Url                         For the link href attribute
     */
    protected function getUrl($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        $url = $this->getServiceLocator()->get('viewhelpermanager')->get('url');

        /* @var $url \Zend\View\Helper\Url */
        return $url->__invoke($name, $params, $options, $reuseMatchedParams);
    }
}