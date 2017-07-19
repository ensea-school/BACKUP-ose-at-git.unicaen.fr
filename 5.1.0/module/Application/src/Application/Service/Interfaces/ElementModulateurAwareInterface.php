<?php

namespace Application\Service\Interfaces;

use Application\Service\ElementModulateur;
use RuntimeException;

/**
 * Description of ElementModulateurAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementModulateurAwareInterface
{
    /**
     * @param ElementModulateur $serviceElementModulateur
     * @return self
     */
    public function setServiceElementModulateur( ElementModulateur $serviceElementModulateur );



    /**
     * @return ElementModulateurAwareInterface
     * @throws RuntimeException
     */
    public function getServiceElementModulateur();
}