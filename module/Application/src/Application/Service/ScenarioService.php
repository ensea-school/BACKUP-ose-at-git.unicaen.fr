<?php

namespace Application\Service;

use Application\Entity\Db\Scenario;
use Application\Service\Traits\ContextAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of ScenarioService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Scenario get($id)
 * @method Scenario[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Scenario newEntity()
 *
 */
class ScenarioService extends AbstractEntityService
{
    use ContextAwareTrait;



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
     * Filtre la liste des services selon lecontexte courant
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        if ($structure = $this->getServiceContext()->getStructure()){
            $this->finderByStructure($structure, $qb, $alias);
        }

        return $qb;
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