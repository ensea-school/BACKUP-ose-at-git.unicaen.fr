<?php

namespace Dossier\Service\Traits;

use Dossier\Service\DossierService;

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
    public function setServiceDossier(?DossierService $serviceDossier)
    {
        $this->serviceDossier = $serviceDossier;

        return $this;
    }



    public function getServiceDossier(): ?DossierService
    {
        if (empty($this->serviceDossier)) {
            $this->serviceDossier = \AppAdmin::container()->get(DossierService::class);
        }

        return $this->serviceDossier;
    }
}