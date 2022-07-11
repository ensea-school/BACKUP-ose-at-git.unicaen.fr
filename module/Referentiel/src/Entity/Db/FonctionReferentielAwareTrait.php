<?php

namespace Application\Entity\Db\Traits;

use Referentiel\Entity\Db\FonctionReferentiel;

/**
 * Description of FonctionReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FonctionReferentielAwareTrait
{
    protected ?FonctionReferentiel $fonctionReferentiel = null;



    /**
     * @param FonctionReferentiel $fonctionReferentiel
     *
     * @return self
     */
    public function setFonctionReferentiel( ?FonctionReferentiel $fonctionReferentiel )
    {
        $this->fonctionReferentiel = $fonctionReferentiel;

        return $this;
    }



    public function getFonctionReferentiel(): ?FonctionReferentiel
    {
        return $this->fonctionReferentiel;
    }
}