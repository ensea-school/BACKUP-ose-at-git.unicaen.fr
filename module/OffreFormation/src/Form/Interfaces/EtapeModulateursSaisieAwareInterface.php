<?php

namespace OffreFormation\Form\Interfaces;

use OffreFormation\Form\EtapeModulateursSaisie;

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