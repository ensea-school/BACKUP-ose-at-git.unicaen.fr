<?php

namespace Application\Service;

use Application\Entity\Db\Discipline as DisciplineEntity;
use Application\Service\Traits\SourceAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of DisciplineService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DisciplineService extends AbstractEntityService
{
    use \Application\Traits\SessionContainerTrait;
    use SourceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return DisciplineEntity::class;
    }



    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     * @return \Application\Entity\Db\Discipline
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());

        return $entity;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->orderBy($alias . '.sourceCode');

        return $qb;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'dis';
    }

}