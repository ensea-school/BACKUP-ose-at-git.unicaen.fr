<?php

namespace Intervenant\Entity\Db;

/**
 * Description of AffectationRechercheAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationRechercheAwareTrait
{
    protected ?AffectationRecherche $affectationRecherche = null;



    /**
     * @param AffectationRecherche $affectationRecherche
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