<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatServiceReferentiel;

/**
 * Description of FormuleResultatServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceReferentielAwareTrait
{
    /**
     * @var FormuleResultatServiceReferentiel
     */
    private $serviceFormuleResultatServiceReferentiel;



    /**
     * @param FormuleResultatServiceReferentiel $serviceFormuleResultatServiceReferentiel
     *
     * @return self
     */
    public function setServiceFormuleResultatServiceReferentiel(FormuleResultatServiceReferentiel $serviceFormuleResultatServiceReferentiel)
    {
        $this->serviceFormuleResultatServiceReferentiel = $serviceFormuleResultatServiceReferentiel;

        return $this;
    }



    /**
     * @return FormuleResultatServiceReferentiel
     */
    public function getServiceFormuleResultatServiceReferentiel()
    {
        if (empty($this->serviceFormuleResultatServiceReferentiel)) {
            $this->serviceFormuleResultatServiceReferentiel = \Application::$container->get('ApplicationFormuleResultatServiceReferentiel');
        }

        return $this->serviceFormuleResultatServiceReferentiel;
    }
}