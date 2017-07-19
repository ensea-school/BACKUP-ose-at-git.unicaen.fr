<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleResultatServiceService;
use RuntimeException;

/**
 * Description of FormuleResultatServiceServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatServiceServiceAwareInterface
{
    /**
     * @param FormuleResultatServiceService $serviceFormuleResultatService
     * @return self
     */
    public function setServiceFormuleResultatService( FormuleResultatServiceService $serviceFormuleResultatService );



    /**
     * @return FormuleResultatServiceServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleResultatService();
}