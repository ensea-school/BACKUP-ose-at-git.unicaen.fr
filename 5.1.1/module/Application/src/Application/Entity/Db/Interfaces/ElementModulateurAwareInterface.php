<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\ElementModulateur;

/**
 * Description of ElementModulateurAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementModulateurAwareInterface
{
    /**
     * @param ElementModulateur $elementModulateur
     * @return self
     */
    public function setElementModulateur( ElementModulateur $elementModulateur = null );



    /**
     * @return ElementModulateur
     */
    public function getElementModulateur();
}