<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\EtapeSaisie;
use RuntimeException;

/**
 * Description of EtapeSaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface EtapeSaisieAwareInterface
{
    /**
     * @param EtapeSaisie $formOffreFormationEtapeSaisie
     * @return self
     */
    public function setFormOffreFormationEtapeSaisie( EtapeSaisie $formOffreFormationEtapeSaisie );



    /**
     * @return EtapeSaisieAwareInterface
     * @throws RuntimeException
     */
    public function getFormOffreFormationEtapeSaisie();
}