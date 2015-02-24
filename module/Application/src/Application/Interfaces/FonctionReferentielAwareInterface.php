<?php

namespace Application\Interfaces;

use Application\Entity\Db\FonctionReferentiel;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface FonctionReferentielAwareInterface
{

    /**
     * Spécifie la fonction référentielle.
     *
     * @param FonctionReferentiel $fonctionReferentiel la fonction référentielle
     * @return self
     */
    public function setFonctionReferentiel(FonctionReferentiel $fonctionReferentiel = null);

    /**
     * Retourne la fonction référentielle.
     *
     * @return FonctionReferentiel
     */
    public function getFonctionReferentiel();
}