<?php

namespace Application\Service;

use Application\Entity\Db\Scenario;

/**
 * Description of ScenarioService
 *
 * @author UnicaenCode
 *
 * @method Scenario get($id)
 * @method Scenario[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Scenario newEntity()
 *
 */
class ScenarioService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Scenario::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'scn';
    }

}