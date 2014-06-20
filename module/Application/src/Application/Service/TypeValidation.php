<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of TypeValidation
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeValidation extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\TypeValidation';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'typev';
    }
    
    /**
     * Recherche par code 
     *
     * @param string $code
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByCode($code, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->andWhere("$alias.code = :code")
                ->setParameter('code', $code);

        return $qb;
    }

    /**
     * Retourne la liste des types de volumes horaires
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\TypeValidation[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return parent::getList($qb, $alias);
    }

}