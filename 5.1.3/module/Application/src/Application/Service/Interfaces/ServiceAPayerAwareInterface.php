<?php

namespace Application\Service\Interfaces;

use Application\Service\ServiceAPayer;
use RuntimeException;

/**
 * Description of ServiceAPayerAwareInterface
 *
 * @author UnicaenCode
 */
interface ServiceAPayerAwareInterface
{
    /**
     * @param ServiceAPayer $serviceServiceAPayer
     * @return self
     */
    public function setServiceServiceAPayer( ServiceAPayer $serviceServiceAPayer );



    /**
     * @return ServiceAPayerAwareInterface
     * @throws RuntimeException
     */
    public function getServiceServiceAPayer();
}