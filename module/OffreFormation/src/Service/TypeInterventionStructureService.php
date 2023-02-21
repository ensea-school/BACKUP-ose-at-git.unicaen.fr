<?php

namespace OffreFormation\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use OffreFormation\Entity\Db\TypeInterventionStructure;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;

/**
 * Description of TypeInterventionStructureService
 *
 * @method TypeInterventionStructure[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 *
 */
class TypeInterventionStructureService extends AbstractEntityService
{
    use TypeInterventionServiceAwareTrait;

    /**
     * Liste des types d'intervention
     *
     * @var TypeInterventionStructure[]
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
        return TypeInterventionStructure::class;
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