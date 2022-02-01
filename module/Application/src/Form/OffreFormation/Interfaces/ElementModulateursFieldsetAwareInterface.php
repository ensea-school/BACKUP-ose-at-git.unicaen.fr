<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\ElementModulateursFieldset;

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
    public function setFormOffreFormationElementModulateursFieldset( ElementModulateursFieldset $formOffreFormationElementModulateursFieldset );



    public function getFormOffreFormationElementModulateursFieldset(): ?ElementModulateursFieldset;
}