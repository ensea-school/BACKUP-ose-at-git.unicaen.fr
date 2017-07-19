<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleResultatServiceReferentiel;
use RuntimeException;

/**
 * Description of FormuleResultatServiceReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatServiceReferentielAwareInterface
{
    /**
     * @param FormuleResultatServiceReferentiel $serviceFormuleResultatServiceReferentiel
     * @return self
     */
    public function setServiceFormuleResultatServiceReferentiel( FormuleResultatServiceReferentiel $serviceFormuleResultatServiceReferentiel );



    /**
     * @return FormuleResultatServiceReferentielAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleResultatServiceReferentiel();
}