<?php

namespace Application\Traits;

use Application\Entity\Db\FonctionReferentiel;

/**
 * Description of FonctionReferentielAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait FonctionReferentielAwareTrait
{
    /**
     * @var FonctionReferentiel
     */
    protected $fonctionReferentiel;

    /**
     * Spécifie la fonction référentielle.
     *
     * @param FonctionReferentiel $fonctionReferentiel la fonction référentielle
     */
    public function setFonctionReferentiel(FonctionReferentiel $fonctionReferentiel = null)
    {
        $this->fonctionReferentiel = $fonctionReferentiel;

        return $this;
    }

    /**
     * Retourne la fonction référentielle.
     *
     * @return FonctionReferentiel
     */
    public function getFonctionReferentiel()
    {
        return $this->fonctionReferentiel;
    }
}