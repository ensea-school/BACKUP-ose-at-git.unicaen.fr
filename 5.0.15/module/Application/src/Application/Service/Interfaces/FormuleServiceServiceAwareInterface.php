<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleServiceService;
use RuntimeException;

/**
 * Description of FormuleServiceServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleServiceServiceAwareInterface
{
    /**
     * @param FormuleServiceService $serviceFormuleService
     * @return self
     */
    public function setServiceFormuleService( FormuleServiceService $serviceFormuleService );



    /**
     * @return FormuleServiceServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleService();
}