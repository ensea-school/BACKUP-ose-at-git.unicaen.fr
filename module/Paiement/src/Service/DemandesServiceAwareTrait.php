<?php

namespace Paiement\Service;


/**
 * Description of DemandesServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DemandesServiceAwareTrait
{
    protected ?DemandesService $serviceDemandes = null;



    /**
     * @param DemandesService $serviceDemandes
     *
     * @return self
     */
    public function setServiceDemandes(?DemandesService $serviceDemandes)
    {
        $this->serviceDemandes = $serviceDemandes;

        return $this;
    }



    public function getServiceDemandes(): ?DemandesService
    {
        if (empty($this->serviceDemandes)) {
            $this->serviceDemandes = \Framework\Application\Application::getInstance()->container()->get(DemandesService::class);
        }

        return $this->serviceDemandes;
    }
}