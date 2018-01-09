<?php

namespace Application\Service\Traits;

use Application\Service\WfEtapeService;

/**
 * Description of WfEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait WfEtapeServiceAwareTrait
{
    /**
     * @var WfEtapeService
     */
    private $serviceWfEtape;



    /**
     * @param WfEtapeService $serviceWfEtape
     *
     * @return self
     */
    public function setServiceWfEtape(WfEtapeService $serviceWfEtape)
    {
        $this->serviceWfEtape = $serviceWfEtape;

        return $this;
    }



    /**
     * @return WfEtapeService
     */
    public function getServiceWfEtape()
    {
        if (empty($this->serviceWfEtape)) {
            $this->serviceWfEtape = \Application::$container->get(WfEtapeService::class);
        }

        return $this->serviceWfEtape;
    }
}