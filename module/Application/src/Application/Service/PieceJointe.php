<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypePieceJointe as TypePieceJointeEntity;
use Application\Entity\Db\PieceJointe as PieceJointeEntity;

/**
 * Description of PieceJointe
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PieceJointe extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\PieceJointe';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'pj';
    }

    /**
     * Retourne la liste des pièces jointes d'un type donné.
     *
     * @param TypePieceJointeEntity $type
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByType(TypePieceJointeEntity $type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.type = :type")->setParameter('type', $type);
        return $qb;
    }

    /**
     * Retourne la liste des pièces jointes d'un dossier donné.
     *
     * @param \Application\Entity\Db\Dossier $dossier
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByDossier(\Application\Entity\Db\Dossier $dossier, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.dossier = :dossier")->setParameter('dossier', $dossier);
        return $qb;
    }

    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return PieceJointeEntity[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");
        return parent::getList($qb, $alias);
    }
}