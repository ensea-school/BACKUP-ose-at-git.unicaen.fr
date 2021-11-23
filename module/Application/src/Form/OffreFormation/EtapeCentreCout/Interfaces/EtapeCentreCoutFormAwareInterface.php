<?php

namespace Application\Form\OffreFormation\EtapeCentreCout\Interfaces;

use Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm;
use RuntimeException;

/**
 * Description of EtapeCentreCoutFormAwareInterface
 *
 * @author UnicaenCode
 */
interface EtapeCentreCoutFormAwareInterface
{
    /**
     * @param EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout
     * @return self
     */
    public function setFormOffreFormationEtapeCentreCoutEtapeCentreCout( EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout );



    /**
     * @return EtapeCentreCoutFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormOffreFormationEtapeCentreCoutEtapeCentreCout();
}