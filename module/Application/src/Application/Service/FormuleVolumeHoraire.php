<?php

namespace Application\Service;

/**
 * Description of FormuleVolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleVolumeHoraire extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\FormuleVolumeHoraire';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'form_vh';
    }

}