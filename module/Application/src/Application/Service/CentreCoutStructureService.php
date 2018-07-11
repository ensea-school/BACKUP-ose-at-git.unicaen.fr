<?php

namespace Application\Service;

/**
 * Description of TypeCentreCoutStructure
 */
class CentreCoutStructureService extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\CentreCoutStructure::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ccs';
    }

    }
