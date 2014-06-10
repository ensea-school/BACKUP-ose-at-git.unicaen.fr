<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of Role
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Role extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Role';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'r';
    }
    
    /**
     * Retourne une liste d'entités en fonction du QueryBuilder donné
     *
     * La liste de présente sous la forme d'un tableau associatif, dont les clés sont les ID des entités et les valeurs les entités elles-mêmes
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return array
     */
    public function getList(QueryBuilder $qb=null, $alias=null )
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        $qb
                ->addSelect("tr, s")
                ->distinct()
                ->innerJoin('r.type', 'tr')
                ->innerJoin('r.structure', 's')
                ->andWhere('tr.code <> :code')->setParameter('code', 'IND')
                ->andWhere('s.niveau = :niv')->setParameter('niv', 2);
        
        return parent::getList($qb);
    }
}