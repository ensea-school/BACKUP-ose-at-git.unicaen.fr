<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeValidation;

/**
 * Description of Validation
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Validation extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Validation';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'v';
    }
    
    /**
     * Recherche par type 
     *
     * @param TypeValidation $type
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByType(TypeValidation $type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.typeValidation", 'tv')
                ->andWhere("tv = :tv")
                ->setParameter('tv', $type);

        return $qb;
    }
}