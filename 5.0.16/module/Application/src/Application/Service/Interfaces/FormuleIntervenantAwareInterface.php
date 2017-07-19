<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleIntervenant;
use RuntimeException;

/**
 * Description of FormuleIntervenantAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleIntervenantAwareInterface
{
    /**
     * @param FormuleIntervenant $serviceFormuleIntervenant
     * @return self
     */
    public function setServiceFormuleIntervenant( FormuleIntervenant $serviceFormuleIntervenant );



    /**
     * @return FormuleIntervenantAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleIntervenant();
}