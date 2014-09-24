<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of TypeRole
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeRole extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\TypeRole';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tr';
    }
}