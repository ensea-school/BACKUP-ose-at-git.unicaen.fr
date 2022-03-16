<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of Departement
 */
class DepartementService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\Departement::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'd';
    }



    /**
     * Retourne la liste des pays, triés par libellé long.
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return PaysService[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.code");

        return parent::getList($qb, $alias);
    }



    public function save($entity)
    {
        if ($entity->getSourceCode() == null) {
            $entity->setSourceCode($entity->getCode());
        }

        return parent::save($entity);
    }
}