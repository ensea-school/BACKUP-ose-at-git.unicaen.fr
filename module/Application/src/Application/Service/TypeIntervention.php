<?php

namespace Application\Service;

use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeIntervention as Entity;

/**
 * Description of TypeIntervention
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeIntervention extends AbstractService
{

    /**
     * Repository
     *
     * @var Repository
     */
    protected $repo;

    /**
     * Liste des types d'intervention
     *
     * @var Entity[]
     */
    protected $typesIntervention;




    /**
     * Retourne la liste des types d'intervention
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByAll( QueryBuilder $qb=null )
    {
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('ti');

        $qb->addOrderBy('ti.ordre');

        return $qb;
    }

    /**
     *
     * @return EntityRepository
     */
    public function getRepo()
    {
        if( empty($this->repo) ){
            $this->getEntityManager()->getFilters()->enable("historique");
            $this->repo = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeIntervention');
        }
        return $this->repo;
    }

    /**
     * Liste des types d'intervention
     *
     * @return Entity[]
     */
    public function getTypesIntervention()
    {
        if (! $this->typesIntervention){
            $til = $this->finderByAll()->getQuery()->execute();

            $this->typesIntervention = array();
            foreach( $til as $ti ){
                $this->typesIntervention[$ti->getId()] = $ti;
            }
        }
        return $this->typesIntervention;
    }
}