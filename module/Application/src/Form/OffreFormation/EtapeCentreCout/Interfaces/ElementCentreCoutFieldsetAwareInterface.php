<?php

namespace Application\Form\OffreFormation\EtapeCentreCout\Interfaces;

use Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset;

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