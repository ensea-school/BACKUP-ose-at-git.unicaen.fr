<?php

namespace Application\Service;

/**
 * Description of FonctionReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FonctionReferentiel extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\FonctionReferentiel';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'fonc_ref';
    }
}