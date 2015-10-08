<?php

namespace Application\Service\Interfaces;

use Application\Service\Service;
use RuntimeException;

/**
 * Description of ServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface ServiceAwareInterface
{
    /**
     * @param Service $service
     * @return self
     */
    public function setService( Service $service );



    /**
     * @return ServiceAwareInterface
     * @throws RuntimeException
     */
    public function getService();
}