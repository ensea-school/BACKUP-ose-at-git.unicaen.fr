<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeHeures;
use RuntimeException;

/**
 * Description of TypeHeuresAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeHeuresAwareInterface
{
    /**
     * @param TypeHeures $serviceTypeHeures
     * @return self
     */
    public function setServiceTypeHeures( TypeHeures $serviceTypeHeures );



    /**
     * @return TypeHeuresAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeHeures();
}