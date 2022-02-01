<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ElementModulateur;

/**
 * Description of ElementModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurAwareTrait
{
    protected ?ElementModulateur $elementModulateur;



    /**
     * @param ElementModulateur|null $elementModulateur
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