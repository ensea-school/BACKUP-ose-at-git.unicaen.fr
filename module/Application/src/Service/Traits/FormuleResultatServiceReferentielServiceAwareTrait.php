<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatServiceReferentielService;

/**
 * Description of FormuleResultatServiceReferentielServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceReferentielServiceAwareTrait
{
    protected ?FormuleResultatServiceReferentielService $serviceFormuleResultatServiceReferentiel;



    /**
     * @param FormuleResultatServiceReferentielService|null $serviceFormuleResultatServiceReferentiel
     *
     * @return self
     */
    public function setServiceFormuleResultatServiceReferentiel( ?FormuleResultatServiceReferentielService $serviceFormuleResultatServiceReferentiel )
    {
        $this->serviceFormuleResultatServiceReferentiel = $serviceFormuleResultatServiceReferentiel;

        return $this;
    }



    public function getServiceFormuleResultatServiceReferentiel(): ?FormuleResultatServiceReferentielService
    {
        if (!$this->serviceFormuleResultatServiceReferentiel){
            $this->serviceFormuleResultatServiceReferentiel = \Application::$container->get('FormElementManager')->get(FormuleResultatServiceReferentielService::class);
        }

        return $this->serviceFormuleResultatServiceReferentiel;
    }
}