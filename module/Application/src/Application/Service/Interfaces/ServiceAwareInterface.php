<?php

namespace Application\Service\Interfaces;

use Application\Service\ServiceService;
use RuntimeException;

/**
 * Description of ServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface ServiceAwareInterface
{
    /**
     * @param ServiceService $service
     *
*@return self
     */
    public function setService(ServiceService $service );



    /**
     * @return ServiceAwareInterface
     * @throws RuntimeException
     */
    public function getService();
}