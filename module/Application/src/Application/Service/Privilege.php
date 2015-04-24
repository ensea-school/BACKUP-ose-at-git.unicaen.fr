<?php

namespace Application\Service;

/**
 * Description of Privilege
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Privilege extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Privilege';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'priv';
    }

}