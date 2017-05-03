<?php

namespace Application\Service;

use Application\Entity\Db\TypeInterventionStructure as Entity;
use Application\Service\Traits\TypeInterventionAwareTrait;

/**
 * Description of TypeInterventionStructureService
 *
 * @method TypeInterventionStructure[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 *
 */
class TypeInterventionStructureService extends AbstractEntityService
{
    use TypeInterventionAwareTrait;

    /**
     * Liste des types d'intervention
     *
     * @var Entity[]
     */
    protected $typesInterventionStructure;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Entity::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'typeintervstruct';
    }

}