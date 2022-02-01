<?php

namespace Application\Service\Traits;

use Application\Service\DomaineFonctionnelService;

/**
 * Description of DomaineFonctionnelServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DomaineFonctionnelServiceAwareTrait
{
    protected ?DomaineFonctionnelService $serviceDomaineFonctionnel;



    /**
     * @param DomaineFonctionnelService|null $serviceDomaineFonctionnel
     *
     * @return self
     */
    public function setServiceDomaineFonctionnel( ?DomaineFonctionnelService $serviceDomaineFonctionnel )
    {
        $this->serviceDomaineFonctionnel = $serviceDomaineFonctionnel;

        return $this;
    }



    public function getServiceDomaineFonctionnel(): ?DomaineFonctionnelService
    {
        if (!$this->serviceDomaineFonctionnel){
            $this->serviceDomaineFonctionnel = \Application::$container->get(DomaineFonctionnelService::class);
        }

        return $this->serviceDomaineFonctionnel;
    }
}