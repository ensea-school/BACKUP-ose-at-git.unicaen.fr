<?php

namespace Application\Service\Interfaces;

use Application\Service\ServiceService;
use RuntimeException;

/**
 * Description of ServiceServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface ServiceServiceAwareInterface
{
    /**
     * @param ServiceService $serviceService
     * @return self
     */
    public function setServiceService( ServiceService $serviceService );



    /**
     * @return ServiceServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceService();
}