<?php

namespace Application\Service;

/**
 * Description of FormuleResultatService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleResultatService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\FormuleResultatService';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'form_rs';
    }

}