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
    /**
     * @var ElementModulateur
     */
    private $elementModulateur;





    /**
     * @param ElementModulateur $elementModulateur
     * @return self
     */
    public function setElementModulateur( ElementModulateur $elementModulateur = null )
    {
        $this->elementModulateur = $elementModulateur;
        return $this;
    }



    /**
     * @return ElementModulateur
     */
    public function getElementModulateur()
    {
        return $this->elementModulateur;
    }
}