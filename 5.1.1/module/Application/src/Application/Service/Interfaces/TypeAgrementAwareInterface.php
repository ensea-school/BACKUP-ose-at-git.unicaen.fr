<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeAgrement;
use RuntimeException;

/**
 * Description of TypeAgrementAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeAgrementAwareInterface
{
    /**
     * @param TypeAgrement $serviceTypeAgrement
     * @return self
     */
    public function setServiceTypeAgrement( TypeAgrement $serviceTypeAgrement );



    /**
     * @return TypeAgrementAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeAgrement();
}