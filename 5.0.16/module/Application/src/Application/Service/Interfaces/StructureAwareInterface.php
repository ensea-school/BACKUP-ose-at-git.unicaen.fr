<?php

namespace Application\Service\Interfaces;

use Application\Service\Structure;
use RuntimeException;

/**
 * Description of StructureAwareInterface
 *
 * @author UnicaenCode
 */
interface StructureAwareInterface
{
    /**
     * @param Structure $serviceStructure
     * @return self
     */
    public function setServiceStructure( Structure $serviceStructure );



    /**
     * @return StructureAwareInterface
     * @throws RuntimeException
     */
    public function getServiceStructure();
}