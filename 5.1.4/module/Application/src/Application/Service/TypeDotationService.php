<?php

namespace Application\Service;

use Application\Entity\Db\TypeDotation;
use Application\Service\Traits\SourceAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TypeDotationService
 * @package Application\Service
 *
 * @method TypeDotation get($id)
 * @method TypeDotation[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 */
class TypeDotationService extends AbstractEntityService
{
    use SourceAwareTrait;
    
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\TypeDotation::class;
    }

    
    
    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'td';
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



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }

    /**
     * Sauvegarde une entité
     *
     * @param TypeDotation $entity
     * @throws \RuntimeException
     * @return mixed
     */
    public function save($entity){
        $entity->setSource($this->getServiceSource()->getOse());
        
        return parent::save($entity);
    }
}