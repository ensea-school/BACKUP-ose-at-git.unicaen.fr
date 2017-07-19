<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleServiceModifie;
use RuntimeException;

/**
 * Description of FormuleServiceModifieAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleServiceModifieAwareInterface
{
    /**
     * @param FormuleServiceModifie $serviceFormuleServiceModifie
     * @return self
     */
    public function setServiceFormuleServiceModifie( FormuleServiceModifie $serviceFormuleServiceModifie );



    /**
     * @return FormuleServiceModifieAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleServiceModifie();
}