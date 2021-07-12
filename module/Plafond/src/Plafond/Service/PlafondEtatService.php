<?php

namespace Plafond\Service;

use Application\Service\AbstractEntityService;
use Plafond\Entity\Db\PlafondEtat;

/**
 * Description of PlafondEtatService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method PlafondEtat get($id)
 * @method PlafondEtat[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method PlafondEtat newEntity()
 *
 */
class PlafondEtatService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return PlafondEtat::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'plaetat';
    }

}