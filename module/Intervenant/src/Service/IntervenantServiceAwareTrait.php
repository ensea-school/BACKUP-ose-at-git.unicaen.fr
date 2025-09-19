<?php

namespace Intervenant\Service;

/**
 * Description of IntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantServiceAwareTrait
{
    protected ?IntervenantService $serviceIntervenant = null;



    /**
     * @param IntervenantService $serviceIntervenant
     *
     * @return self
     */
    public function setServiceIntervenant(?IntervenantService $serviceIntervenant)
    {
        $this->serviceIntervenant = $serviceIntervenant;

        return $this;
    }



    public function getServiceIntervenant(): ?IntervenantService
    {
        if (empty($this->serviceIntervenant)) {
            $this->serviceIntervenant = \Framework\Application\Application::getInstance()->container()->get(IntervenantService::class);
        }

        return $this->serviceIntervenant;
    }
}