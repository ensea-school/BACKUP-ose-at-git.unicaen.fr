<?php

namespace Plafond\Service;

use Application\Service\AbstractEntityService;
use Plafond\Entity\Db\PlafondStructure;

/**
 * Description of PlafondStructureService
 *
 * @author UnicaenCode
 *
 * @method PlafondStructure get($id)
 * @method PlafondStructure[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method PlafondStructure newEntity()
 *
 */
class PlafondStructureService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return PlafondStructure::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'plastruct';
    }

}