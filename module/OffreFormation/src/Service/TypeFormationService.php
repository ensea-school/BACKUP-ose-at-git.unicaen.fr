<?php

namespace OffreFormation\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;

/**
 * Description of TypeFormation
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeFormationService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \OffreFormation\Entity\Db\TypeFormation::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'typefor';
    }
    
    public function finderByNiveau(\OffreFormation\Entity\NiveauEtape $niveau, ?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        
        $qb
                ->join("$alias.groupe", "gtf")
                ->andWhere("gtf.libelleCourt = :lib")
                ->setParameter('lib', $niveau->getLib());
        
        return $qb;
    }

    /**
     * Retourne la liste des types de formation
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return \OffreFormation\Entity\Db\TypeFormation[]
     */
    public function getList( ?QueryBuilder $qb = null, $alias=null )
    {
        [$qb,$alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleLong");
        return parent::getList($qb, $alias);
    }
}