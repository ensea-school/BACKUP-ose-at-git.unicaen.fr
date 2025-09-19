<?php

namespace Contrat\Service;

/**
 * Description of ContratServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratServiceAwareTrait
{
    protected ?ContratService $serviceContrat = null;



    /**
     * @param ContratService $serviceContrat
     *
     * @return self
     */
    public function setServiceContrat(?ContratService $serviceContrat)
    {
        $this->serviceContrat = $serviceContrat;

        return $this;
    }



    public function getServiceContrat(): ?ContratService
    {
        if (empty($this->serviceContrat)) {
            $this->serviceContrat = \Framework\Application\Application::getInstance()->container()->get(ContratService::class);
        }

        return $this->serviceContrat;
    }
}