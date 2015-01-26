<?php

namespace Application\Service;

/**
 * Description of FormuleIntervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleIntervenant extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\FormuleIntervenant';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'form_i';
    }

}