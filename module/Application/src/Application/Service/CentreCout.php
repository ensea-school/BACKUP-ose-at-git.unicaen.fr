<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;


/**
 * Description of CentreCout
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CentreCout extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\CentreCout';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'cc';
    }
}