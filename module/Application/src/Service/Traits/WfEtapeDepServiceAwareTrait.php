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
    protected ?WfEtapeDepService $serviceWfEtapeDep = null;



    /**
     * @param WfEtapeDepService $serviceWfEtapeDep
     *
     * @return self
     */
    public function setServiceWfEtapeDep(?WfEtapeDepService $serviceWfEtapeDep)
    {
        $this->serviceWfEtapeDep = $serviceWfEtapeDep;

        return $this;
    }



    public function getServiceWfEtapeDep(): ?WfEtapeDepService
    {
        if (empty($this->serviceWfEtapeDep)) {
            $this->serviceWfEtapeDep = \OseAdmin::instance()->container()->get(WfEtapeDepService::class);
        }

        return $this->serviceWfEtapeDep;
    }
}