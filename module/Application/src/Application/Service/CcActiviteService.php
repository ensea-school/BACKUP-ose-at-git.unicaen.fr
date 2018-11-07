<?php

namespace Application\Service;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of FonctionReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CcActiviteService extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\CcActivite::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ca';
    }

    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery();

        $qb->addOrderBy("$alias.libelle");

        return parent::getList($qb, $alias);
    }
}