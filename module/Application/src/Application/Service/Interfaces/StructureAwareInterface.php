<?php

namespace Application\Service\Interfaces;

use Application\Service\StructureService;
use RuntimeException;

/**
 * Description of StructureAwareInterface
 *
 * @author UnicaenCode
 */
interface StructureAwareInterface
{
    /**
     * @param StructureService $serviceStructure
     *
     * @return self
     */
    public function setServiceStructure(StructureService $serviceStructure );



    /**
     * @return StructureAwareInterface
     * @throws RuntimeException
     */
    public function getServiceStructure();
}