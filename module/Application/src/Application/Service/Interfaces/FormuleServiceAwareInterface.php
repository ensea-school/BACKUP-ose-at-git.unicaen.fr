<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleService;
use RuntimeException;

/**
 * Description of FormuleServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleServiceAwareInterface
{
    /**
     * @param FormuleService $serviceFormule
     * @return self
     */
    public function setServiceFormule( FormuleService $serviceFormule );



    /**
     * @return FormuleServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormule();
}