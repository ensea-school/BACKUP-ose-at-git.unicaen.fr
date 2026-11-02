<?php

namespace PieceJointe\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use PieceJointe\Entity\Db\TypePieceJointe;

/**
 * Description of TypePieceJointe
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypePieceJointeService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypePieceJointe::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tpj';
    }



    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return TypePieceJointe[]
     */
    public function getList(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return parent::getList($qb, $alias);
    }
}