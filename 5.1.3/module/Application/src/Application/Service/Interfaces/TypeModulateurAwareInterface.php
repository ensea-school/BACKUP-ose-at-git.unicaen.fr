<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeModulateur;
use RuntimeException;

/**
 * Description of TypeModulateurAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeModulateurAwareInterface
{
    /**
     * @param TypeModulateur $serviceTypeModulateur
     * @return self
     */
    public function setServiceTypeModulateur( TypeModulateur $serviceTypeModulateur );



    /**
     * @return TypeModulateurAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeModulateur();
}