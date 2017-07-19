<?php

namespace Application\Service\Interfaces;

use Application\Service\Civilite;
use RuntimeException;

/**
 * Description of CiviliteAwareInterface
 *
 * @author UnicaenCode
 */
interface CiviliteAwareInterface
{
    /**
     * @param Civilite $serviceCivilite
     * @return self
     */
    public function setServiceCivilite( Civilite $serviceCivilite );



    /**
     * @return CiviliteAwareInterface
     * @throws RuntimeException
     */
    public function getServiceCivilite();
}