<?php

namespace Application\Service;

use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Service as EntityService;


/**
 * Description of VolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraire extends AbstractService
{

    /**
     * Repository
     *
     * @var EntityRepository
     */
    protected $repo;

    /**
     * Retourne la liste des volumes horaires selon le contexte donné
     *
     * @param array $context
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContext( array $context, QueryBuilder $qb=null )
    {
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('vh');

        if (! empty($context['service']) && $context['service'] instanceof EntityService){
            $qb->andWhere('vh.service = :service')->setParameter('service', $context['service']);
        }
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
            $this->repo = $this->getEntityManager()->getRepository('Application\Entity\Db\VolumeHoraire');
        }
        return $this->repo;
    }
}