<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeIntervenant;
use RuntimeException;

/**
 * Description of TypeIntervenantAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeIntervenantAwareInterface
{
    /**
     * @param TypeIntervenant $serviceTypeIntervenant
     * @return self
     */
    public function setServiceTypeIntervenant( TypeIntervenant $serviceTypeIntervenant );



    /**
     * @return TypeIntervenantAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeIntervenant();
}