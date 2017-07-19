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
    /**
     * @var AffectationRecherche
     */
    private $affectationRecherche;





    /**
     * @param AffectationRecherche $affectationRecherche
     * @return self
     */
    public function setAffectationRecherche( AffectationRecherche $affectationRecherche = null )
    {
        $this->affectationRecherche = $affectationRecherche;
        return $this;
    }



    /**
     * @return AffectationRecherche
     */
    public function getAffectationRecherche()
    {
        return $this->affectationRecherche;
    }
}