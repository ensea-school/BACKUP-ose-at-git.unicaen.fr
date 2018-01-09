<?php

namespace Application\Service;

use Application\Entity\Db\TypeModulateurStructure;
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
     * @var TypeModulateurStructure[]
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
        return TypeModulateurStructure::class;
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