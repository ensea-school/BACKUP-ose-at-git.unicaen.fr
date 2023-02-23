<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\ElementModulateur;

/**
 * Description of ElementModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurAwareTrait
{
    protected ?ElementModulateur $elementModulateur = null;



    /**
     * @param ElementModulateur $elementModulateur
     *
     * @return self
     */
    public function setElementModulateur( ?ElementModulateur $elementModulateur )
    {
        $this->elementModulateur = $elementModulateur;

        return $this;
    }



    public function getElementModulateur(): ?ElementModulateur
    {
        return $this->elementModulateur;
    }
}