<?php

namespace Application\Service\Interfaces;

use Application\Service\Agrement;
use RuntimeException;

/**
 * Description of AgrementAwareInterface
 *
 * @author UnicaenCode
 */
interface AgrementAwareInterface
{
    /**
     * @param Agrement $serviceAgrement
     * @return self
     */
    public function setServiceAgrement( Agrement $serviceAgrement );



    /**
     * @return AgrementAwareInterface
     * @throws RuntimeException
     */
    public function getServiceAgrement();
}