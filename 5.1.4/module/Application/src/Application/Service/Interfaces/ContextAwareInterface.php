<?php

namespace Application\Service\Interfaces;

use Application\Service\Context;
use RuntimeException;

/**
 * Description of ContextAwareInterface
 *
 * @author UnicaenCode
 */
interface ContextAwareInterface
{
    /**
     * @param Context $serviceContext
     * @return self
     */
    public function setServiceContext( Context $serviceContext );



    /**
     * @return ContextAwareInterface
     * @throws RuntimeException
     */
    public function getServiceContext();
}