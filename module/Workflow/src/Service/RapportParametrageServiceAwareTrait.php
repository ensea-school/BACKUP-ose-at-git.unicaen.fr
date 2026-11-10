<?php

namespace Workflow\Service;

trait RapportParametrageServiceAwareTrait
{
    protected ?RapportParametrageService $serviceRapportParametrage = null;



    public function setServiceRapportParametrage(?RapportParametrageService $serviceRapportParametrage): self
    {
        $this->serviceRapportParametrage = $serviceRapportParametrage;

        return $this;
    }



    public function getServiceRapportParametrage(): ?RapportParametrageService
    {
        if (empty($this->serviceRapportParametrage)) {
            $this->serviceRapportParametrage = \Unicaen\Framework\Application\Application::getInstance()->container()->get(RapportParametrageService::class);
        }

        return $this->serviceRapportParametrage;
    }
}
