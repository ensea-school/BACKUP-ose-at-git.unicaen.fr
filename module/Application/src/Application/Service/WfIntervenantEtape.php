<?php

namespace Application\Service;

use Application\Entity\Db\WfIntervenantEtape as WfIntervenantEtapeEntity;
use RuntimeException;

/**
 * Service de gestion de la progression d'un intervenant dans le workflow.
 *
 * @author Bertrand
 */
class WfIntervenantEtape extends AbstractEntityService
{        
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return WfIntervenantEtapeEntity::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ie';
    }
}