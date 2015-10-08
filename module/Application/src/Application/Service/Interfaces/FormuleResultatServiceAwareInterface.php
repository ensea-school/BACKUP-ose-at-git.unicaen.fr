<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleResultatService;
use RuntimeException;

/**
 * Description of FormuleResultatServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatServiceAwareInterface
{
    /**
     * @param FormuleResultatService $serviceFormuleResultat
     * @return self
     */
    public function setServiceFormuleResultat( FormuleResultatService $serviceFormuleResultat );



    /**
     * @return FormuleResultatServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleResultat();
}