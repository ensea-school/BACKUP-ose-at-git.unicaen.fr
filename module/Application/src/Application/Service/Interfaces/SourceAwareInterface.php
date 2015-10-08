<?php

namespace Application\Service\Interfaces;

use Application\Service\Source;
use RuntimeException;

/**
 * Description of SourceAwareInterface
 *
 * @author UnicaenCode
 */
interface SourceAwareInterface
{
    /**
     * @param Source $serviceSource
     * @return self
     */
    public function setServiceSource( Source $serviceSource );



    /**
     * @return SourceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceSource();
}