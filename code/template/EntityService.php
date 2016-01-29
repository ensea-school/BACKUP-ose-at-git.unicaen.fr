<?php

namespace <namespace>;

use Application\Entity\Db\<entity>;

/**
 * Description of <classname>
 *
 * @author <author>
 *
 * @method <entity> get($id)
 * @method <entity>[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method <entity> newEntity()
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
    public function getEntityClass()
    {
        return <entity>::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return '<alias>';
    }

}