<?php

namespace Application\Service;

/**
 * Description of GroupeTypeFormation
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GroupeTypeFormationService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\GroupeTypeFormation::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'gtf';
    }
}