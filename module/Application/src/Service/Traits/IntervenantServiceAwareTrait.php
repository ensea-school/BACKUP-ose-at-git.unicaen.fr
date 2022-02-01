<?php

namespace Application\Service\Traits;

use Application\Service\IntervenantService;

/**
 * Description of IntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantServiceAwareTrait
{
    protected ?IntervenantService $serviceIntervenant;



    /**
     * @param IntervenantService|null $serviceIntervenant
     *
     * @return self
     */
    public function setServiceIntervenant( ?IntervenantService $serviceIntervenant )
    {
        $this->serviceIntervenant = $serviceIntervenant;

        return $this;
    }



    public function getServiceIntervenant(): ?IntervenantService
    {
        if (!$this->serviceIntervenant){
            $this->serviceIntervenant = \Application::$container->get(IntervenantService::class);
        }

        return $this->serviceIntervenant;
    }
}