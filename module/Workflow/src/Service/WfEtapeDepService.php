<?php

namespace Workflow\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Workflow\Entity\Db\WfEtapeDep;

/**
 * Description of WfEtapeDepService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method WfEtapeDep get($id)
 * @method WfEtapeDep[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method WfEtapeDep newEntity()
 *
 */
class WfEtapeDepService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return WfEtapeDep::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'wfed';
    }

}