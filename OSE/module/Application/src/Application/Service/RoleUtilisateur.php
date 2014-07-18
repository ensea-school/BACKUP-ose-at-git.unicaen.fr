<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of RoleUtilisateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RoleUtilisateur extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\RoleUtilisateur';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ru';
    }

    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByUtilisateur(\Application\Entity\Db\Utilisateur $utilisateur, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.utilisateur = :utilisateur")->setParameter('utilisateur', $utilisateur);
        return $qb;
    }
}