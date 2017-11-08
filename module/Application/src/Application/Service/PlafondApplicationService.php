<?php

namespace Application\Service;

use Application\Entity\Db\PlafondApplication;

/**
 * Description of PlafondApplicationService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method PlafondApplication get($id)
 * @method PlafondApplication[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method PlafondApplication newEntity()
 *
 */
class PlafondApplicationService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return PlafondApplication::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'papp';
    }

}