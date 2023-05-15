<?php

namespace Paiement\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Paiement\Entity\Db\TypeModulateurStructure;

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