<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\AffectationRecherche;

/**
 * Description of AffectationRechercheAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationRechercheAwareTrait
{
    protected ?AffectationRecherche $affectationRecherche;



    /**
     * @param AffectationRecherche|null $affectationRecherche
     *
     * @return self
     */
    public function setAffectationRecherche( ?AffectationRecherche $affectationRecherche )
    {
        $this->affectationRecherche = $affectationRecherche;

        return $this;
    }



    public function getAffectationRecherche(): ?AffectationRecherche
    {
        return $this->affectationRecherche;
    }
}