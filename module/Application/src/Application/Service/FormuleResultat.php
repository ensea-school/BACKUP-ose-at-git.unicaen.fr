<?php

namespace Application\Service;

/**
 * Description of FormuleResultat
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleResultat extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\FormuleResultat';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'form_r';
    }

}