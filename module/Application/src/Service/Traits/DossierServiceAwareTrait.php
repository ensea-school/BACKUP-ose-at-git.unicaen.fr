<?php

namespace Application\Service\Traits;

use Application\Service\DossierService;

/**
 * Description of DossierServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierServiceAwareTrait
{
    protected ?DossierService $serviceDossier = null;



    /**
     * @param DossierService $serviceDossier
     *
     * @return self
     */
    public function setServiceDossier( DossierService $serviceDossier )
    {
        $this->serviceDossier = $serviceDossier;

        return $this;
    }



    public function getServiceDossier(): ?DossierService
    {
        if (empty($this->serviceDossier)){
            $this->serviceDossier = \Application::$container->get(DossierService::class);
        }

        return $this->serviceDossier;
    }
}