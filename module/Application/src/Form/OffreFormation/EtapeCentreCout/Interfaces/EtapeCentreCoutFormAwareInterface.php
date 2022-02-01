<?php

namespace Application\Form\OffreFormation\EtapeCentreCout\Interfaces;

use Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm;

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
    public function setFormOffreFormationEtapeCentreCoutEtapeCentreCout( EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout );



    public function getFormOffreFormationEtapeCentreCoutEtapeCentreCout(): ?EtapeCentreCoutForm;
}