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
    /**
     * @var WfEtapeDepService
     */
    private $serviceWfEtapeDep;



    /**
     * @param WfEtapeDepService $serviceWfEtapeDep
     *
     * @return self
     */
    public function setServiceWfEtapeDep(WfEtapeDepService $serviceWfEtapeDep)
    {
        $this->serviceWfEtapeDep = $serviceWfEtapeDep;

        return $this;
    }



    /**
     * @return WfEtapeDepService
     */
    public function getServiceWfEtapeDep()
    {
        if (empty($this->serviceWfEtapeDep)) {
            $this->serviceWfEtapeDep = \Application::$container->get('applicationWfEtapeDep');
        }

        return $this->serviceWfEtapeDep;
    }
}