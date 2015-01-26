<?php

namespace Application\Service;

/**
 * Description of FormuleServiceModifie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleServiceModifie extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\FormuleServiceModifie';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'form_sm';
    }

}