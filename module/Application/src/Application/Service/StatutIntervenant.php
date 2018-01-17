<?php

namespace Application\Service;

use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of StatutIntervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StatutIntervenant extends AbstractEntityService
{
    use SourceServiceAwareTrait;
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\StatutIntervenant::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'si';
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder
     */
    public function orderBy( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return $qb;
    }

    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     * @return \Application\Entity\Db\TypeDotation
     */
    public function newEntity()
    {
        /** @var TypeDotation $entity */
        $entity = parent::newEntity();

        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());

        return $entity;
    }
}