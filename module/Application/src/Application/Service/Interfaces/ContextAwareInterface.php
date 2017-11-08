<?php

namespace Application\Service\Interfaces;

use Application\Service\ContextService;
use RuntimeException;

/**
 * Description of ContextAwareInterface
 *
 * @author UnicaenCode
 */
interface ContextAwareInterface
{
    /**
     * @param ContextService $serviceContext
     *
     * @return self
     */
    public function setServiceContext(ContextService $serviceContext );



    /**
     * @return ContextAwareInterface
     * @throws RuntimeException
     */
    public function getServiceContext();
}