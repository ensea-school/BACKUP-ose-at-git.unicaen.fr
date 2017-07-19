<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeAgrementStatut;
use RuntimeException;

/**
 * Description of TypeAgrementStatutAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeAgrementStatutAwareInterface
{
    /**
     * @param TypeAgrementStatut $serviceTypeAgrementStatut
     * @return self
     */
    public function setServiceTypeAgrementStatut( TypeAgrementStatut $serviceTypeAgrementStatut );



    /**
     * @return TypeAgrementStatutAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeAgrementStatut();
}