<?php

namespace Application\Service;

use Application\Entity\Db\WfEtape as WfEtapeEntity;


/**
 * Description of Service
 *
 * @author Bertrand
 */
class WfEtape extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return WfEtapeEntity::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'e';
    }

    /**
     * Recherche une étapde par son code.
     * 
     * @param string $code
     * @return WfEtapeEntity
     */
    public function getByCode($code)
    {
        return $this->finderByCode($code)->getQuery()->getOneOrNullResult();
    }
}