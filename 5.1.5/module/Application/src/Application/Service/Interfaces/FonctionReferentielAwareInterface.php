<?php

namespace Application\Service\Interfaces;

use Application\Service\FonctionReferentiel;
use RuntimeException;

/**
 * Description of FonctionReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FonctionReferentielAwareInterface
{
    /**
     * @param FonctionReferentiel $serviceFonctionReferentiel
     * @return self
     */
    public function setServiceFonctionReferentiel( FonctionReferentiel $serviceFonctionReferentiel );



    /**
     * @return FonctionReferentielAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFonctionReferentiel();
}