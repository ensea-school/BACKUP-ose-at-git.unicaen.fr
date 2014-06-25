<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Etape as EtapeEntity;
use Application\Entity\Db\Service as ServiceEntity;
use Application\Entity\Db\Structure as StructureEntity;

/**
 * Description of Service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Service extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Service';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 's';
    }

    /**
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return mixed
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        $entity->setAnnee( $this->getContextProvider()->getGlobalContext()->getAnnee() );
        $entity->setValiditeDebut(new \DateTime );
        if ($this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            $entity->setIntervenant( $this->getContextProvider()->getGlobalContext()->getIntervenant() );
        }
        return $entity;
    }

    /**
     * Suvegarde une entité
     *
     * @param ServiceEntity $entity
     * @throws \Common\Exception\RuntimeException
     */
    public function save($entity)
    {
        if (! $entity->getEtablissement()){
            $entity->setEtablissement( $this->getContextProvider()->getGlobalContext()->getEtablissement() );
        }
        if (! $entity->getIntervenant() && $this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            $entity->setIntervenant( $this->getContextProvider()->getGlobalContext()->getIntervenant() );
        }
        $result = parent::save($entity);
        /* Sauvegarde automatique des volumes horaires associés */
        $serviceVolumeHoraire = $this->getServiceLocator()->get('applicationVolumeHoraire');
        /* @var $serviceVolumeHoraire VolumeHoraire */
        foreach( $entity->getVolumeHoraire() as $volumeHoraire ){
            if ($volumeHoraire->getRemove()){
                $serviceVolumeHoraire->delete($volumeHoraire);
            }else{
                $serviceVolumeHoraire->save( $volumeHoraire );
            }
        }
        return $result;
    }

    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param EtapeEntity $etape
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByEtape( EtapeEntity $etape, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->join('Application\Entity\Db\ElementPedagogique', 'ep', \Doctrine\ORM\Query\Expr\Join::WITH, 'ep.id = s.elementPedagogique');
        $qb->andWhere('ep.etape = :etape')->setParameter('etape', $etape);
        return $qb;
    }

    /**
     * Retourne le query builder permettant de rechercher les services prévisionnels
     * selon la structure de responsabilité spécifiée. 
     * 
     * Càd les services prévisionnels satisfaisant au moins l'un des critères suivants :
     * - la structure d'enseignement (champ 'structure_ens') est la structure spécifiée OU l'une de ses filles ;
     * - la structure d'affectation (champ 'structure_aff')  est la structure spécifiée OU l'une de ses filles ;
     *
     * @param StructureEntity $structure
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByStructureResp(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $filter = "(si.structureNiv2 = :structure OR NOT (sa.structureNiv2 <> :structure AND se.structureNiv2 <> :structure))";
        $qb
                ->leftJoin("$alias.structureEns", 'se')
                ->leftJoin("$alias.structureAff", 'sa')
                ->leftJoin("$alias.intervenant", 'i')
                ->leftJoin("i.structure", 'si')
                ->andWhere($filter)
                ->setParameter('structure', $structure->getParenteNiv2());
        return $qb;
    }

    /**
     * Filtre la liste des services selon lecontexte courant
     * 
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return QueryBuilder
     */
    public function finderByContext( QueryBuilder $qb=null, $alias=null )
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();

        list($qb,$alias) = $this->initQuery($qb, $alias);
        
        $this->finderByAnnee( $context->getannee(), $qb, $alias ); // Filtre d'année obligatoire
        
        if ($role instanceof \Application\Acl\IntervenantRole){ // Si c'est un intervenant
            $this->finderByIntervenant( $context->getIntervenant(), $qb, $alias );
        }
        elseif ($role instanceof \Application\Acl\DbRole){ // Si c'est un RA
//            $this->finderByStructureEns( $role->getStructure(), $qb, $alias );
            $this->finderByStructureResp( $role->getStructure(), $qb, $alias );
        }
        
        return $qb;
    }

    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param string $statutInterv "Application\Entity\Db\IntervenantPermanent" ou "Application\Entity\Db\IntervenantExterieur"
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByStatutInterv($statutInterv, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        if (!in_array((string)$statutInterv, array("Application\Entity\Db\IntervenantPermanent", "Application\Entity\Db\IntervenantExterieur"))) {
            return $qb;
        }
        $qb
                ->join("$alias.intervenant", 'i2')
                ->andWhere("i2 INSTANCE OF $statutInterv");
        return $qb;
    }

    /**
     * 
     * @param \stdClass $filter
     * @return array
     */
    public function getResumeService($filter)
    {
        $role           = $this->getContextProvider()->getSelectedIdentityRole();
        $structureEnsId = $role->getStructure()->getId();
        
        $whereFilter          = array();
        $filtreOffreFormation = false;
        if (isset($filter->intervenant)) {
            $whereFilter[] = 'INTERVENANT_ID = ' . $filter->intervenant->getId();
        }
        if (isset($filter->structureEns)) {
            $whereFilter[] = 'STRUCTURE_ENS_ID = ' . $filter->structureEns->getId();
        }
        if (isset($filter->statutInterv)) {
            $whereFilter[] = "TYPE_INTERVENANT_CODE = '" . \Application\Entity\Db\TypeIntervenant::$classToCode[$filter->statutInterv] . "'";
        }
        if (isset($filter->etape)) {
            $whereFilter[] = 'ETAPE_ID = ' . $filter->etape->getId();
            $filtreOffreFormation = true;
        }
        if (isset($filter->elementPedagogique)) {
            $whereFilter[] = 'ELEMENT_PEDAGOGIQUE_ID = ' . $filter->elementPedagogique->getId();
            $filtreOffreFormation = true;
        }
        $whereFilter = $whereFilter ? ' AND ' . implode(' AND ', $whereFilter) : null;
        
        $queryServices = <<<EOS
select 
  NOM_USUEL ,
  PRENOM ,
  INTERVENANT_ID ,
  SOURCE_CODE ,
  TYPE_INTERVENANT_CODE ,
  TYPE_INTERVENTION_ID ,
  sum(TOTAL_HEURES) TOTAL_HEURES,
  v.total_hetd TOTAL_HETD,
  v.heures_comp HEURES_COMP
from V_RESUME_SERVICE v
where (v.STRUCTURE_ENS_ID = $structureEnsId or v.STRUCTURE_AFF_ID = $structureEnsId)
  $whereFilter
group by 
  NOM_USUEL ,
  PRENOM ,
  INTERVENANT_ID ,
  SOURCE_CODE ,
  TYPE_INTERVENANT_CODE ,
  TYPE_INTERVENTION_ID ,
  v.total_hetd,
  v.heures_comp
EOS;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($queryServices);
        $data = $stmt->fetchAll();
        $dataService = array();
        foreach ($data as $r) {
            $intervenantId = $r['INTERVENANT_ID'];
            $typeId        = $r['TYPE_INTERVENTION_ID'];
            $dataService[$intervenantId]['intervenant'] = $r;
            if ($typeId) {
                $dataService[$intervenantId]['service'][$typeId] = $r['TOTAL_HEURES'];
            }
        }
//        var_dump($queryServices, $dataService);die;
        
        $whereFilter = array();
        if (isset($filter->intervenant)) {
            $whereFilter[] = 'INTERVENANT_ID = ' . $filter->intervenant->getId();
        }
        if (isset($filter->structureEns)) {
            $whereFilter[] = 'STRUCTURE_ENS_ID = ' . $filter->structureEns->getId();
        }
        if (isset($filter->statutInterv)) {
            $whereFilter[] = "TYPE_INTERVENANT_CODE = '" . \Application\Entity\Db\TypeIntervenant::$classToCode[$filter->statutInterv] . "'";
        }
        $whereFilter = $whereFilter ? ' AND ' . implode(' AND ', $whereFilter) : null;
        
        $queryReferentiel = <<<EOS
select 
  NOM_USUEL ,
  PRENOM ,
  INTERVENANT_ID ,
  SOURCE_CODE ,
  TYPE_INTERVENANT_CODE ,
  sum(TOTAL_HEURES) TOTAL_HEURES
from V_RESUME_REFERENTIEL v
where (v.STRUCTURE_ENS_ID = $structureEnsId or v.STRUCTURE_AFF_ID = $structureEnsId)
  $whereFilter
group by 
  NOM_USUEL ,
  PRENOM ,
  INTERVENANT_ID ,
  SOURCE_CODE ,
  TYPE_INTERVENANT_CODE 
EOS;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($queryReferentiel);
        $data = $stmt->fetchAll();
        $dataReferentiel = array();
        foreach ($data as $r) {
            $intervenantId = $r['INTERVENANT_ID'];
            // NB: si un filtre 'etape' ou 'elementPedagogique' est spécifié, cela signifie que l'on recherche des 
            // services prévisionnels. Pour chaque intervenant, on ne conserve donc une ligne de référentiel que s'il 
            // existe du service prévisionnel pour ce même intervenant.
            if ($filtreOffreFormation && !isset($dataService[$intervenantId])) {
                continue;
            }
            $dataReferentiel[$intervenantId] = array(
                'intervenant' => $r,
                'referentiel' => $r['TOTAL_HEURES'],
            );
        }

        $data = \Zend\Stdlib\ArrayUtils::merge($dataReferentiel, $dataService, true);

        uasort($data, function($a, $b) { 
            return strcmp(
                    $a['intervenant']['NOM_USUEL'] . $a['intervenant']['PRENOM'], 
                    $b['intervenant']['NOM_USUEL'] . $b['intervenant']['PRENOM']);
        });
        
        return $data;
    }
    
    /**
     * Retourne, par ID du type d'intervention, la liste des heures saisies pour le service donné
     *
     * @param integer|ServiceEntity|null $service
     * @return array
     */
    public function getTotalHeuresParTypeIntervention($service = null)
    {
        if ($service instanceof ServiceEntity) $service = $service->getId();

        $sql = 'SELECT * FROM V_SERVICE_HEURES';
        if ($service) $sql .= ' WHERE service_id = '.(int)$service;

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        $result = array();
        while($r = $stmt->fetch()){
            $result[(int)$r['SERVICE_ID']][(int)$r['TYPE_INTERVENTION_ID']] = (float)$r['HEURES'];
        }

        if ($service){
            if (array_key_exists( $service, $result)){
                return $result[$service];
            }else{
                return array();
            }
        }
        return $result;
    }
    
    /**
     * Retourne le total des heures RÉELLES de référentiel et de service d'un intervenant.
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return float
     */
    public function getTotalHeuresReelles(\Application\Entity\Db\Intervenant $intervenant)
    {
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
        
        /**
         * Service
         */
        $sql = <<<EOS
select sum(v.TOTAL_HEURES) as TOTAL_HEURES
from V_RESUME_SERVICE v
where v.INTERVENANT_ID = :intervenant and v.ANNEE_ID = :annee
EOS;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, array(
            'intervenant' => $intervenant->getId(),
            'annee' => $annee->getId()));
        $r = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $totalService = isset($r[0]) ? (float)$r[0] : 0.0;
        
        /**
         * Référentiel
         */
        $sql = <<<EOS
select sum(v.TOTAL_HEURES) as TOTAL_HEURES
from V_RESUME_REFERENTIEL v
where v.INTERVENANT_ID = :intervenant and v.ANNEE_ID = :annee
EOS;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, array(
            'intervenant' => $intervenant->getId(),
            'annee' => $annee->getId()));
        $r = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $totalRef = isset($r[0]) ? (float)$r[0] : 0.0;

        return $totalService + $totalRef;
    }

    /**
     * Détermine si un service est assuré localement (c'est-à-dire dans l'université) ou sur un autre établissement
     *
     * @param \Application\Entity\Db\Service $service
     * @return boolean
     */
    public function isLocal( ServiceEntity $service )
    {
        if (! $service->getEtablissement()) return true; // par défaut
        if ($service->getEtablissement() === $this->getContextProvider()->getGlobalContext()->getEtablissement()) return true;
        return false;
    }

    /**
     * Retourne la période courante d'un service
     * @param \Application\Entity\Db\Service $service
     * @return \Application\Entity\Db\Periode
     */
    public function getPeriode(ServiceEntity $service)
    {
        if (! $this->isLocal($service)) return null;
        if (! $service->getElementPedagogique()) return null;
        if (! $service->getElementPedagogique()->getPeriode()) return null;
        return $service->getElementPedagogique()->getPeriode();
    }

    /**
     * Détermine si on peut ajouter un nouveau service ou non
     *
     * @param \Application\Entity\Db\Intervenant $intervenant Eventuel intervenant concerné
     * @return boolean
     */
    public function canAdd($intervenant = null, $runEx = false)
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        if (!$intervenant) {
            return $this->cannotDoThat("Anomalie : aucun intervenant spécifié.", $runEx);
        }
        
        $rulesEvaluator = new \Application\Rule\Service\SaisieServiceRulesEvaluator($intervenant);
        if (!$rulesEvaluator->execute()) {
            $message = "?";
            if ($role instanceof \Application\Acl\IntervenantRole) {
                $message = "Vous ne pouvez pas saisir de service. ";
            }
            elseif ($role instanceof \Application\Acl\ComposanteDbRole) {
                $message = "Vous ne pouvez pas saisir de service pour $intervenant. ";
            }
            return $this->cannotDoThat($message . $rulesEvaluator->getMessage(), $runEx);
        }
        
        return true;
    }
}