<?php

namespace Application\Service;

use Application\Entity\Db\TypeModulateurStructure as Entity;
use Application\Service\Traits\TypeModulateurStructureServiceAwareTrait;

/**
 * Description of TypeModulateurStructureService
 */
class TypeModulateurStructureService extends AbstractEntityService
{
use TypeModulateurStructureServiceAwareTrait;
    /**
     * Liste des types de modulateur par structure
     *
     * @var Entity[]
     */
    protected $typesModulateurStructure;



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
        return 'tmd';
    }
    
}