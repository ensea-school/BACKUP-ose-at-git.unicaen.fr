<?php

namespace Application\Service\Interfaces;

use Application\Service\AgrementService;
use RuntimeException;

/**
 * Description of AgrementAwareInterface
 *
 * @author UnicaenCode
 */
interface AgrementAwareInterface
{
    /**
     * @param AgrementService $serviceAgrement
     *
     * @return self
     */
    public function setServiceAgrement(AgrementService $serviceAgrement );



    /**
     * @return AgrementAwareInterface
     * @throws RuntimeException
     */
    public function getServiceAgrement();
}