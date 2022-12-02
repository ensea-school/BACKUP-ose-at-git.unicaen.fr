<?php

namespace Application\Service;

use Application\Entity\Db\Tag;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of TagService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class TagService extends AbstractEntityService
{

    /**
     * retourne la classe des entités correcpondantes
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Tag::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tag';
    }

    /**
     * Retourne la liste des tags
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Tag[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleLong");
        return parent::getList($qb, $alias);
    }
}