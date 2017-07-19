<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleServiceReferentiel;
use RuntimeException;

/**
 * Description of FormuleServiceReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleServiceReferentielAwareInterface
{
    /**
     * @param FormuleServiceReferentiel $serviceFormuleServiceReferentiel
     * @return self
     */
    public function setServiceFormuleServiceReferentiel( FormuleServiceReferentiel $serviceFormuleServiceReferentiel );



    /**
     * @return FormuleServiceReferentielAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleServiceReferentiel();
}