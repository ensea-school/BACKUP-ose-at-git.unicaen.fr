<?php

namespace Application\Service\Interfaces;

use Application\Service\Pays;
use RuntimeException;

/**
 * Description of PaysAwareInterface
 *
 * @author UnicaenCode
 */
interface PaysAwareInterface
{
    /**
     * @param Pays $servicePays
     * @return self
     */
    public function setServicePays( Pays $servicePays );



    /**
     * @return PaysAwareInterface
     * @throws RuntimeException
     */
    public function getServicePays();
}