<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\EtapeSaisie;

/**
 * Description of EtapeSaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface EtapeSaisieAwareInterface
{
    /**
     * @param EtapeSaisie|null $formOffreFormationEtapeSaisie
     *
     * @return self
     */
    public function setFormOffreFormationEtapeSaisie( ?EtapeSaisie $formOffreFormationEtapeSaisie );



    public function getFormOffreFormationEtapeSaisie(): ?EtapeSaisie;
}