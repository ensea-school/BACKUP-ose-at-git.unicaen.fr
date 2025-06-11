<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;


/**
 * Description of Perimetre
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PerimetreService extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\Perimetre::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'perim';
    }

    /**
     * Retourne la liste des périodes
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\Perimetre[]
     */
    public function getList( ?QueryBuilder $qb = null, $alias=null )
    {
        return parent::getList($qb, $alias);
    }

}