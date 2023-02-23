<?php

namespace OffreFormation\Form\EtapeCentreCout\Interfaces;

use OffreFormation\Form\EtapeCentreCout\ElementCentreCoutFieldset;

/**
 * Description of ElementCentreCoutFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementCentreCoutFieldsetAwareInterface
{
    /**
     * @param ElementCentreCoutFieldset|null $formOffreFormationEtapeCentreCoutElementCentreCoutFieldset
     *
     * @return self
     */
    public function setFormOffreFormationEtapeCentreCoutElementCentreCoutFieldset( ?ElementCentreCoutFieldset $formOffreFormationEtapeCentreCoutElementCentreCoutFieldset );



    public function getFormOffreFormationEtapeCentreCoutElementCentreCoutFieldset(): ?ElementCentreCoutFieldset;
}