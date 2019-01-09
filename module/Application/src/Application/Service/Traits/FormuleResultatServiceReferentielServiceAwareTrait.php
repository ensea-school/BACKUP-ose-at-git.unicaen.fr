<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatServiceReferentielService;

/**
 * Description of FormuleResultatServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceReferentielServiceAwareTrait
{
    /**
     * @var FormuleResultatServiceReferentielService
     */
    private $serviceFormuleResultatServiceReferentiel;



    /**
     * @param FormuleResultatServiceReferentielService $serviceFormuleResultatServiceReferentiel
     *
     * @return self
     */
    public function setServiceFormuleResultatServiceReferentiel(FormuleResultatServiceReferentielService $serviceFormuleResultatServiceReferentiel)
    {
        $this->serviceFormuleResultatServiceReferentiel = $serviceFormuleResultatServiceReferentiel;

        return $this;
    }



    /**
     * @return FormuleResultatServiceReferentielService
     */
    public function getServiceFormuleResultatServiceReferentiel()
    {
        if (empty($this->serviceFormuleResultatServiceReferentiel)) {
            $this->serviceFormuleResultatServiceReferentiel = \Application::$container->get(FormuleResultatServiceReferentielService::class);
        }

        return $this->serviceFormuleResultatServiceReferentiel;
    }
}