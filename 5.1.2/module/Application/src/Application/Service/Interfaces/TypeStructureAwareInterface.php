<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeStructure;
use RuntimeException;

/**
 * Description of TypeStructureAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeStructureAwareInterface
{
    /**
     * @param TypeStructure $serviceTypeStructure
     * @return self
     */
    public function setServiceTypeStructure( TypeStructure $serviceTypeStructure );



    /**
     * @return TypeStructureAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeStructure();
}