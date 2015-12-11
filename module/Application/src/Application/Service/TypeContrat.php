<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of TypeContrat
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeContrat extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\TypeContrat::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'typec';
    }

    /**
     * Retourne la liste des types de volumes horaires
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\TypeContrat[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return parent::getList($qb, $alias);
    }

}