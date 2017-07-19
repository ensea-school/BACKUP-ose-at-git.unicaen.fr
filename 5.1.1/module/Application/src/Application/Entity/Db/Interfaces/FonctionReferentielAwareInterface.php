<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FonctionReferentiel;

/**
 * Description of FonctionReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FonctionReferentielAwareInterface
{
    /**
     * @param FonctionReferentiel $fonctionReferentiel
     * @return self
     */
    public function setFonctionReferentiel( FonctionReferentiel $fonctionReferentiel = null );



    /**
     * @return FonctionReferentiel
     */
    public function getFonctionReferentiel();
}