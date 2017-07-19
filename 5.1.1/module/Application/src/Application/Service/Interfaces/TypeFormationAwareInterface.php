<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeFormation;
use RuntimeException;

/**
 * Description of TypeFormationAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeFormationAwareInterface
{
    /**
     * @param TypeFormation $serviceTypeFormation
     * @return self
     */
    public function setServiceTypeFormation( TypeFormation $serviceTypeFormation );



    /**
     * @return TypeFormationAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeFormation();
}