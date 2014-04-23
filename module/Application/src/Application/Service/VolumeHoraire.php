<?php

namespace Application\Service;



/**
 * Description of VolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraire extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\VolumeHoraire';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'vh';
    }

}