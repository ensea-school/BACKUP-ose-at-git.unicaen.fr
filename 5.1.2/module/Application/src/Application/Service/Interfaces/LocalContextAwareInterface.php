<?php

namespace Application\Service\Interfaces;

use Application\Service\LocalContext;
use RuntimeException;

/**
 * Description of LocalContextAwareInterface
 *
 * @author UnicaenCode
 */
interface LocalContextAwareInterface
{
    /**
     * @param LocalContext $serviceLocalContext
     * @return self
     */
    public function setServiceLocalContext( LocalContext $serviceLocalContext );



    /**
     * @return LocalContextAwareInterface
     * @throws RuntimeException
     */
    public function getServiceLocalContext();
}