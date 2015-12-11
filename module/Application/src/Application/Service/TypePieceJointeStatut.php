<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypePieceJointeStatut as TypePieceJointeStatutEntity;
use Application\Entity\Db\StatutIntervenant as StatutIntervenantEntity;

/**
 * Description of TypePieceJointeStatut
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypePieceJointeStatut extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypePieceJointeStatutEntity::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tpjs';
    }

    /**
     * Retourne la liste des enregistrements correspondant aux statut intervenant spécifié.
     *
     * @param StatutIntervenantEntity $statut
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByStatutIntervenant(StatutIntervenantEntity $statut, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.statut = :statut")->setParameter('statut', $statut);
        return $qb;
    }

    /**
     * Retourne la liste des enregistrements correspondant au témoin de premier recrutement spécifié.
     *
     * @param bool $premierRecrutement
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByPremierRecrutement($premierRecrutement, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.premierRecrutement = :flag")->setParameter('flag', $premierRecrutement);
        return $qb;
    }

    /**
     * Retourne la liste des enregistrements.
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return TypePieceJointeStatutEntity[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");
        return parent::getList($qb, $alias);
    }
}