<?php

namespace Workflow\Service;

trait EtatLieuxIntervenantServiceAwareTrait
{
    protected ?EtatLieuxIntervenantService $serviceEtatLieuxIntervenant = null;



    public function setServiceEtatLieuxIntervenant(?EtatLieuxIntervenantService $serviceEtatLieuxIntervenant): self
    {
        $this->serviceEtatLieuxIntervenant = $serviceEtatLieuxIntervenant;

        return $this;
    }



    public function getServiceEtatLieuxIntervenant(): ?EtatLieuxIntervenantService
    {
        if (empty($this->serviceEtatLieuxIntervenant)) {
            $this->serviceEtatLieuxIntervenant = \Unicaen\Framework\Application\Application::getInstance()->container()->get(EtatLieuxIntervenantService::class);
        }

        return $this->serviceEtatLieuxIntervenant;
    }
}
