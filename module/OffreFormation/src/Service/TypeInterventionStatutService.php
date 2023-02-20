<?php

namespace OffreFormation\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use OffreFormation\Entity\Db\TypeInterventionStatut;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;

/**
 * Description of TypeInterventionStatutService
 *
 * @method TypeInterventionStatut[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 *
 */
class TypeInterventionStatutService extends AbstractEntityService
{
    use TypeInterventionServiceAwareTrait;

    /**
     * Liste des types d'intervention
     *
     * @var TypeInterventionStatut[]
     */
    protected $typesInterventionStatut;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeInterventionStatut::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'typeintervstatut';
    }

}