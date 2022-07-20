<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\EtapeModulateursSaisie;

/**
 * Description of EtapeModulateursSaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface EtapeModulateursSaisieAwareInterface
{
    /**
     * @param EtapeModulateursSaisie|null $formOffreFormationEtapeModulateursSaisie
     *
     * @return self
     */
    public function setFormOffreFormationEtapeModulateursSaisie( ?EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie );



    public function getFormOffreFormationEtapeModulateursSaisie(): ?EtapeModulateursSaisie;
}