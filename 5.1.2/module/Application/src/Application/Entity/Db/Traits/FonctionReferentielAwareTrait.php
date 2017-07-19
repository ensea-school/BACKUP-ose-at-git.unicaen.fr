<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FonctionReferentiel;

/**
 * Description of FonctionReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FonctionReferentielAwareTrait
{
    /**
     * @var FonctionReferentiel
     */
    private $fonctionReferentiel;





    /**
     * @param FonctionReferentiel $fonctionReferentiel
     * @return self
     */
    public function setFonctionReferentiel( FonctionReferentiel $fonctionReferentiel = null )
    {
        $this->fonctionReferentiel = $fonctionReferentiel;
        return $this;
    }



    /**
     * @return FonctionReferentiel
     */
    public function getFonctionReferentiel()
    {
        return $this->fonctionReferentiel;
    }
}