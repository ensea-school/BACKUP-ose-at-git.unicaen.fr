<?php

namespace <namespace>;

use <entityClass>;

/**
 * Description of <classname>
 *
 * @author <author>
 *
 * @method <entityClassname> get($id)
 * @method <entityClassname>[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method <entityClassname> newEntity()
 *
 */
class <classname> extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return <entityClassname>::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string{
        return '<alias>';
    }

}