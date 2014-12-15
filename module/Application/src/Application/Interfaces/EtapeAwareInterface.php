<?php

namespace Application\Interfaces;

use Application\Entity\Db\Etape;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface EtapeAwareInterface
{

    /**
     * Spécifie l'étape concernée.
     *
     * @param Etape $etape l'étape concernée
     * @return self
     */
    public function setEtape(Etape $etape);

    /**
     * Retourne l'étape concernée.
     *
     * @return Etape
     */
    public function getEtape();
}