<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleResultat;
use RuntimeException;

/**
 * Description of FormuleResultatAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatAwareInterface
{
    /**
     * @param FormuleResultat $serviceFormuleResultat
     * @return self
     */
    public function setServiceFormuleResultat( FormuleResultat $serviceFormuleResultat );



    /**
     * @return FormuleResultatAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleResultat();
}