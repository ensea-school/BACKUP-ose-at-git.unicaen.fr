<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeContrat;

/**
 * Description of Contrat
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Contrat extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Contrat';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'c';
    }

    /**
     * Recherche par type 
     *
     * @param TypeContrat $type
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByType(TypeContrat $type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.typeContrat", 'tc')
                ->andWhere("tc = :tc")
                ->setParameter('tc', $type);

        return $qb;
    }
}