<?php

namespace Application\Service\Traits;

use Application\Service\WfEtapeDepService;

/**
 * Description of WfEtapeDepServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait WfEtapeDepServiceAwareTrait
{
    protected ?WfEtapeDepService $serviceWfEtapeDep;



    /**
     * @param WfEtapeDepService|null $serviceWfEtapeDep
     *
     * @return self
     */
    public function setServiceWfEtapeDep( ?WfEtapeDepService $serviceWfEtapeDep )
    {
        $this->serviceWfEtapeDep = $serviceWfEtapeDep;

        return $this;
    }



    public function getServiceWfEtapeDep(): ?WfEtapeDepService
    {
        if (!$this->serviceWfEtapeDep){
            $this->serviceWfEtapeDep = \Application::$container->get(WfEtapeDepService::class);
        }

        return $this->serviceWfEtapeDep;
    }
}