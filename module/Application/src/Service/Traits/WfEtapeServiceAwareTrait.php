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
    protected ?WfEtapeService $serviceWfEtape = null;



    /**
     * @param WfEtapeService $serviceWfEtape
     *
     * @return self
     */
    public function setServiceWfEtape(?WfEtapeService $serviceWfEtape)
    {
        $this->serviceWfEtape = $serviceWfEtape;

        return $this;
    }



    public function getServiceWfEtape(): ?WfEtapeService
    {
        if (empty($this->serviceWfEtape)) {
            $this->serviceWfEtape = \OseAdmin::instance()->container()->get(WfEtapeService::class);
        }

        return $this->serviceWfEtape;
    }
}