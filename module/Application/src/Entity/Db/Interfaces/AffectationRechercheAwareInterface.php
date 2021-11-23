<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\AffectationRecherche;

/**
 * Description of AffectationRechercheAwareInterface
 *
 * @author UnicaenCode
 */
interface AffectationRechercheAwareInterface
{
    /**
     * @param AffectationRecherche $affectationRecherche
     * @return self
     */
    public function setAffectationRecherche( AffectationRecherche $affectationRecherche = null );



    /**
     * @return AffectationRecherche
     */
    public function getAffectationRecherche();
}