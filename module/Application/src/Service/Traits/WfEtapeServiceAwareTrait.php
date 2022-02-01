<?php

namespace Application\Service\Traits;

use Application\Service\WfEtapeService;

/**
 * Description of WfEtapeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait WfEtapeServiceAwareTrait
{
    protected ?WfEtapeService $serviceWfEtape;



    /**
     * @param WfEtapeService|null $serviceWfEtape
     *
     * @return self
     */
    public function setServiceWfEtape( ?WfEtapeService $serviceWfEtape )
    {
        $this->serviceWfEtape = $serviceWfEtape;

        return $this;
    }



    public function getServiceWfEtape(): ?WfEtapeService
    {
        if (!$this->serviceWfEtape){
            $this->serviceWfEtape = \Application::$container->get(WfEtapeService::class);
        }

        return $this->serviceWfEtape;
    }
}