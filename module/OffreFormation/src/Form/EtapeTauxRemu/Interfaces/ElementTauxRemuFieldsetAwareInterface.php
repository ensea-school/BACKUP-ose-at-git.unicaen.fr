<?php

namespace OffreFormation\Form\EtapeTauxRemu\Interfaces;

use OffreFormation\Form\EtapeTauxRemu\ElementTauxRemuFieldset;

/**
 * Description of ElementTauxRemuFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementTauxRemuFieldsetAwareInterface
{
    /**
     * @param ElementTauxRemuFieldset|null $formOffreFormationEtapeTauxRemuElementTauxRemuFieldset
     *
     * @return self
     */
    public function setEtapeTauxRemuElementTauxRemuFieldset( ?ElementTauxRemuFieldset $formOffreFormationEtapeTauxRemuElementTauxRemuFieldset );



    public function getEtapeTauxRemuElementTauxRemuFieldset(): ?ElementTauxRemuFieldset;
}