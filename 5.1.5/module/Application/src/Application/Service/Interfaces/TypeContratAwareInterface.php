<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeContrat;
use RuntimeException;

/**
 * Description of TypeContratAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeContratAwareInterface
{
    /**
     * @param TypeContrat $serviceTypeContrat
     * @return self
     */
    public function setServiceTypeContrat( TypeContrat $serviceTypeContrat );



    /**
     * @return TypeContratAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeContrat();
}