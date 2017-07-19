<?php

namespace Application\Service\Interfaces;

use Application\Service\Modulateur;
use RuntimeException;

/**
 * Description of ModulateurAwareInterface
 *
 * @author UnicaenCode
 */
interface ModulateurAwareInterface
{
    /**
     * @param Modulateur $serviceModulateur
     * @return self
     */
    public function setServiceModulateur( Modulateur $serviceModulateur );



    /**
     * @return ModulateurAwareInterface
     * @throws RuntimeException
     */
    public function getServiceModulateur();
}