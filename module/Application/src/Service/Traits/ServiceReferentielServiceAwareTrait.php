<?php

namespace Application\Service\Traits;

use Application\Service\ServiceReferentielService;

/**
 * Description of ServiceReferentielServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceReferentielServiceAwareTrait
{
    protected ?ServiceReferentielService $serviceServiceReferentiel;



    /**
     * @param ServiceReferentielService|null $serviceServiceReferentiel
     *
     * @return self
     */
    public function setServiceServiceReferentiel( ?ServiceReferentielService $serviceServiceReferentiel )
    {
        $this->serviceServiceReferentiel = $serviceServiceReferentiel;

        return $this;
    }



    public function getServiceServiceReferentiel(): ?ServiceReferentielService
    {
        if (!$this->serviceServiceReferentiel){
            $this->serviceServiceReferentiel = \Application::$container->get(ServiceReferentielService::class);
        }

        return $this->serviceServiceReferentiel;
    }
}