<?php

namespace Application\Interfaces;

use Application\Entity\NiveauEtape;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface NiveauEtapeAwareInterface
{

    /**
     * Spécifie le niveau d'étape concerné.
     *
     * @param NiveauEtape $niveauEtape le niveau d'étape concerné
     * @return self
     */
    public function setNiveauEtape(NiveauEtape $niveauEtape);

    /**
     * Retourne le niveau d'étape concerné.
     *
     * @return NiveauEtape
     */
    public function getNiveauEtape();
}