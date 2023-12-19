<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\DomaineFonctionnelService;

/**
 * Description of DomaineFonctionnelServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DomaineFonctionnelServiceAwareTrait
{
    protected ?DomaineFonctionnelService $serviceDomaineFonctionnel = null;



    /**
     * @param DomaineFonctionnelService $serviceDomaineFonctionnel
     *
     * @return self
     */
    public function setServiceDomaineFonctionnel(?DomaineFonctionnelService $serviceDomaineFonctionnel)
    {
        $this->serviceDomaineFonctionnel = $serviceDomaineFonctionnel;

        return $this;
    }



    public function getServiceDomaineFonctionnel(): ?DomaineFonctionnelService
    {
        if (empty($this->serviceDomaineFonctionnel)) {
            $this->serviceDomaineFonctionnel = \OseAdmin::instance()->container()->get(DomaineFonctionnelService::class);
        }

        return $this->serviceDomaineFonctionnel;
    }
}