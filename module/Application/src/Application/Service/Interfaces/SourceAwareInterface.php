<?php

namespace Application\Service\Interfaces;

use Application\Service\SourceService;
use RuntimeException;

/**
 * Description of SourceAwareInterface
 *
 * @author UnicaenCode
 */
interface SourceAwareInterface
{
    /**
     * @param SourceService $serviceSource
     *
     * @return self
     */
    public function setServiceSource(SourceService $serviceSource );



    /**
     * @return SourceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceSource();
}