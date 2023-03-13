<?php

namespace OffreFormation\Form\EtapeCentreCout\Interfaces;

use OffreFormation\Form\EtapeCentreCout\EtapeCentreCoutForm;

/**
 * Description of EtapeCentreCoutFormAwareInterface
 *
 * @author UnicaenCode
 */
interface EtapeCentreCoutFormAwareInterface
{
    /**
     * @param EtapeCentreCoutForm|null $formOffreFormationEtapeCentreCoutEtapeCentreCout
     *
     * @return self
     */
    public function setFormOffreFormationEtapeCentreCoutEtapeCentreCout( ?EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout );



    public function getFormOffreFormationEtapeCentreCoutEtapeCentreCout(): ?EtapeCentreCoutForm;
}