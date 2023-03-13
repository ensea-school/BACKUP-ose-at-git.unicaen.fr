<?php

namespace OffreFormation\Form\Interfaces;

use OffreFormation\Form\ElementModulateursFieldset;

/**
 * Description of ElementModulateursFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementModulateursFieldsetAwareInterface
{
    /**
     * @param ElementModulateursFieldset|null $formOffreFormationElementModulateursFieldset
     *
     * @return self
     */
    public function setFormOffreFormationElementModulateursFieldset( ?ElementModulateursFieldset $formOffreFormationElementModulateursFieldset );



    public function getFormOffreFormationElementModulateursFieldset(): ?ElementModulateursFieldset;
}