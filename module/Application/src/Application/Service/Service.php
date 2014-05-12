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
    use ContextProviderAwareTrait;

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
     * - la structure d'affectation de l'intervenant         est la structure spécifiée OU l'une de ses filles.
     *
     * @param StructureEntity $structure
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByStructureResp(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        
        $or = $qb->expr()->orX(
                "$alias.structureEns = :structure", 
                "se.structureNiv2    = :structure",
                "$alias.structureAff = :structure", 
                "sa.structureNiv2    = :structure",
                "i.structure         = :structure", 
                "si.structureNiv2    = :structure"
        );
        $qb
                ->join("$alias.structureEns", 'se')
                ->join("$alias.structureAff", 'sa')
                ->join("$alias.intervenant",  'i')
                ->join("i.structure",         'si')
                ->andWhere($or)
                ->setParameter('structure', $structure);
        
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
        $context = $this->getServiceLocator()->get('ApplicationContextProvider')->getGlobalContext();
        $role    = $this->getServiceLocator()->get('ApplicationContextProvider')->getSelectedIdentityRole();

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
     * Retourne, par ID du type d'intervention, la liste des heures saisies pour le service donné
     *
     * @param integer|ServiceEntity|null $service
     * @return array
     */
    public function getTotalHeures($service)
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
}